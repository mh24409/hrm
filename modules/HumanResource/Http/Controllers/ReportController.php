<?php

namespace Modules\HumanResource\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\HumanResource\Entities\Holiday;
use Illuminate\Database\Eloquent\Collection;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\Position;
use Modules\HumanResource\Entities\LeaveType;
use Modules\HumanResource\Entities\Attendance;
use Modules\HumanResource\Entities\Department;
use Modules\HumanResource\Entities\WeekHoliday;
use Modules\HumanResource\Entities\SalaryAdvance;
use Modules\HumanResource\DataTables\JobCardDataTable;
use Modules\HumanResource\Entities\EmployeeSalaryType;
use Modules\HumanResource\DataTables\AllowanceDataTable;
use Modules\HumanResource\DataTables\LeaveReportDataTable;
use Modules\HumanResource\DataTables\ContractRenewalDataTable;
use Modules\HumanResource\DataTables\StaffAttendanceDataTable;
use Modules\HumanResource\DataTables\AttendanceSummaryDataTable;
use Modules\HumanResource\DataTables\DailyPresentReportDataTable;
use Modules\HumanResource\DataTables\EmployeeReportDataTable;
use Modules\HumanResource\DataTables\MonthPresentReportDataTable;
use Modules\HumanResource\DataTables\LateClosingAttendanceDataTable;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_attendance_report')->only('staffAttendanceReport');
        $this->middleware('permission:read_attendance_details_report')->only(['staffAttendanceDetailReport']);
        $this->middleware('permission:read_job_card_report')->only(['jobCardReport']);
        $this->middleware('permission:read_attendance_summary')->only('attendanceSummery');
        $this->middleware('permission:read_contract_renewal_report')->only('contractRenewalReport');
        $this->middleware('permission:read_allowance_report')->only('allowanceReport');
        $this->middleware('permission:read_deduction_report')->only('deductionReport');
        $this->middleware('permission:read_leave_report')->only('leaveReport');

        $this->middleware('permission:read_payroll_report')->only('npf3SocSecTaxReport');
    }

    public function staffAttendanceReport(StaffAttendanceDataTable $dataTable)
    {

        return $dataTable->render('humanresource::reports.staff-attendance', [
            'departments' => Department::where('is_active', true)->get(),
            'positions' => Position::where('is_active', true)->get(),
        ]);
    }

    public function staffAttendanceDetailReport($employee_id, Request $request)
    {
        $date = $request->date ? $request->date : Carbon::today()->format('Y-m-d');

        $attendances = Attendance::where('employee_id', $employee_id)->whereDate('time', '=', $date)->get();

        $employee = Employee::find($employee_id);

        return view('humanresource::reports.staff-attendance-detail', [
            'employee_id' => $employee_id,
            'date' => $date,
            'attendances' => $attendances,
            'employee' => $employee,
        ]);
    }

    public function jobCardReport(JobCardDataTable $dataTable)
    {
        return $dataTable->render('humanresource::reports.job-card', [
            'employees' => Employee::where('is_active', true)->where('is_left', false)->get(),
        ]);
    }

    public function jobCardReportShow(Request $request)
    {
        $string = explode(' - ', $request->date);
        $fromDate = $string[0];
        $toDate = $string[1];

        $fromDate = date('Y-m-d', strtotime($string[0]));
        $toDate = date('Y-m-d', strtotime($string[1]));

        $employee_id = $request->employee_id;
        $start_date = $fromDate;
        $end_date = $toDate;

        $attendances = Attendance::selectRaw('employee_id, DATE(time) as date, MIN(time) as in_time, MAX(time) as out_time')
            ->with('employee.attendance_time')
            ->where('employee_id', $employee_id)
            ->whereDate('time', '>=', $start_date)
            ->whereDate('time', '<=', $end_date)
            ->groupBy('date')->get();

        $start_date_arr = Carbon::parse($fromDate);
        $end_date_arr = Carbon::parse($toDate);

        $collection = new Collection();

        $weekend = WeekHoliday::first();
        $weekends_array = explode(',', $weekend->dayname);

        $weekends = array_map(function ($weekend) {
            return ucfirst($weekend);
        }, $weekends_array);

        while ($start_date_arr <= $end_date_arr) {

            $currentDateData = $attendances->firstWhere('date', $start_date_arr->format('Y-m-d'));

            if ($currentDateData) {
                $collection->push((object) [
                    'employee_id' => (int) $request->employee_id,
                    'date' => $currentDateData->date,
                    'in_time' => $currentDateData->in_time,
                    'out_time' => $currentDateData->out_time,
                    'roaster' => $currentDateData->employee?->employee_group?->start_time,
                    'status' => "Present",
                ]);
            } else {
                $holidays = Holiday::whereDate('start_date', '<=', $start_date_arr)
                    ->whereDate('end_date', '>=', $start_date_arr)->get()->toArray();
                $weekly_holiday_check = $start_date_arr->format('l');

                if (count($holidays) > 0) {
                    $collection->push((object) [
                        'employee_id' => (int) $request->employee_id,
                        'date' => $start_date_arr->format('Y-m-d'),
                        'in_time' => '',
                        'out_time' => '',
                        'roaster' => '',
                        'status' => 'Holiday',
                    ]);
                } elseif (in_array($weekly_holiday_check, $weekends)) {
                    $collection->push((object) [
                        'employee_id' => (int) $request->employee_id,
                        'date' => $start_date_arr->format('Y-m-d'),
                        'in_time' => '',
                        'out_time' => '',
                        'roaster' => '',
                        'status' => 'Weekend',
                    ]);
                } else {
                    $collection->push((object) [
                        'employee_id' => (int) $request->employee_id,
                        'date' => $start_date_arr->format('Y-m-d'),
                        'in_time' => '',
                        'out_time' => '',
                        'roaster' => '',
                        'status' => 'Absent',
                    ]);
                }
            }

            $start_date_arr->addDay();
        }

        $employee = Employee::find($request->employee_id);

        return view('humanresource::reports.job-card-report', compact('attendances', 'employee', 'fromDate', 'toDate', 'collection'));
    }

    public function attendanceSummery(AttendanceSummaryDataTable $dataTable)
    {

        return $dataTable->render('humanresource::reports.attendance-summery', [
            'departments' => Department::where('is_active', true)->get(),
        ]);
    }

    public function contractRenewalReport(ContractRenewalDataTable $dataTable)
    {
        return $dataTable->render('humanresource::reports.contract-renewal', [
            'departments' => Department::where('is_active', true)->get(),
        ]);
    }

    public function deductionReport(Request $request)
    {
        $departments = Department::where('is_active', true)->get();
        $deductions = EmployeeSalaryType::with('employee')->whereNotNull('id');

        if ($request->department_id) {
            $deductions = $deductions->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        $allEmployee = $deductions->whereHas('setup_rule', function ($query) {
            $query->where('type', 'deduction');
        })->with('setup_rule')->get()->groupBy('employee_id');

        return view('humanresource::reports.deduction', [
            'request' => $request,
            'departments' => $departments,
            'allEmployee' => $allEmployee,
        ]);
    }

    public function allowanceReport(Request $request, AllowanceDataTable $dataTable)
    {

        $departments = Department::where('is_active', true)->get();
        $positions = Position::where('is_active', true)->get();
        $allowances = EmployeeSalaryType::whereNotNull('id');

        if ($request->department_id) {
            $allowances = $allowances->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        if ($request->position_id) {
            $allowances = $allowances->whereHas('employee', function ($q) use ($request) {
                $q->where('position_id', $request->position_id);
            });
        }

        $allEmployee = $allowances->whereHas('setup_rule', function ($query) {
            $query->where('type', 'allowance');
        })->with('setup_rule')->get()->groupBy('employee_id');

        return view('humanresource::reports.allowance', [
            'request' => $request,
            'departments' => $departments,
            'positions' => $positions,
            'allEmployee' => $allEmployee,
        ]);
    }

    public function leaveReport(Request $request, LeaveReportDataTable $dataTable)
    {
        return $dataTable->render('humanresource::reports.leave', [
            'departments' => Department::where('is_active', true)->get(),
            'employees' => Employee::where('is_active', true)->get(['id', 'first_name', 'last_name', 'middle_name']),
            'leave_types' => LeaveType::all(),
        ]);
    }

    public function salaryAdvanceReport(Request $request)
    {

        $advance = SalaryAdvance::whereNotNull('id');
        if ($request->employee_id) {
            $advance = $advance->where('employee_id', $request->employee_id);
        }
        if ($request->start_date && $request->end_date) {
            $advance->whereBetween('approved_date', [$request->start_date, $request->end_date]);
        }
        $employees = Employee::where('is_active', true)->get();
        $advance = $advance->get();
        $total_advance = $advance->sum('amount');
        $total_amount = $advance->sum('repayment_amount');

        return view('humanresource::reports.salary-advance', [
            'employees' => $employees,
            'advance' => $advance,
            'total_advance' => $total_advance,
            'total_amount' => $total_amount,
            'request' => $request,
        ]);
    }

    public function employeeWiseAttendanceSummery()
    {
        $employees = Employee::where('is_active', true)->get();

        return view('humanresource::reports.employee-wise-attendance-summary', [
            'employees' => $employees,
        ]);
    }

    public function employeeWiseAttendanceSummeryReports(Request $request)
    {
        $string = explode(' - ', $request->date);
        $fromDate = $string[0];
        $toDate = $string[1];
        $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');

        $employee_id = $request->employee_id;
        $start_date = $fromDate;
        $end_date = $toDate;

        $attendances = Attendance::select(
            'employee_id',
            'time',
            DB::raw('DATE(time) AS date'),
            DB::raw('TIME(time) AS raw_time')
        )
            ->where('employee_id', $employee_id)
            ->whereDate('time', '>=', $start_date)
            ->whereDate('time', '<=', $end_date)
            ->orderBy('time', 'ASC')
            ->get()
            ->groupBy('date');

        $workHoursByDate = [];
        $wastingTimeByDate = [];
        $netWorkHoursByDate = [];

        foreach ($attendances as $date => $logs) {
            $logCount = count($logs);
            foreach ($logs as $key => $log) {
                $log->count = $logCount;
                if ($key % 2 == 0) {
                    $log->status = 'in';
                } else {
                    $log->status = 'out';
                }
            }
            $times = $this->calculateWorkAndWastingTime($logs);
            $workHoursByDate[$date] = $times['work'];
            $wastingTimeByDate[$date] = $times['wasting'];
            $netWorkHoursByDate[$date] = $times['network'];
        }

        // sum workHoursByDate, wastingTimeByDate and netWorkHoursByDate
        $totalWorkSeconds = array_sum(array_map(function ($time) {
            return Carbon::parse($time)->diffInSeconds(Carbon::parse('00:00:00'));
        }, $workHoursByDate));
        $totalWastingSeconds = array_sum(array_map(function ($time) {
            return Carbon::parse($time)->diffInSeconds(Carbon::parse('00:00:00'));
        }, $wastingTimeByDate));
        $totalNetWorkSeconds = array_sum(array_map(function ($time) {
            return Carbon::parse($time)->diffInSeconds(Carbon::parse('00:00:00'));
        }, $netWorkHoursByDate));

        $totalWorkHours = $this->formatTime($totalWorkSeconds);
        $totalWastingHours = $this->formatTime($totalWastingSeconds);
        $totalNetWorkHours = $this->formatTime($totalNetWorkSeconds);

        $employee = Employee::find($request->employee_id);

        return view('humanresource::reports.employee-wise-attendance-summary-report', compact(
            'attendances',
            'employee',
            'fromDate',
            'toDate',
            'workHoursByDate',
            'wastingTimeByDate',
            'netWorkHoursByDate',
            'totalWorkHours',
            'totalWastingHours',
            'totalNetWorkHours'
        ));
    }

    public function latenessClosingAttendanceReport(LateClosingAttendanceDataTable $dataTable)
    {
        return $dataTable->render('humanresource::reports.lateness-closing-attendance', [
            'employees' => Employee::where('is_active', true)->get(),
        ]);
    }

    public function attendanceLogReport(Request $request)
    {
        $employees = Employee::where('is_active', true)->get();
        $employee_id = $request->employee_id;

        $date = explode(' - ', $request->date);

        $fromDate = $request->date ? Carbon::createFromFormat('d/m/Y', $date[0])->addDay()->format('Y-m-d') : Carbon::now()->subWeek()->format('Y-m-d');
        $toDate = $request->date ? Carbon::createFromFormat('d/m/Y', $date[1])->format('Y-m-d') : Carbon::now()->addDay()->format('Y-m-d');
        $perPage = 10;
        $currentPage = request()->input('page', 1);

        $dayWiseAttendances = Attendance::selectRaw('DATE(time) as date, MIN(time) as in_time, MAX(time) as out_time, COUNT(time) as count, employee_id')
            ->whereBetween(DB::raw('DATE(time)'), [$fromDate, $toDate])
            ->when($employee_id, function ($query) use ($employee_id) {
                $query->with('employee')->where('employee_id', $employee_id);
            })
            ->groupBy(['date', 'employee_id'])
            ->orderBy('date')
            ->paginate($perPage, ['*'], 'page', $currentPage);

        $dayWiseAttendancesPaginated = $dayWiseAttendances->groupBy('date');

        return view('humanresource::reports.attendance-log', [
            'employees' => $employees,
            'dayWiseAttendances' => $dayWiseAttendances,
            'dayWiseAttendancesPaginated' => $dayWiseAttendancesPaginated,
            'fromDate' => @$date[0],
            'toDate' => @$date[1],
            'date' => $request->date,
        ]);
    }

    public function attendanceLogEmployeeDetails(Employee $employee, Request $request)
    {
        $perPage = 10;
        $currentPage = request()->input('page', 1);

        $logsGroupedByDate = Attendance::with('employee:id,first_name,last_name,middle_name')
            ->selectRaw('id, DATE(time) as date, employee_id, time')
            ->where('employee_id', $employee->id)
            ->orderByDesc('date')
            ->orderBy('time')
            ->get()
            ->groupBy('date');

        // Manually paginate the grouped data
        $totalItems = $logsGroupedByDate->count();
        $start = ($currentPage - 1) * $perPage;
        $items = $logsGroupedByDate->slice($start, $perPage)->all();
        $logsPaginated = new \Illuminate\Pagination\LengthAwarePaginator($items, $totalItems, $perPage, $currentPage);

        return view('humanresource::reports.attendance-log-details', [
            'employee' => $employee,
            'attendances' => $logsGroupedByDate,
            'attendancesPaginated' => $logsPaginated,
        ]);
    }

    public function dailyPresentReport(DailyPresentReportDataTable $dataTable)
    {
        $departments = Department::where('is_active', true)->get();
        return $dataTable->render('humanresource::reports.daily-present', [
            'departments' => $departments,
        ]);
    }
    public function monthlyReport()
    {
        $departments = Department::where('is_active', true)->get();
        return view('humanresource::reports.monthly', [
            'departments' => $departments,
        ]);
    }

    public function monthlyReportShow(Request $request)
    {
        $employee_id = $request->employee_id;
        $department_id = $request->department_id;
        $month = $request->month;
        $year = $request->year;

        $start_date = Carbon::parse($year . '-' . $month . '-01')->format('Y-m-d');
        $end_date = Carbon::parse($year . '-' . $month . '-01')->endOfMonth()->format('Y-m-d');

        $collection = new Collection();

        $weekend = WeekHoliday::first();
        $weekends_array = explode(',', $weekend->dayname);

        $weekends = array_map(function ($weekend) {
            return ucfirst($weekend);
        }, $weekends_array);

        $employees = Employee::where('is_active', true)->get();

        $attendances = Attendance::selectRaw('employee_id, DATE(time) as date, COUNT(time) as count, MIN(time) as in_time, MAX(time) as out_time')
            ->with(
                [
                    'employee:id,first_name,last_name,middle_name,department_id,employee_id',
                    'employee.department:id,department_name'
                ]
            )
            ->when($department_id > 0, function ($query) use ($department_id) {
                $query->whereHas('employee', function ($q) use ($department_id) {
                    $q->where('department_id', $department_id);
                });
            })
            ->when($employee_id > 0, function ($query) use ($employee_id) {
                $query->where('employee_id', $employee_id);
            })
            ->whereDate('time', '>=', $start_date)
            ->whereDate('time', '<=', $end_date)
            ->groupBy('employee_id', 'date')
            ->orderBy('date', 'asc')
            ->get();

        while ($start_date <= $end_date) {

            $currentDateData = $attendances->where('date', $start_date);
            if ($currentDateData->isNotEmpty()) {
                foreach ($currentDateData as $data) {
                    $collection->push((object) [
                        'employee_id'     => $data?->employee?->employee_id,
                        'employee_name'   => $data?->employee?->full_name,
                        'department_name' => $data?->employee?->department?->department_name,
                        'date'            => $data->date,
                        'count'           => $data->count,
                        'in_time'         => $data->in_time,
                        'out_time'        => $data->out_time,
                        'status'          => "Present",
                    ]);
                }
            } else {
                $holidays = Holiday::whereDate('start_date', '<=', $start_date)
                    ->whereDate('end_date', '>=', $start_date)->get()->toArray();
                $weekly_holiday_check = Carbon::parse($start_date)->format('l');

                if (count($holidays) > 0) {
                    $collection->push((object) [
                        'employee_id' => '--',
                        'employee_name' => '--',
                        'department_name' => '--',
                        'date' => $start_date,
                        'count' => 0,
                        'in_time' => '',
                        'out_time' => '',
                        'status' => 'Holiday',
                    ]);
                } elseif (in_array($weekly_holiday_check, $weekends)) {
                    $collection->push((object) [
                        'employee_id' => '--',
                        'employee_name' => '--',
                        'department_name' => '--',
                        'date' => $start_date,
                        'count' => 0,
                        'in_time' => '',
                        'out_time' => '',
                        'status' => 'Weekend',
                    ]);
                } else {
                    $collection->push((object) [
                        'employee_id' => '--',
                        'employee_name' => '--',
                        'department_name' => '--',
                        'date' => $start_date,
                        'count' => 0,
                        'in_time' => '',
                        'out_time' => '',
                        'status' => 'Absent',
                    ]);
                }
            }
            $start_date = Carbon::parse($start_date)->addDay()->format('Y-m-d');
        }

        return view('humanresource::reports.monthly-report', compact('collection', 'employees', 'start_date', 'end_date'));
    }

    public function employeeReport(EmployeeReportDataTable $dataTable)
    {
        return $dataTable->render('humanresource::reports.employee-report', [
            'employees' => Employee::where('is_active', true)->get(),
            'positions' => Position::where('is_active', true)->get(),
        ]);
    }

    public function adhocAdvanceReport()
    {
        $departmentColumns = DB::connection()->getSchemaBuilder()->getColumnListing('departments');
        $employeeColumns = DB::connection()->getSchemaBuilder()->getColumnListing('employees');

        $excludeColumns = ['id', 'uuid', 'deleted_at', 'created_by', 'updated_by', 'created_at', 'updated_at'];

        $employees = array_diff($employeeColumns, $excludeColumns);
        $departments = array_diff($departmentColumns, $excludeColumns);

        return view('humanresource::reports.adhoc-advance', [
            'departments' => $departments,
            'employees' => $employees,
        ]);
    }

    public function adhocAdvanceReportShow(Request $request)
    {
        $request->validate([
            'field' => 'required|array',
            'operator' => 'required|array',
            'value' => 'required|array',
        ]);

        $field = $request['field'];
        $operator = $request['operator'];
        $value = $request['value'];

        $tables = [];
        foreach ($field as $fieldItem) {
            $table = explode('.', $fieldItem)[0];
            if (!in_array($table, $tables)) {
                $tables[] = $table;
            }
        }
        try {
            $query = DB::table($tables[0]);

            // Add JOIN clauses if there are multiple tables
            for ($i = 1; $i < count($tables); $i++) {
                $joinCondition = function ($join) use ($tables, $i) {
                    $join->on($tables[0] . '.id', '=', $tables[$i] . '.' .  rtrim($tables[0], 's') . '_id');
                };
                $query->join($tables[$i], $joinCondition);
            }

            // Add WHERE clauses
            for ($i = 0; $i < count($field); $i++) {
                $query->where($field[$i], $operator[$i], $value[$i]);
            }
            // Execute the query
            $results = $query->get();


            if ($results->isEmpty()) {
                $html = '<table class="table table-border">';
                $html .= '<thead><tr>';
                $html .= '<th>' . localize('SL') . '</th>';
                $html .= '<th>' . localize('Employee ID') . '</th>';
                $html .= '<th>' . localize('Employee Name') . '</th>';
                $html .= '<th>' . localize('Department') . '</th>';
                $html .= '<th>' . localize('Supervisor') . '</th>';
                $html .= '</tr></thead><tbody>';
                $html .= '<tr>';
                $html .= '<td colspan="4" class="text-center"> No Data Found </td>';
                $html .= '</tr>';
                $html .= '</tbody></table>';
            } else {
                if (count($tables) === 1 && $tables[0] === 'employees') {
                    $html = '<table class="table table-border">';
                    $html .= '<thead><tr>';
                    $html .= '<th>' . localize('SL') . '</th>';
                    $html .= '<th>' . localize('Employee ID') . '</th>';
                    $html .= '<th>' . localize('Employee Name') . '</th>';
                    $html .= '<th>' . localize('Supervisor') . '</th>';
                    $html .= '</tr></thead><tbody>';
                    foreach ($results as $key => $item) {
                        $html .= '<tr>';
                        $html .= '<td>' . ($key + 1) . '</td>';
                        $html .= '<td>' . $item->employee_id . '</td>';
                        $html .= '<td>' . $item->first_name . ' ' . $item->middle_name . ' ' . $item->last_name . '</td>';
                        $html .= '<td>' . ($item->is_supervisor == 0 ? "No" : "Yes") . '</td>';
                        $html .= '</tr>';
                    }
                    $html .= '</tbody></table>';
                } else if (count($tables) === 1 && $tables[0] === 'departments') {
                    $html = '<table class="table table-border">';
                    $html .= '<thead><tr>';
                    $html .= '<th>' . localize('SL') . '</th>';
                    $html .= '<th>' . localize('Department Name') . '</th>';
                    $html .= '</tr></thead><tbody>';
                    $html .= '<tr>';
                    foreach ($results as $key => $item) {
                        $html .= '<td>' . $key + 1 . '</td>';
                        $html .= '<td>' . $item->department_name . '</td>';
                    }
                    $html .= '</tr>';
                    $html .= '</tbody></table>';
                } else if (count($tables) === 2 && in_array($tables, ['employees', 'departments'])) {
                    $html = '<table class="table table-border">';
                    $html .= '<thead><tr>';
                    $html .= '<th>' . localize('SL') . '</th>';
                    $html .= '<th>' . localize('Employee ID') . '</th>';
                    $html .= '<th>' . localize('Employee Name') . '</th>';
                    $html .= '<th>' . localize('Department') . '</th>';
                    $html .= '<th>' . localize('Supervisor') . '</th>';
                    $html .= '</tr></thead><tbody>';
                    $html .= '<tr>';
                    foreach ($results as $key => $item) {
                        $html .= '<td>' . $key + 1 . '</td>';
                        $html .= '<td>' . $item->employee_id . '</td>';
                        $html .= '<td>' . $item->first_name . ' ' . $item->middle_name . ' ' . $item->last_name . '</td>';
                        $html .= '<td>' . $item->department_name . '</td>';
                        $html .= '<td>' . $item->is_supervisor == 0 ? "No" : "Yes" . '</td>';
                    }
                    $html .= '</tr>';
                    $html .= '</tbody></table>';
                }
            }

            return response()->json([
                'status' => 'success',
                'html' => $html,
            ], 200);
        } catch (\Throwable $th) {
            $html = '<table class="table table-border">';
            $html .= '<tbody>';
            $html .= '<tr>';
            $html .= '<td class="text-center"> No Results Found From Your Statement </td>';
            $html .= '</tr>';
            $html .= '</tbody></table>';
            return response()->json([
                'status' => 'error',
                'html' => $html,
            ], 502);
        }
    }

    private function calculateWorkAndWastingTime($logs)
    {
        $totalWorkSeconds = 0;
        $totalWastingSeconds = 0;
        $totalNetworkSeconds = 0;

        // Iterate through the logs
        for ($i = 0; $i < count($logs); $i++) {
            // Skip if the log is an "in" entry
            if ($logs[$i]['status'] == 'in') {
                continue;
            }

            // Calculate work time for the current "out" entry
            $outTime = Carbon::parse($logs[$i]['raw_time']);
            $inTime = Carbon::parse($logs[$i - 1]['raw_time']);
            $netWorkSeconds = $outTime->diffInSeconds($inTime);
            $totalNetworkSeconds += $netWorkSeconds;

            // Check if there's a next "in" entry
            if ($i + 1 < count($logs) && $logs[$i + 1]['status'] == 'in') {
                // Calculate wasting time between the current "out" and next "in" entries
                $nextInTime = Carbon::parse($logs[$i + 1]['raw_time']);
                $wastingSeconds = $nextInTime->diffInSeconds($outTime);
                $totalWastingSeconds += $wastingSeconds;
            }
        }

        // logs first time & last time get
        $startTime = $logs->first()->raw_time;
        $endTime = $logs->last()->raw_time;
        // Calculate work time
        $totalWorkSeconds = Carbon::parse($endTime)->diffInSeconds(Carbon::parse($startTime));
        // Ensure totalNetworkSeconds is non-negative
        $totalNetworkSeconds = max($totalNetworkSeconds, 0);
        // Calculate hours and minutes
        $networkHours = floor($totalNetworkSeconds / 3600);
        $networkMinutes = floor(($totalNetworkSeconds % 3600) / 60);
        // Ensure networkHours is between 0 and 99
        $networkHours = min(max($networkHours, 0), 99);
        $workHours = floor($totalWorkSeconds / 3600);
        $workMinutes = floor(($totalWorkSeconds % 3600) / 60);
        // Ensure workHours is between 0 and 99
        $workHours = min(max($workHours, 0), 99);
        $wastingHours = floor($totalWastingSeconds / 3600);
        $wastingMinutes = floor(($totalWastingSeconds % 3600) / 60);
        // Ensure wastingHours is between 0 and 99
        $wastingHours = min(max($wastingHours, 0), 99);

        return [
            'work' => Carbon::createFromTime($workHours, $workMinutes)->format('H:i:s'),
            'wasting' => Carbon::createFromTime($wastingHours, $wastingMinutes)->format('H:i:s'),
            'network' => Carbon::createFromTime($networkHours, $networkMinutes)->format('H:i:s'),
        ];
    }

    public function npf3SocSecTaxReport()
    {
        return view('humanresource::reports.payroll.npf3-soc-sec-tax-report');
    }

    public function npf3SocSecTaxReportShow(Request $request)
    {
        $month_year = $request->month_year;
        list($year, $month) = explode('-', $month_year);

        $month_name = date("F", strtotime($year . "-" . $month . "-01"));
        $sal_month_year = $month_name . ' ' . $year;

        $tax_data   = $this->get_npf3_soc_sec_tax_data($month_year);
        $setting = app_setting();
        $user_info = Auth::user();

        $uuid = null;
        $salary_info = DB::table('salary_sheet_generates as shg')
            ->select('shg.*')->where('shg.name', $month_year)->whereNull('shg.deleted_at')->first();
        if ($salary_info != null) {
            $uuid = $salary_info->uuid;
        }

        return view(
            'humanresource::reports.payroll.npf3_social_security_tax_data',
            compact(
                'uuid',
                'tax_data',
                'setting',
                'user_info',
                'month',
                'year',
                'sal_month_year',
            )
        );
    }

    public function npf3SocSecTaxPdf($uuid)
    {
        $salary_info = DB::table('salary_sheet_generates as shg')
            ->select('shg.*')->where('shg.uuid', $uuid)->whereNull('shg.deleted_at')->first();

        $month_year = $salary_info->name;
        list($year, $month) = explode('-', $month_year);

        $month_name = date("F", strtotime($year . "-" . $month . "-01"));
        $sal_month_year = $month_name . ' ' . $year;

        $tax_data   = $this->get_npf3_soc_sec_tax_data($month_year);
        $setting = app_setting();
        $user_info = Auth::user();

        $currency = null;
        if ($setting->currency && $setting->currency->symbol) {
            $currency = $setting->currency?->symbol;
        }

        $pdf = Pdf::loadView('humanresource::reports.payroll.npf3_social_security_tax-pdf', [
            'salary_info'    => $salary_info,
            'tax_data'       => $tax_data,
            'setting'        => $setting,
            'user_info'      => $user_info,
            'month'          => $month,
            'year'           => $year,
            'sal_month_year' => $year,
            'currency'       => $currency,
        ]);

        return $pdf->download($user_info->full_name . '_' . $sal_month_year . '_npf3_social_security_tax.pdf');
    }

    public function iicf3Contribution()
    {
        return view('humanresource::reports.payroll.iicf3-contribution-report');
    }

    public function iicf3ContributionShow(Request $request)
    {
        $month_year = $request->month_year;
        list($year, $month) = explode('-', $month_year);

        $month_name = date("F", strtotime($year . "-" . $month . "-01"));
        $sal_month_year = $month_name . ' ' . $year;

        $iicf3_contribution_data   = $this->iicf3_contribution_data($month_year);
        $setting = app_setting();
        $user_info = Auth::user();

        $uuid = null;
        $salary_info = DB::table('salary_sheet_generates as shg')
            ->select('shg.*')->where('shg.name', $month_year)->whereNull('shg.deleted_at')->first();
        if ($salary_info != null) {
            $uuid = $salary_info->uuid;
        }

        return view(
            'humanresource::reports.payroll.iicf3_contribution_data',
            compact(
                'uuid',
                'iicf3_contribution_data',
                'setting',
                'user_info',
                'month',
                'year',
                'sal_month_year',
            )
        );
    }

    public function iicf3ContributionPdf($uuid)
    {
        $salary_info = DB::table('salary_sheet_generates as shg')
            ->select('shg.*')->where('shg.uuid', $uuid)->whereNull('shg.deleted_at')->first();

        $month_year = $salary_info->name;
        list($year, $month) = explode('-', $month_year);

        $month_name = date("F", strtotime($year . "-" . $month . "-01"));
        $sal_month_year = $month_name . ' ' . $year;

        $iicf3_contribution_data   = $this->iicf3_contribution_data($month_year);
        $setting = app_setting();
        $user_info = Auth::user();

        $currency = null;
        if ($setting->currency && $setting->currency->symbol) {
            $currency = $setting->currency?->symbol;
        }

        $pdf = Pdf::loadView('humanresource::reports.payroll.iicf3-contribution-pdf', [
            'salary_info'    => $salary_info,
            'iicf3_contribution_data' => $iicf3_contribution_data,
            'setting'        => $setting,
            'user_info'      => $user_info,
            'month'          => $month,
            'year'           => $year,
            'sal_month_year' => $year,
            'currency'       => $currency,
        ]);

        return $pdf->download($user_info->full_name . '_' . $sal_month_year . '_iicf3_contribution.pdf');
    }

    public function socialSecurityNpfIcfReport()
    {
        return view('humanresource::reports.payroll.social-security-npf-icf');
    }

    public function socialSecurityNpfIcfShow(Request $request)
    {
        $month_year = $request->month_year;
        list($year, $month) = explode('-', $month_year);

        $month_name = date("F", strtotime($year . "-" . $month . "-01"));
        $sal_month_year = $month_name . ' ' . $year;

        $soc_sec_npf_icf_data   = $this->social_security_npf_icf_data($month_year);
        $setting = app_setting();
        $user_info = Auth::user();

        $uuid = null;
        $salary_info = DB::table('salary_sheet_generates as shg')
            ->select('shg.*')->where('shg.name', $month_year)->whereNull('shg.deleted_at')->first();
        if ($salary_info != null) {
            $uuid = $salary_info->uuid;
        }

        return view(
            'humanresource::reports.payroll.social-security-npf-icf-data',
            compact(
                'uuid',
                'soc_sec_npf_icf_data',
                'setting',
                'user_info',
                'month',
                'year',
                'sal_month_year',
            )
        );
    }

    public function socialSecurityNpfIcfPdf($uuid)
    {
        $salary_info = DB::table('salary_sheet_generates as shg')
            ->select('shg.*')->where('shg.uuid', $uuid)->whereNull('shg.deleted_at')->first();

        $month_year = $salary_info->name;
        list($year, $month) = explode('-', $month_year);

        $month_name = date("F", strtotime($year . "-" . $month . "-01"));
        $sal_month_year = $month_name . ' ' . $year;

        $soc_sec_npf_icf_data   = $this->social_security_npf_icf_data($month_year);
        $setting = app_setting();
        $user_info = Auth::user();

        $currency = null;
        if ($setting->currency && $setting->currency->symbol) {
            $currency = $setting->currency?->symbol;
        }

        $pdf = Pdf::loadView('humanresource::reports.payroll.social-security-npf-icf-pdf', [
            'salary_info'    => $salary_info,
            'soc_sec_npf_icf_data' => $soc_sec_npf_icf_data,
            'setting'        => $setting,
            'user_info'      => $user_info,
            'month'          => $month,
            'year'           => $year,
            'sal_month_year' => $sal_month_year,
            'currency'       => $currency,
        ]);

        return $pdf->download($user_info->full_name . '_' . $sal_month_year . '_social_security_npf_icf.pdf');
    }

    public function graRet5ReportReport()
    {
        return view('humanresource::reports.payroll.gra-ret-5-report');
    }

    public function graRet5ReportReportShow(Request $request)
    {
        $month_year = $request->month_year;
        list($year, $month) = explode('-', $month_year);

        $month_name = date("F", strtotime($year . "-" . $month . "-01"));
        $sal_month_year = $month_name . ' ' . $year;

        $gra_ret_5_data           = $this->gra_ret_5_report_data();
        $gra_ret_5_report_monthly = $this->gra_ret_5_report_monthly($month_year);

        $setting = app_setting();
        $user_info = Auth::user();

        $uuid = null;
        $salary_info = DB::table('salary_sheet_generates as shg')
            ->select('shg.*')->where('shg.name', $month_year)->whereNull('shg.deleted_at')->first();
        if ($salary_info != null) {
            $uuid = $salary_info->uuid;
        }

        return view(
            'humanresource::reports.payroll.gra-ret-5-data',
            compact(
                'uuid',
                'gra_ret_5_data',
                'gra_ret_5_report_monthly',
                'setting',
                'user_info',
                'month',
                'year',
                'sal_month_year',
            )
        );
    }

    public function graRet5ReportReportPdf($uuid)
    {
        $salary_info = DB::table('salary_sheet_generates as shg')
            ->select('shg.*')->where('shg.uuid', $uuid)->whereNull('shg.deleted_at')->first();

        $month_year = $salary_info->name;
        list($year, $month) = explode('-', $month_year);

        $month_name = date("F", strtotime($year . "-" . $month . "-01"));
        $sal_month_year = $month_name . ' ' . $year;

        $gra_ret_5_data           = $this->gra_ret_5_report_data();
        $gra_ret_5_report_monthly = $this->gra_ret_5_report_monthly($month_year);

        $setting = app_setting();
        $user_info = Auth::user();

        $currency = null;
        if ($setting->currency && $setting->currency->symbol) {
            $currency = $setting->currency?->symbol;
        }

        $pdf = Pdf::loadView('humanresource::reports.payroll.gra-ret-5-pdf', [
            'salary_info'    => $salary_info,
            'gra_ret_5_data'           => $gra_ret_5_data,
            'gra_ret_5_report_monthly' => $gra_ret_5_report_monthly,
            'setting'        => $setting,
            'user_info'      => $user_info,
            'month'          => $month,
            'year'           => $year,
            'sal_month_year' => $sal_month_year,
            'currency'       => $currency,
        ]);

        return $pdf->download($user_info->full_name . '_' . $sal_month_year . '_gra_ret_5.pdf');
    }

    public function sateIncomeTaxReport()
    {
        return view('humanresource::reports.payroll.sate-income-tax');
    }

    public function sateIncomeTaxReportShow(Request $request)
    {
        $month_year = $request->month_year;
        list($year, $month) = explode('-', $month_year);

        $month_name = date("F", strtotime($year . "-" . $month . "-01"));
        $sal_month_year = $month_name . ' ' . $year;

        $sate_incom_tax_data = $this->sate_income_tax_schedule_data($month_year);

        $setting = app_setting();
        $user_info = Auth::user();

        $uuid = null;
        $salary_info = DB::table('salary_sheet_generates as shg')
            ->select('shg.*')->where('shg.name', $month_year)->whereNull('shg.deleted_at')->first();
        if ($salary_info != null) {
            $uuid = $salary_info->uuid;
        }

        return view(
            'humanresource::reports.payroll.sate-income-tax-data',
            compact(
                'uuid',
                'sate_incom_tax_data',
                'setting',
                'user_info',
                'month',
                'year',
                'sal_month_year',
            )
        );
    }

    public function sateIncomeTaxReportPdf($uuid)
    {
        $salary_info = DB::table('salary_sheet_generates as shg')
            ->select('shg.*')->where('shg.uuid', $uuid)->whereNull('shg.deleted_at')->first();

        $month_year = $salary_info->name;
        list($year, $month) = explode('-', $month_year);

        $month_name = date("F", strtotime($year . "-" . $month . "-01"));
        $sal_month_year = $month_name . ' ' . $year;

        $sate_incom_tax_data = $this->sate_income_tax_schedule_data($month_year);

        $setting = app_setting();
        $user_info = Auth::user();

        $currency = null;
        if ($setting->currency && $setting->currency->symbol) {
            $currency = $setting->currency?->symbol;
        }

        $pdf = Pdf::loadView('humanresource::reports.payroll.sate-income-tax-pdf', [
            'salary_info'    => $salary_info,
            'sate_incom_tax_data' => $sate_incom_tax_data,
            'setting'        => $setting,
            'user_info'      => $user_info,
            'month'          => $month,
            'year'           => $year,
            'sal_month_year' => $sal_month_year,
            'currency'       => $currency,
        ]);

        return $pdf->download($user_info->full_name . '_' . $sal_month_year . '_sate_income_tax.pdf');
    }

    public function salaryConfirmationForm()
    {
        return view('humanresource::reports.payroll.salary-confirmation-form');
    }

    public function salaryConfirmationFormShow(Request $request)
    {
        $month_year = $request->month_year;
        list($year, $month) = explode('-', $month_year);

        $month_name = date("F", strtotime($year . "-" . $month . "-01"));
        $sal_month_year = $month_name . ' ' . $year;

        $salary_confirmation_emp_list = $this->salary_confirmation_emp_list($month_year);

        $setting = app_setting();
        $user_info = Auth::user();

        $uuid = null;
        $salary_info = DB::table('salary_sheet_generates as shg')
            ->select('shg.*')->where('shg.name', $month_year)->whereNull('shg.deleted_at')->first();
        if ($salary_info != null) {
            $uuid = $salary_info->uuid;
        }

        return view(
            'humanresource::reports.payroll.salary-confirmation-form-data',
            compact(
                'uuid',
                'salary_confirmation_emp_list',
                'setting',
                'user_info',
                'month',
                'year',
                'sal_month_year',
            )
        );
    }

    public function salaryConfirmationFormPdf($uuid)
    {
        $salary_info = DB::table('salary_sheet_generates as shg')
            ->select('shg.*')->where('shg.uuid', $uuid)->whereNull('shg.deleted_at')->first();

        $month_year = $salary_info->name;
        list($year, $month) = explode('-', $month_year);

        $month_name = date("F", strtotime($year . "-" . $month . "-01"));
        $sal_month_year = $month_name . ' ' . $year;

        $salary_confirmation_emp_list = $this->salary_confirmation_emp_list($month_year);

        $setting = app_setting();
        $user_info = Auth::user();

        $currency = null;
        if ($setting->currency && $setting->currency->symbol) {
            $currency = $setting->currency?->symbol;
        }

        $pdf = Pdf::loadView('humanresource::reports.payroll.salary-confirmation-form-pdf', [
            'salary_info'    => $salary_info,
            'salary_confirmation_emp_list' => $salary_confirmation_emp_list,
            'setting'        => $setting,
            'user_info'      => $user_info,
            'month'          => $month,
            'year'           => $year,
            'sal_month_year' => $sal_month_year,
            'currency'       => $currency,
        ]);

        return $pdf->download($user_info->full_name . '_' . $sal_month_year . '_salary_confirmation_form.pdf');
    }

    public function salary_confirmation_emp_list($sal_month_year)
    {
        return DB::table('salary_generates as gsg')
            ->select('gsg.*', 'emp.first_name', 'emp.last_name')
            ->leftJoin('employees as emp', 'gsg.employee_id', '=', 'emp.id')
            ->where('gsg.salary_month_year', $sal_month_year)
            ->whereNull('gsg.deleted_at') // Check if deleted_at is NULL
            ->get();
    }

    public function sate_income_tax_schedule_data($sal_month_year)
    {
        return DB::table('salary_generates as gsg')
            ->select('gsg.*', 'emp.first_name', 'emp.last_name', 'emp.sos as social_security_no')
            ->leftJoin('employees as emp', 'gsg.employee_id', '=', 'emp.id')
            ->where('gsg.salary_month_year', $sal_month_year)
            ->where('gsg.income_tax', '>', 0)
            ->whereNull('gsg.deleted_at') // Check if deleted_at is NULL
            ->get();
    }

    public function gra_ret_5_report_monthly($sal_month_year)
    {
        $resp = DB::table('salary_generates as gsg')
            ->select('gsg.employee_id', 'gsg.income_tax', 'gsg.gross_salary', 'gsg.salary_month_year', 'empf.tin_no')
            ->leftJoin('employee_files as empf', 'gsg.employee_id', '=', 'empf.employee_id')
            ->where('gsg.salary_month_year', $sal_month_year)
            ->whereNull('gsg.deleted_at') // Check if deleted_at is NULL
            ->get();

        $resp_arr = array();

        foreach ($resp as $key => $row) {

            $temp_arr = array();
            $temp_arr['income_tax']     = $row->income_tax ? $row->income_tax : 0;
            $temp_arr['gross_salary']   = $row->gross_salary ? $row->gross_salary : 0;
            $temp_arr['sal_month_year'] = $row->salary_month_year ? $row->salary_month_year : 0;
            $temp_arr['tin_no']         = $row->tin_no ? $row->tin_no : '';

            $resp_arr[$row->employee_id] = $temp_arr;
        }

        return $resp_arr;
    }

    public function gra_ret_5_report_data()
    {
        return DB::table('salary_generates as gsg')
            ->select(
                'gsg.employee_id',
                'emp.first_name',
                'emp.last_name',
                'empf.tin_no',
                DB::raw('SUM(COALESCE(gsg.gross_salary, 0)) as cumilative_gross_salary'),
                DB::raw('SUM(COALESCE(gsg.income_tax, 0)) as cumilative_income_tax')
            )
            ->leftJoin('employees as emp', 'gsg.employee_id', '=', 'emp.id')
            ->leftJoin('employee_files as empf', 'gsg.employee_id', '=', 'empf.employee_id')
            ->whereNull('gsg.deleted_at') // Check if deleted_at is NULL
            ->groupBy('gsg.employee_id')
            ->get();
    }

    public function social_security_npf_icf_data($sal_month_year)
    {
        return DB::table('salary_generates as gsg')
            ->select('gsg.*', 'emp.first_name', 'emp.last_name', 'emp.sos as social_security_no')
            ->leftJoin('employees as emp', 'gsg.employee_id', '=', 'emp.id')
            ->where('gsg.salary_month_year', $sal_month_year)
            ->where('gsg.soc_sec_npf_tax', '>', 0)
            ->whereNull('gsg.deleted_at') // Check if deleted_at is NULL
            ->get();
    }

    public function iicf3_contribution_data($sal_month_year)
    {
        return DB::table('salary_generates as gsg')
            ->select('gsg.*', 'emp.first_name', 'emp.last_name', 'emp.sos as social_security_no')
            ->leftJoin('employees as emp', 'gsg.employee_id', '=', 'emp.id')
            ->where('gsg.salary_month_year', $sal_month_year)
            ->where('gsg.icf_amount', '>', 0)
            ->whereNull('gsg.deleted_at') // Check if deleted_at is NULL
            ->get();
    }

    public function get_npf3_soc_sec_tax_data($sal_month_year)
    {
        return DB::table('salary_generates as gsg')
            ->select('gsg.*', 'emp.first_name', 'emp.last_name', 'emp.sos as social_security_no')
            ->leftJoin('employees as emp', 'gsg.employee_id', '=', 'emp.id')
            ->where('gsg.salary_month_year', $sal_month_year)
            ->where('gsg.soc_sec_npf_tax', '>', 0)
            ->whereNull('gsg.deleted_at') // Check if deleted_at is NULL
            ->get();
    }

    private function formatTime($totalSeconds)
    {
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}
