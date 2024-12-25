<?php

namespace Modules\HumanResource\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\AccPredefineAccount;
use Modules\Accounts\Http\Traits\AccVoucherTrait;
use Modules\HumanResource\DataTables\EmployeesSalaryDataTable;
use Modules\HumanResource\Entities\ApplyLeave;
use Modules\HumanResource\Entities\Attendance;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\EmployeeSalaryType;
use Modules\HumanResource\Entities\Holiday;
use Modules\HumanResource\Entities\Loan;
use Modules\HumanResource\Entities\ManualAttendance;
use Modules\HumanResource\Entities\SalaryAdvance;
use Modules\HumanResource\Entities\SalaryGenerate;
use Modules\HumanResource\Entities\SalarySheetGenerate;
use Modules\HumanResource\Entities\SetupRule;
use Modules\HumanResource\Entities\WeekHoliday;
use Modules\Setting\Entities\Application;

class SalaryGenerateController extends Controller
{
    use AccVoucherTrait;

    public function __construct()
    {
        $this->middleware('permission:read_salary_generate')->only(['index', 'salaryGenerateForm', 'employeePayslip']);
        $this->middleware('permission:create_salary_generate', ['only' => ['create', 'store', 'salaryGenerate']]);
        $this->middleware('permission:update_salary_generate', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_salary_generate', ['only' => ['destroy']]);

        $this->middleware('permission:read_manage_employee_salary')->only(['employeeSalary']);
    }

    public function salaryGenerateForm()
    {
        return view('humanresource::salary-generate.salary-sheet', [
            'salary_sheets' => SalarySheetGenerate::all(),
        ]);
    }

    public function salaryGenerate2222(Request $request)
    {
        $request->validate([
            'salary_month' => 'required',
        ]);

        if (SalarySheetGenerate::where('name', $request->salary_month)->first()) {
            Toastr::error('Salary already generated for this month :)', 'Error');

            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $month = Carbon::parse($request->salary_month);
            $year_check = $month->year;
            $month_check = $month->month;

            $start_date = $month->firstOfMonth()->format('Y-m-d');
            $end_date = $month->lastOfMonth()->format('Y-m-d');
            $total_days = $month->daysInMonth;

            $salary_sheet_info = [
                'name' => $request->salary_month,
                'generate_date' => Carbon::now()->format('Y-m-d'),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'generate_by_id' => auth()->user()->id,
            ];
            $salary_sheet = new SalarySheetGenerate();
            $salary_sheet->create($salary_sheet_info); //save salary sheet info here

            $employees = Employee::with('employee_files')->where('is_active', true)->where('is_left', false)->get();

            if ($employees->count() > 0) {
                foreach ($employees as $employee) {

                    $leave = ApplyLeave::where('employee_id', $employee->id)->where('is_approved', true)->whereBetween('leave_approved_start_date', [$start_date, $end_date]);

                    $lwp_days = $leave->where('leave_type_id', 6)->sum('total_approved_day');

                    $total_work_days = ManualAttendance::select(DB::raw('MIN(time) as intime, MAX(time) as outtime,DATE(time) as mydate'), 'employee_id')
                        ->where('employee_id', $employee->id)
                        ->whereDate('time', '>=', $start_date)
                        ->whereDate('time', '<=', $end_date)
                        ->groupBy('mydate')->get()->count();

                    $total_leave_days = $leave->sum('total_approved_day');
                    $joinning_date = Carbon::parse($employee->joinning_date);

                    if (($joinning_date->year == $year_check) && ($joinning_date->month == $month_check)) {
                        $day = $joinning_date->format('d');
                    } else {
                        $day = 1;
                    }

                    $weekly_holiday = $this->get_weekly_holidays($month, $day);
                    $holiday = $this->holidaysInMonth($month);

                    if ($total_work_days > 1) {

                        $total_work_days = $total_work_days + $weekly_holiday + $holiday - $lwp_days;
                        $total_absense = ($total_days - $total_work_days);
                    } else {
                        $total_absense = $total_days;
                    }

                    $normal_working_days = $total_days - ($weekly_holiday + $holiday);

                    $employee_salary_types = EmployeeSalaryType::where('employee_id', $employee->id)->with('setup_rule')->get();
                    $employee_allowances = $employee_salary_types->filter(function ($value) {
                        return $value->type == 'allowance';
                    })->sum('amount');
                    $employee_deductions = $employee_salary_types->filter(function ($value) {
                        return $value->type == 'deduction';
                    })->sum('amount');

                    $gross = $employee->employee_files?->gross_salary ?? 0;

                    $basic = $employee_salary_types->filter(function ($value) {
                        return $value->type == 'basic';
                    })->sum('amount');

                    //salary advance & loan amount for this month
                    $salary_advance_amount = $this->get_salary_advance_by_employee($employee->id, $request->salary_month);
                    $loan_amount = $this->get_loan_amount_by_employee($employee->id, $start_date, $end_date, $month_check);

                    $leave_without_pay = ($gross / $total_days) * ($total_absense);
                    $total_deductions = ($salary_advance_amount + $loan_amount + $employee_deductions + $leave_without_pay);

                    $net_salary = $gross - $total_deductions;

                    $loan = Loan::where('employee_id', $employee->id)
                        ->whereMonth('repayment_start_date', "<=", $month_check)
                        ->whereColumn('installment_cleared', "<=", 'installment_period')
                        ->where('is_active', true)->first();

                    $salary_advance = SalaryAdvance::where('employee_id', $employee->id)->where('salary_month', $request->salary_month)->where('is_active', true)->first();

                    $salary_info = new SalaryGenerate();
                    $salary_info->employee_id = $employee->id;
                    $salary_info->loan_id = $loan->id ?? null;
                    $salary_info->salary_advanced_id = $salary_advance->id ?? null;
                    $salary_info->salary_month_year = $request->salary_month;
                    $salary_info->tin_no = $employee->employee_files?->tin_no ?? 0;
                    $salary_info->total_attendance = $total_days ?? 0;
                    $salary_info->total_count = $total_work_days ?? 0;
                    $salary_info->salary_month_year = $request->salary_month;

                    $salary_info->gross = $$employee->employee_files?->gross_salary ?? 0;
                    $salary_info->basic = $basic ?? 0;

                    $salary_info->gross_salary = $employee->employee_files?->gross_salary ?? 0;
                    $salary_info->loan_deduct = $loan_amount;
                    $salary_info->salary_advance = $salary_advance_amount;
                    $salary_info->leave_without_pay = $leave_without_pay;
                    $salary_info->net_salary = $net_salary;
                    $salary_info->normal_working_hrs_month = $normal_working_days * 8;
                    $salary_info->actual_working_hrs_month = $this->get_total_work_hours($employee->id, $start_date, $end_date);

                    $salary_info->total_allowance = $employee_allowances;
                    $salary_info->total_deduction = $total_deductions;

                    $salary_info->save();

                    if (!empty($salary_advance)) {
                        $salary_advance_info = SalaryAdvance::find($salary_advance->id);
                        $salary_advance_info->release_amount = $salary_advance_amount;
                        $salary_advance_info->paid = 1;
                        $salary_advance_info->update();
                    }

                    if (!empty($loan)) {
                        $loan_info = Loan::find($loan->id);

                        if ($loan_info) {
                            $installment_clear = $loan->installment_cleared ?? 0;
                            $released_amount = $loan->released_amount ?? 0;
                            $loan_info->installment_cleared = $installment_clear + 1;
                            $loan_info->released_amount = $released_amount + $loan_amount;
                            $loan_info->update();
                        }
                    }
                }
            }

            DB::commit();

            Toastr::success('Salary Generated successfully :)', 'Success');

            return redirect()->route('salary.generate')->with('success', 'Salary Generated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::Error('Something Went Wrong :)', 'Error');

            return redirect()->route('salary.generate')->with('fail', $e);
        }
    }

    public function salaryGenerate_old(Request $request)
    {

        $request->validate([
            'salary_month' => 'required',
        ]);

        if (SalarySheetGenerate::where('name', $request->salary_month)->first()) {
            Toastr::error('Salary already generated for this month :)', 'Error');

            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $month = Carbon::parse($request->salary_month);
            $start_date = $month->firstOfMonth()->format('Y-m-d');
            $end_date = $month->lastOfMonth()->format('Y-m-d');
            $total_days = $month->daysInMonth;
            $month_check = $month->month;

            $salary_sheet_info = [
                'name' => $request->salary_month,
                'generate_date' => Carbon::now()->format('Y-m-d'),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'generate_by_id' => auth()->user()->id,
            ];
            $salary_sheet = new SalarySheetGenerate();
            $salary_sheet->create($salary_sheet_info); //save salary sheet info here

            $employees = Employee::with('employee_files')->where('is_active', 1)->where('is_left', 0)->get();
            dd($employees);

            if ($employees->count() > 0) {
                foreach ($employees as $employee) {

                    $leave = ApplyLeave::where('employee_id', $employee->id)->where('is_approved', true)->whereBetween('leave_approved_start_date', [$start_date, $end_date]);

                    $lwp_days = $leave->where('leave_type_id', 5)->sum('total_approved_day');

                    $total_work_days = ManualAttendance::select(DB::raw('MIN(time) as intime, MAX(time) as outtime,DATE(time) as mydate'), 'employee_id')
                        ->where('employee_id', $employee->id)
                        ->whereDate('time', '>=', $start_date)
                        ->whereDate('time', '<=', $end_date)
                        ->groupBy('mydate')->get()->count();

                    $total_leave_days = $leave->sum('total_approved_day');
                    $weekly_holiday = $this->get_weekly_holidays($month);

                    $holiday = $this->holidaysInMonth($month);
                    if ($total_work_days > 1) {

                        $total_absense = $total_days - ($total_work_days + $weekly_holiday + $holiday + $total_leave_days +
                            $lwp_days);
                    } else {

                        $total_absense = $total_days;
                    }

                    $normal_working_days = $total_days - ($weekly_holiday + $holiday);

                    $employee_salary_types = EmployeeSalaryType::where('employee_id', $employee->id)->with('setup_rule')->get();
                    $employee_allowances = $employee_salary_types->filter(function ($value) {
                        return $value->type == 'allowance';
                    })->sum('amount');
                    $employee_deductions = $employee_salary_types->filter(function ($value) {
                        return $value->type == 'deduction';
                    })->sum('amount');

                    $gross = $employee->employee_files?->gross_salary ?? 0;

                    $basic = $employee_salary_types->filter(function ($value) {
                        return $value->type == 'basic';
                    })->sum('amount');

                    //salary advance & loan amount for this month
                    $salary_advance_amount = $this->get_salary_advance_by_employee($employee->id, $request->salary_month);
                    $loan_amount = $this->get_loan_amount_by_employee($employee->id, $start_date, $end_date, $month_check);

                    $leave_without_pay = ($gross / $total_days) * ($total_absense);
                    $total_deductions = ($salary_advance_amount + $loan_amount + $employee_deductions + $leave_without_pay);
                    $net_salary = $gross - $total_deductions;

                    $loan = Loan::where('employee_id', $employee->id)
                        ->whereMonth('repayment_start_date', "<=", $month_check)
                        ->whereColumn('installment_cleared', "<=", 'installment_period')
                        ->where('is_active', true)->first();

                    $salary_advance = SalaryAdvance::where('employee_id', $employee->id)->where('salary_month', $request->salary_month)->where('is_active', true)->first();

                    $salary_info = new SalaryGenerate();
                    $salary_info->employee_id = $employee->id;
                    $salary_info->loan_id = $loan->id ?? null;
                    $salary_info->salary_advanced_id = $salary_advance->id ?? null;
                    $salary_info->salary_month_year = $request->salary_month;
                    $salary_info->tin_no = $employee->employee_files?->tin_no ?? 0;
                    $salary_info->total_attendance = $total_days ?? 0;
                    $salary_info->total_count = $total_work_days ?? 0;
                    $salary_info->salary_month_year = $request->salary_month;

                    $salary_info->gross = $$employee->employee_files?->gross_salary ?? 0;
                    $salary_info->basic = $basic ?? 0;

                    $salary_info->gross_salary = $employee->employee_files?->gross_salary ?? 0;
                    $salary_info->loan_deduct = $loan_amount;
                    $salary_info->salary_advance = $salary_advance_amount;
                    $salary_info->leave_without_pay = $leave_without_pay;
                    $salary_info->net_salary = $net_salary;
                    $salary_info->normal_working_hrs_month = $normal_working_days * 8;
                    $salary_info->actual_working_hrs_month = $this->get_total_work_hours($employee->id, $start_date, $end_date);

                    $salary_info->total_allowance = $employee_allowances;
                    $salary_info->total_deduction = $total_deductions;

                    $salary_info->save();

                    if (!empty($salary_advance)) {
                        $salary_advance_info = SalaryAdvance::find($salary_advance->id);
                        $salary_advance_info->release_amount = $salary_advance_amount;
                        $salary_advance_info->paid = 1;
                        $salary_advance_info->update();
                    }

                    if (!empty($loan)) {
                        $loan_info = Loan::find($loan->id);
                        if ($loan_info) {
                            $installment_clear = $loan->installment_cleared ?? 0;
                            $released_amount = $loan->released_amount ?? 0;
                            $loan_info->installment_cleared = $installment_clear + 1;
                            $loan_info->released_amount = $released_amount + $loan_amount;
                            $loan_info->update();
                        }
                    }
                }
            }

            DB::commit();

            Toastr::success('Salary Generated successfully :)', 'Success');
            return redirect()->route('salary.generate')->with('success', 'Salary Generated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::Error('Something Went Wrong :)', 'Error');
            return redirect()->route('salary.generate')->with('fail', $e);
        }
    }

    public function salaryGenerate(Request $request)
    {

        $request->validate([
            'salary_month' => 'required',
        ]);

        if (SalarySheetGenerate::where('name', $request->salary_month)->first()) {
            Toastr::error('Salary already generated for this month :)', 'Error');

            return redirect()->back();
        }

        DB::beginTransaction();
        try {

            $setting = Application::first();

            $salary_month = Carbon::parse($request->salary_month);
            $start_date = $startd = $salary_month->firstOfMonth()->format('Y-m-d');
            $end_date = $edate = $salary_month->lastOfMonth()->format('Y-m-d');
            $total_days = $salary_month->daysInMonth;
            $month = $salary_month->month;

            $salary_sheet_info = [
                'name' => $request->salary_month,
                'generate_date' => Carbon::now()->format('Y-m-d'),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'generate_by_id' => auth()->user()->id,
            ];
            $salary_sheet = new SalarySheetGenerate();
            $salary_sheet->create($salary_sheet_info); //save salary sheet info here

            $employees = Employee::with('employee_files')->where('is_active', 1)->where('is_left', 0)->get();

            if ($employees->count() > 0) {
                $res_arr = [];
                foreach ($employees as $key => $value) {

                    $emp_id = $value->id;
                    $employee_file = $value->employee_files;
                    if ($employee_file) {

                        // Hourly rate computing along with transport allowance
                        $worked_hours = $this->employee_worked_hours($emp_id, $startd, $edate);

                        $actual_working_hrs_month = floatval($worked_hours); 
                        // this is for calculation 
                        $month_actual_work_hrs = floatval($worked_hours);

                        // Check if actual_working_hrs_month by employee is greater than monthly_work_hours, then set monthly_work_hours as his/her actual_working_hrs_month for now..
                        if ($actual_working_hrs_month > floatval($value->monthly_work_hours)) {
                            $actual_working_hrs_month = floatval($value->monthly_work_hours);
                        }

                        $hourly_rate_basic = floatval($employee_file->basic / $value->monthly_work_hours);
                        $hourly_rate_trasport_allowance = floatval($employee_file->transport / $value->monthly_work_hours);

                        $basic_salary_pro_rated = $basic_salary = floatval($hourly_rate_basic * $actual_working_hrs_month);
                        $transport_allowance_pro_rated = floatval($hourly_rate_trasport_allowance * $actual_working_hrs_month);

                        // Benefits amounts
                        $total_benefits = 0.0;
                        $total_benefits = floatval($employee_file->medical_benefit) + floatval($employee_file->family_benefit) + floatval($employee_file->transportation_benefit) + floatval($employee_file->other_benefit);

                        $basic_transport_allowance = $gross_salary =  $basic_salary_pro_rated + $transport_allowance_pro_rated + $total_benefits;
                        /*End of Hourly rate compution along with transport allowance*/

                        /* Start of tax calculation*/
                        $state_income_tax = 0.0;
                        // Check if employee type is Full_time and Tin no is not null
                        if ($employee_file->tin_no != null && (int)$value->employee_type_id == 3) {
                            $state_income_tax = floatval($this->state_income_tax($gross_salary));
                        }

                        // Check if employee SOS.Sec.NPF is available
                        $soc_sec_npf_tax = 0.0;
                        $employer_contribution = 0.0;
                        $icf_amount = 0.0;

                        $soc_sec_npf_tax_percnt = floatval($setting->soc_sec_npf_tax);
                        $employer_contribution_percnt = floatval($setting->employer_contribution);
                        $setting_icf_amount           = floatval($setting->icf_amount);

                        if ($value->sos != "" && (int)$value->employee_type_id == 3) {
                            $soc_sec_npf_tax = floatval(($basic_salary * $soc_sec_npf_tax_percnt) / 100);
                            // Employer contribution is $employer_contribution_percnt of basic salary..
                            $employer_contribution = floatval(($basic_salary * $employer_contribution_percnt) / 100);
                            if ($basic_salary > 0) {
                                $icf_amount = $setting_icf_amount;
                            }
                        }
                        /* End  of tax calculation*/

                        /* Starts of loan and salary advance deduction*/
                        $salary_advance = 0.0;
                        $salary_advance_id = null;
                        $salary_advance_resp = $this->salary_advance_deduction($emp_id, $request->salary_month);

                        if ($salary_advance_resp) {

                            $salary_advance = floatval($salary_advance_resp->amount);
                            $salary_advance_id = $salary_advance_resp->id;
                        }
                        $loan_deduct = 0.0;
                        $loan_id = null;
                        $loan_installment_resp = $this->loan_installment_deduction($emp_id);
                        if ($loan_installment_resp) {

                            $loan_deduct = floatval($loan_installment_resp->installment);
                            $loan_id = $loan_installment_resp->id;
                        }

                        /*Net salary calculation*/
                        $net_salary = 0.0;
                        $total_deductions =  0.0;
                        $total_deductions = ($state_income_tax + $soc_sec_npf_tax + $loan_deduct + $salary_advance);
                        $net_salary       = ($gross_salary - $total_deductions);

                        /*Ends*/

                        $paymentData = array(
                            'employee_id'                     => $emp_id,
                            'tin_no'                            => $employee_file->tin_no,
                            'total_attendance'                => $value->monthly_work_hours, //tagret_hours / days
                            'total_count'                        => $month_actual_work_hrs, //weorked_hours / days
                            'gross'                           => $employee_file->gross_salary,
                            'basic'                           => $employee_file->basic,
                            'transport'                       => $employee_file->transport,
                            'gross_salary'                    => $gross_salary,
                            'income_tax'                      => $state_income_tax,
                            'soc_sec_npf_tax'                 => $soc_sec_npf_tax,
                            'employer_contribution'           => $employer_contribution,
                            'icf_amount'                      => $icf_amount,
                            'loan_deduct'                     => $loan_deduct,
                            'loan_id'                         => $loan_id,
                            'salary_advance'                  => $salary_advance,
                            'salary_advanced_id'              => $salary_advance_id,
                            'total_deduction'                 => $total_deductions,
                            'net_salary'                      => $net_salary,
                            'salary_month_year'               => $request->salary_month,
                            'medical_benefit'                  => floatval($employee_file->medical_benefit),
                            'family_benefit'                  => floatval($employee_file->family_benefit),
                            'transportation_benefit'          => floatval($employee_file->transportation_benefit),
                            'other_benefit'                      => floatval($employee_file->other_benefit),
                            'normal_working_hrs_month'        => $value->monthly_work_hours,
                            'actual_working_hrs_month'        => $month_actual_work_hrs,
                            'hourly_rate_basic'               => $hourly_rate_basic,
                            'hourly_rate_trasport_allowance'  => $hourly_rate_trasport_allowance,
                            'basic_salary_pro_rated'             => $basic_salary_pro_rated,
                            'transport_allowance_pro_rated'   => $transport_allowance_pro_rated,
                            'basic_transport_allowance'          => $basic_transport_allowance,
                        );

                        // Create salary data for the employee
                        $res_sal_gen = SalaryGenerate::create($paymentData);
                        if ($res_sal_gen) {
                            // Update salary advance afetr applying it to salary generate
                            if ($salary_advance_resp) {
                                $sal_adv_data = array(
                                    'release_amount'  => $salary_advance,
                                );
                                $salary_adv_paid_resp = $this->update_sal_advance($sal_adv_data, $salary_advance_id);
                            }

                            // Update loan after applying it to salary generate
                            if ($loan_installment_resp) {

                                $total_released_amount = 0.0;
                                $total_released_amount = floatval($loan_installment_resp->released_amount) + $loan_deduct;

                                $total_installment_cleared = 0;
                                $total_installment_cleared = (int)$loan_installment_resp->installment_cleared + 1;

                                $loan_installment_data = array(
                                    'installment_cleared' => $total_installment_cleared,
                                    'released_amount'    => $total_released_amount,
                                );
                                $loan_installmnt_paid_respo = $this->update_loan_installment($loan_installment_data, $loan_id);
                            }
                        }
                    }
                }
            }

            DB::commit();

            Toastr::success('Salary Generated successfully :)', 'Success');
            return redirect()->route('salary.generate')->with('success', 'Salary Generated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::Error('Something Went Wrong :)', 'Error');
            return redirect()->route('salary.generate')->with('fail', $e);
        }
    }

    public function update_loan_installment($data = array(), $id = null)
    {
        return Loan::where('id', $id)->update($data);
    }

    public function update_sal_advance($data = array(), $id = null)
    {
        return SalaryAdvance::where('id', $id)->update($data);
    }

    public function loan_installment_deduction($emp_id)
    {
        $loan_status = 1;

        $queryResult = DB::table('loans')
            ->select('*')
            ->where('employee_id', $emp_id)
            ->where('is_active', $loan_status)
            ->whereRaw('(`installment_period` - `installment_cleared`) > 0')
            ->first();

        return $queryResult;
    }

    public function salary_advance_deduction($emp_id, $salary_month)
    {
        $query = "SELECT * FROM `salary_advances` WHERE `salary_month` = ? AND `employee_id` = ? AND (`amount` - `release_amount`) > ?";
        $results = DB::select($query, [$salary_month, $emp_id, 0]);

        // Assuming you expect only one row, you can retrieve it like this:
        $result = !empty($results) ? $results[0] : null;
        return $result;
    }

    /* Calculate state income tax*/
    public function state_income_tax($gross_salary)
    {


        $tax_calculations = DB::table('tax_calculations')
            ->get();

        $tax_amount = 0.0;
        $remaining_amnt = $gross_salary;

        foreach ($tax_calculations as $row) {

            $flag = 1; 
            // to enter the bottom if condition..
            $salary_tax = 0.0;

            if ($flag == 1 && floatval($remaining_amnt) > 0 && floatval($remaining_amnt) >= floatval($row->max) && floatval($row->min) != floatval($row->max)) {

                $remaining_amnt = floatval($remaining_amnt) - floatval($row->max);
                $salary_tax = ($row->max * floatval($row->tax_percent)) / 100;
                $tax_amount =  $tax_amount + floatval($row->add_amount) +  floatval($salary_tax);

                $flag = 0;
            } else if ($flag == 1 && floatval($remaining_amnt) > 0 && floatval($remaining_amnt) < floatval($row->max) && floatval($row->min) != floatval($row->max)) {

                $salary_tax = ($remaining_amnt * floatval($row->tax_percent)) / 100;
                $tax_amount =  $tax_amount + floatval($row->add_amount) +  floatval($salary_tax);
                $remaining_amnt = 0.0;
            }
        }

        return $tax_amount;
    }

    // Get employee worked hours for the requested date range/ month
    public function employee_worked_hours($employee_id, $startd, $edate)
    {

        $startd  = $startd;
        $end     = $edate;

        $att_in = DB::table('attendances as a')
            ->selectRaw('a.time, MIN(a.time) as intime, MAX(a.time) as outtime, a.employee_id as uid, DATE(a.time) as mydate')
            ->where('a.employee_id', $employee_id)
            ->whereDate('a.time', '>=', date('Y-m-d', strtotime($startd)))
            ->whereDate('a.time', '<=', date('Y-m-d', strtotime($end)))
            ->groupByRaw('DATE(a.time)')
            ->get();

        $idx = 1;
        $totalhour = [];
        $totalday = [];

        foreach ($att_in as $attendancedata) {

            $date_a = Carbon::createFromFormat('Y-m-d H:i:s', $attendancedata->outtime);
            $date_b = Carbon::createFromFormat('Y-m-d H:i:s', $attendancedata->intime);

            // Calculate the interval
            $interval = $date_a->diff($date_b);

            // Format the interval
            $totalwhour = $interval->format('%h:%I:%S'); 
            // Adjust format as needed
            $totalhour[$idx] = $totalwhour;
            $totalday[$idx] = $attendancedata->mydate;

            $idx++;
        }

        $seconds = 0;

        foreach ($totalhour as $t) {
            $timeArr = array_reverse(explode(":", $t));

            foreach ($timeArr as $key => $tv) {
                if ($key > 2) break;
                $seconds += pow(60, $key) * $tv;
            }
        }

        $hours = floor($seconds / 3600);
        $mins = floor(($seconds - ($hours * 3600)) / 60);
        $secs = floor($seconds % 60);
        $times = $hours * 3600 + $mins * 60 + $secs;;

        // end new salary generate		
        $wormin = ($times / 60);
        $worhour = number_format($wormin / 60, 2);

        return $worhour;
    }

    public function salaryChart($uuid)
    {
        $salary_sheet = SalarySheetGenerate::where('uuid', $uuid)->firstOrFail();

        $salary_infos = SalaryGenerate::where('salary_month_year', $salary_sheet->name)
            ->with(['employee.allowanceDeduction.setup_rule'])->get();

        $salary_setup_rules = SetupRule::where('type', 'allowance')->orWhere('type', 'deduction')
            ->orderBy('type')->get();

        return view('humanresource::salary-generate.salary-chart', [
            'salary_sheet' => $salary_sheet,
            'salary_infos' => $salary_infos,
            'salary_setup_rules' => $salary_setup_rules,
        ]);
    }

    public function getSalaryApproval($uuid)
    {
        $salary_sheet = SalarySheetGenerate::where('uuid', $uuid)->firstOrFail();
        $salary_infos = SalaryGenerate::where('salary_month_year', $salary_sheet->name)->get();
        $credit_accounts = AccCoa::where('head_level', 4)
            ->where('is_cash_nature', 1)
            ->orWhere('is_bank_nature', 1)
            ->where('is_active', true)
            ->get();

        return view('humanresource::salary-generate.salary-approval', [
            'salary_sheet' => $salary_sheet,
            'gross_salary' => $salary_infos->sum('gross_salary'),
            'net_salary' => $salary_infos->sum('net_salary'),
            'salary_advance' => $salary_infos->sum('salary_advance'),
            'loan_deduct' => $salary_infos->sum('loan_deduct'),
            'income_tax' => $salary_infos->sum('income_tax'),
            'soc_sec_npf_tax' => $salary_infos->sum('soc_sec_npf_tax'),
            'employer_contribution' => $salary_infos->sum('employer_contribution'),
            'icf_amount' => $salary_infos->sum('icf_amount'),
            'credit_accounts' => $credit_accounts,
        ]);
    }

    public function salaryApproval(Request $request, $uuid)
    {
        DB::beginTransaction();
        try {
            $salary_sheet = SalarySheetGenerate::where('uuid', $uuid)->firstOrFail();
            $predefine_coa = AccPredefineAccount::first();

            $salary_sheet->is_approved = true;
            $salary_sheet->approved_by = auth()->user()->id;
            $salary_sheet->approved_date = Carbon::now();
            $salary_sheet->update();

            DB::commit();
            Toastr::success('Salary Approval Posting successfully :)', 'Success');

            return redirect()->route('salary.generate')->with('message', 'Salary Approved successfully');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::Error('Something Went Wrong :)', 'Error');

            return redirect()->back()->with('fail', $e);
        }
    }

    public function employeeSalary(EmployeesSalaryDataTable $dataTable)
    {
        return $dataTable->render('humanresource::salary-generate.employee-salary');
    }

    public function employeePayslip($uuid)
    {
        $salary_info = SalaryGenerate::where('uuid', $uuid)->firstOrFail();
        $salary_sheet = SalarySheetGenerate::where('name', $salary_info->salary_month_year)->first();

        $rules = EmployeeSalaryType::where('employee_id', $salary_info->employee_id)->get();
        $employee_info = Employee::where('id', $salary_info->employee_id)->where('is_active', 1)->first();

        $deductions = $rules->filter(function ($value) {
            return $value->type == 'deduction';
        });
        $allowances = $rules->filter(function ($value) {
            return $value->type == 'allowance';
        });

        return view('humanresource::salary-generate.employee-payslip', [
            'employee_info' => $employee_info,
            'salary_info'   => $salary_info,
            'salary_sheet'  => $salary_sheet,
            'allowances'    => $allowances,
            'deductions'    => $deductions,
        ]);
    }

    public function downloadPayslip($uuid)
    {
        $salary_info = SalaryGenerate::where('uuid', $uuid)->firstOrFail();
        $salary_sheet = SalarySheetGenerate::where('name', $salary_info->salary_month_year)->first();

        $rules = EmployeeSalaryType::where('employee_id', $salary_info->employee_id)->get();
        $employee_info = Employee::where('id', $salary_info->employee_id)->where('is_active', 1)->first();

        $deductions = $rules->filter(function ($value) {
            return $value->type == 'deduction';
        });
        $allowances = $rules->filter(function ($value) {
            return $value->type == 'allowance';
        });

        $pdf = Pdf::loadView('humanresource::salary-generate.employee-payslip-pdf', [
            'employee_info' => $employee_info,
            'salary_info' => $salary_info,
            'salary_sheet' => $salary_sheet,
            'allowances' => $allowances,
            'deductions' => $deductions,
        ]);

        return $pdf->download($salary_info->employee->full_name . '_' . $salary_info->salary_month_year . '_payslip.pdf');
    }

    private function get_total_work_hours($employee_id, $start_date, $end_date)
    {

        $all_attendances = ManualAttendance::select(DB::raw('MIN(time) as intime, MAX(time) as outtime,DATE(time) as mydate'), 'employee_id')
            ->where('employee_id', $employee_id)
            ->whereDate('time', '>=', $start_date)
            ->whereDate('time', '<=', $end_date)
            ->groupBy('mydate')->get();

        $total_hour = [];

        foreach ($all_attendances as $i => $attendance) {
            $date_out = new DateTime($attendance->outtime);
            $date_in = new DateTime($attendance->intime);
            $interval = date_diff($date_out, $date_in);
            $total_work_hour = $interval->format('%h:%i:%s');
            $total_hour[$attendance->mydate] = $total_work_hour;
        }

        $seconds = 0;

        foreach ($total_hour as $hour) {
            $timeArr = array_reverse(explode(":", $hour));

            foreach ($timeArr as $key => $tv) {

                if ($key > 2) {
                    break;
                }

                $seconds += pow(60, $key) * $tv;
            }
        }

        $hours = floor($seconds / 3600);
        $mins = floor(($seconds - ($hours * 3600)) / 60);
        $secs = floor($seconds % 60);
        $times = $hours * 3600 + $mins * 60 + $secs;

        $total_work_min = ($times / 60);
        $total_work_hour = number_format($total_work_min / 60, 2);

        return $total_work_hour;
    }

    private function get_salary_advance_by_employee($employee_id, $month)
    {
        return SalaryAdvance::where('employee_id', $employee_id)->where('salary_month', $month)->where('is_active', true)->sum('amount');
    }

    private function get_loan_amount_by_employee($employee_id, $start_date, $end_date, $month_check)
    {
        $loan_amount = Loan::where('employee_id', $employee_id)
            ->whereMonth('repayment_start_date', "<=", $month_check)
            ->whereColumn('installment_cleared', "<=", 'installment_period')
            ->where('is_active', true)->first();

        if ($loan_amount) {
            if ($loan_amount->installment_period > $loan_amount->installment_cleared || $loan_amount->repayment_amount > $loan_amount->released_amount) {
                return $loan_amount->installment;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function get_weekly_holidays($month)
    {
        $days = [];

        for ($x = 1; $x <= $month->daysInMonth; $x++) {
            array_push($days, date("l", strtotime($month->format('Y-m') . '-' . $x)));
        }

        $weekend = WeekHoliday::first();
        $weekends = explode(",", $weekend->dayname);
        $total_weekend = 0;

        foreach ($weekends as $week) {
            $total_weekend += array_count_values($days)[ucwords($week)];
        }

        return $total_weekend; //return total weekend in a month
    }

    private function holidaysInMonth($month)
    {
        $m_start_date = $month->firstOfMonth()->format('Y-m-d');
        $m_end_date = $month->lastOfMonth()->format('Y-m-d');

        $holidays = Holiday::whereBetween('start_date', [$m_start_date, $m_end_date])->orWhereBetween('end_date', [$m_start_date, $m_end_date])->get();
        $holiday = 0;

        foreach ($holidays as $key => $day) {

            if ($month->firstOfMonth() < Carbon::parse($day->end_date) && $month->firstOfMonth() > Carbon::parse($day->start_date)) {
                $holiday += Carbon::parse($day->end_date)->diffInDays($month->firstOfMonth());
            } else

            if ($month->lastOfMonth() > Carbon::parse($day->start_date) && $month->lastOfMonth() < Carbon::parse($day->end_date)) {
                $holiday += $month->lastOfMonth()->diffInDays(Carbon::parse($day->start_date));
            } else {
                $holiday += Carbon::parse($day->start_date)->diffInDays(Carbon::parse($day->end_date));
            }
        }

        return $holiday;
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($uuid)
    {
        DB::beginTransaction();
        try {

            $salaryGenerateDelete = SalarySheetGenerate::where('uuid', $uuid)->first();
            if ($salaryGenerateDelete->is_approved == 1) {
                return redirect()->back()->with('fail', localize('can_not_be_deleted_as_it_is_already_approved'));
            }

            // Start of reversing loan and salary advance amount if applied for any employee salary...for the month
            $salaries = SalaryGenerate::where('salary_month_year', $salaryGenerateDelete->name)->get();
            foreach ($salaries as $key => $row) {

                // Loan data
                if (floatval($row->loan_deduct) > 0) {
                    // Get loan data
                    $loan_data = Loan::where('id', $row->loan_id)->first();
                    // Deduction loan data
                    $released_amount = floatval($loan_data->released_amount) - floatval($row->loan_deduct);
                    $installment_cleared = (int)$loan_data->installment_cleared - 1;

                    $loan_post_data = array(
                        'released_amount' => $released_amount,
                        'installment_cleared' => $installment_cleared,
                    );
                    // Update loan data
                    Loan::where('id', $row->loan_id)->update($loan_post_data);
                }

                // Salary advance data
                if (floatval($row->salary_advance) > 0) {
                    // Get Salary advance data
                    $salary_adv_data = SalaryAdvance::where('id', $row->salary_advanced_id)->first();
                    // Deduction Salary advance data
                    $salary_advance = floatval($salary_adv_data->release_amount) - floatval($row->salary_advance);
                    $salary_advance_post_data = array(
                        'release_amount' => $salary_advance,
                    );
                    // Update Salary advance data
                    SalaryAdvance::where('id', $row->salary_advanced_id)->update($salary_advance_post_data);
                }
            }

            SalaryGenerate::where('salary_month_year', $salaryGenerateDelete->name)->delete();
            $salaryGenerateDelete->delete();

            DB::commit();
            return response()->json(['success' => 'success']);
        } catch (\Exception $e) {

            DB::rollback();
            return redirect()->back()->with('fail', localize('data_save_error'));
        }
    }
}
