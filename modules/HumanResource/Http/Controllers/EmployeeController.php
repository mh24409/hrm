<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Modules\Accounts\Entities\AccSubcode;
use Modules\HumanResource\DataTables\EmployeeDataTable;
use Modules\HumanResource\DataTables\InactiveEmployeeDataTable;
use Modules\HumanResource\Entities\BankInfo;
use Modules\HumanResource\Entities\CertificateType;
use Modules\HumanResource\Entities\Department;
use Modules\HumanResource\Entities\DutyType;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\EmployeeAcademicInfo;
use Modules\HumanResource\Entities\EmployeeDocs;
use Modules\HumanResource\Entities\EmployeeFile;
use Modules\HumanResource\Entities\EmployeeType;
use Modules\HumanResource\Entities\Gender;
use Modules\HumanResource\Entities\MaritalStatus;
use Modules\HumanResource\Entities\PayFrequency;
use Modules\HumanResource\Entities\Position;
use Modules\HumanResource\Entities\SetupRule;
use Modules\HumanResource\Entities\SkillType;
use Modules\HumanResource\Http\Requests\CertificateTypeRequest;
use Modules\HumanResource\Http\Requests\EmployeeCreateRequest;
use Modules\HumanResource\Http\Requests\EmployeeUpdateRequest;
use Modules\HumanResource\Http\Requests\SkillTypeRequest;
use Modules\Setting\Entities\Country;
use PDF;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:read_employee'])->only('index', 'inactive_list');
        $this->middleware(['permission:create_employee'])->only('create');
        $this->middleware(['permission:update_employee'])->only('edit');
        $this->middleware(['permission:delete_employee'])->only('destroy');
        $this->middleware(['permission:update_employee_status'])->only('statusChange');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(EmployeeDataTable $dataTables)
    {
        $employees = Employee::all();
        $genders = Gender::all();
        $marital_statuses = MaritalStatus::all();
        $positions = Position::where('is_active', true)->get();
        $departments = Department::whereNull('parent_id')->where('is_active', true)->get();
        $sub_departments = Department::whereNotNull('parent_id')->where('is_active', true)->get();
        $countries = Country::all();
        $employeeTypes = EmployeeType::where('is_active', true)->get();

        return $dataTables->render('humanresource::employee.index', compact('employees', 'genders', 'marital_statuses', 'positions', 'departments', 'sub_departments', 'countries', 'employeeTypes'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function inactive_list(InactiveEmployeeDataTable $dataTables)
    {
        $employees = Employee::where('is_active', false)->get();
        $genders = Gender::all();
        $marital_statuses = MaritalStatus::all();
        $positions = Position::where('is_active', true)->get();
        $departments = Department::whereNull('parent_id')->where('is_active', true)->get();
        $sub_departments = Department::whereNotNull('parent_id')->where('is_active', true)->get();
        $countries = Country::all();
        $employeeTypes = EmployeeType::where('is_active', true)->get();

        return $dataTables->render('humanresource::employee.inactive_list', compact('employees', 'genders', 'marital_statuses', 'positions', 'departments', 'sub_departments', 'countries', 'employeeTypes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $positions = Position::where('is_active', true)->get();
        $parent_departments = Department::whereNull('parent_id')->where('is_active', true)->get();
        $sub_departments = Department::whereNotNull('parent_id')->where('is_active', true)->get();
        $pay_frequencies = PayFrequency::where('is_active', true)->get();
        $duty_types = DutyType::where('is_active', 1)->get();
        $setup_rules = SetupRule::where('is_active', true)->get();
        $basic_setup_rule = SetupRule::where('is_active', true)->where('type', 'basic')->first();
        $employees = Employee::where('is_active', true)->get();

        $times = $setup_rules->filter(function ($value) {
            return $value->type == 'time';
        });

        $deductions = $setup_rules->filter(function ($value) {
            return $value->type == 'deduction';
        });

        $allowances = $setup_rules->filter(function ($value) {
            return $value->type == 'allowance';
        });

        return view('humanresource::employee.create', [
            'positions' => $positions,
            'departments' => $parent_departments,
            'sub_departments' => $sub_departments,
            'countries' => Country::all(),
            'setup_rules' => $setup_rules,
            'times' => $times,
            'deductions' => $deductions,
            'allowances' => $allowances,
            'basic_setup_rule' => $basic_setup_rule,
            'pay_frequencies' => $pay_frequencies,
            'employee_types' => EmployeeType::where('is_active', true)->get(),
            'duty_types' => $duty_types,
            'genders' => Gender::all(),
            'marital_statuses' => MaritalStatus::all(),
            'certificate_types' => CertificateType::all(),
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(EmployeeCreateRequest $request)
    {
        DB::beginTransaction();
        try {
            // create new user for this employee
            $user = new User();
            $user->user_type_id = 2;
            $user->user_name = strtolower($request->first_name . ' ' . $request->last_name);
            $user->full_name = strtolower($request->first_name . ' ' . $request->last_name);
            $user->email = $request->email;
            $user->contact_no = $request->phone;
            $user->password = Hash::make($request->password);
            $user->is_active = true;
            $user->save();
            $user->assignRole(2);

            $employee = new Employee();
            $employee->fill($request->all());
            $employee->user_id = $user->id;

            if ($request->hasFile('profile_image')) {
                $request_file = $request->file('profile_image');
                $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
                $path = $request_file->storeAs('employee', $filename, 'public');
                $employee->profile_img_name = $filename;
                $employee->profile_img_location = $path;
            }

            $employee->save();

            $bankInfo = [
                'employee_id' => $employee->id,
                'acc_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'bban_num' => $request->bban_num,
                'branch_address' => $request->branch_address,
            ];

            $employeeFile = [
                'employee_id' => $employee->id,
                'tin_no' => $request->tin_no,
                'basic' => $request->basic_salary,
                'gross_salary' => $request->gross_salary,
                'transport' => $request->transport_allowance,
                'medical_benefit' => $request->medical_benefit,
                'other_benefit' => $request->other_benefit,
                'family_benefit' => $request->family_benefit,
                'transportation_benefit' => $request->transportation_benefit,
            ];

            BankInfo::create($bankInfo);
            EmployeeFile::create($employeeFile);

            if ($request->employee_docs) {
                foreach ($request->employee_docs as $key => $employee_document) {

                    $employee_doc = new EmployeeDocs();
                    $employee_doc->employee_id = $employee->id;

                    if (isset($employee_document['file']) && !empty($employee_document['file'])) {
                        $request_file = $employee_document['file'];
                        $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
                        $path = $request_file->storeAs('employee/docs', $filename, 'public');
                        $employee_doc->file_name = $filename;
                        $employee_doc->file_path = $path;
                    }

                    $employee_doc->doc_title = @$employee_document['document_title'];
                    $employee_doc->expiry_date = @$employee_document['expiry_date'];
                    $employee_doc->save();
                }
            }

            DB::commit();

            Toastr::success('Employee Added Successfully..!!', 'Success');
            return redirect()->route('employees.index');
        } catch (\Exception $e) {
            DB::rollback();
            activity()
                ->causedBy(auth()->user())
                ->log('An error occurred: ' . $e->getMessage());
            Toastr::error('Something went wrong :)', 'Errors');
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $employee      = Employee::where('id', $id)->firstOrFail();
        $bank_info     = BankInfo::where('employee_id', $employee->id)->first();
        $employee_file = EmployeeFile::where('employee_id', $employee->id)->first();

        $rules = SetupRule::where('is_active', true)->with('employee_salary_types', function ($q) use ($employee) {
            return $q->where('employee_id', $employee->id);
        })->get();

        $basic = $rules->filter(function ($value) {
            return $value->type == 'basic';
        })->first();

        $deductions = $rules->filter(function ($value) {
            return $value->type == 'deduction';
        });

        $allowances = $rules->filter(function ($value) {
            return $value->type == 'allowance';
        });

        $bonuses = $rules->filter(function ($value) {
            return $value->type == 'bonus';
        });

        return view('humanresource::employee.show', [
            'employee'      => $employee,
            'bank_info'     => $bank_info,
            'employee_file' => $employee_file,
            'basic'         => $basic,
            'deductions'    => $deductions,
            'allowances'    => $allowances,
            'bonuses'       => $bonuses,
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function profilePictureUpdate(Request $request, $id)
    {
        $employee = Employee::where('id', $id)->firstOrFail();

        if ($request->hasFile('profile_image')) {

            $destination = public_path('storage/' . $employee->profile_img_location ?? null);

            if ($employee->profile_img_location != null && file_exists($destination)) {
                unlink($destination);
            }

            $request_file = $request->file('profile_image');
            $name = time() . '.' . $request_file->getClientOriginalExtension();
            $path = Storage::disk('public')->putFileAs('employee', $request_file, $name);
            Image::make($request_file)->resize(150, 150)->save(public_path('storage/' . $path));
            $employee->profile_image = $name;
            $employee->profile_img_location = $path;
        }

        $employee->update();
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $employee = Employee::with(['employee_salary_types'])->where('id', $id)->firstOrFail();
        $bank_info = BankInfo::where('employee_id', $employee->id)->first();
        $employee_file = EmployeeFile::where('employee_id', $employee->id)->first();

        $positions = Position::where('is_active', true)->get();
        $parent_departments = Department::whereNull('parent_id')->where('is_active', true)->get();
        $sub_departments = Department::whereNotNull('parent_id')->where('is_active', true)->get();
        $basic_setup_rule = SetupRule::where('is_active', true)->where('type', 'basic')->first();
        $setup_rules = SetupRule::where('is_active', true)->get();

        $basic_salary = $employee->employee_salary_types->where('type', 'basic')->first();
        $allowances = $employee->employee_salary_types->where('type', 'allowance');
        $deductions = $employee->employee_salary_types->where('type', 'deduction');
        $bonuses = $employee->employee_salary_types->where('type', 'bonus');

        $times = $setup_rules->filter(function ($value) {
            return $value->type == 'time';
        });

        $pay_frequencies = PayFrequency::where('is_active', true)->get();
        $employee_types = EmployeeType::where('is_active', true)->get();
        $duty_types = DutyType::where('is_active', true)->get();
        $genders = Gender::all();

        return view('humanresource::employee.edit', [
            'employee' => $employee,
            'bank_info' => $bank_info,
            'employee_file' => $employee_file,
            'positions' => $positions,
            'departments' => $parent_departments,
            'sub_departments' => $sub_departments,
            'countries' => Country::all(),
            'times' => $times,
            'pay_frequencies' => $pay_frequencies,
            'employee_types' => $employee_types,
            'duty_types' => $duty_types,
            'genders' => $genders,
            'marital_statuses' => MaritalStatus::all(),
            'setup_rules' => $setup_rules,
            'basic_setup_rule' => $basic_setup_rule,
            'basic_salary' => $basic_salary,
            'allowances' => $allowances,
            'deductions' => $deductions,
            'bonuses' => $bonuses,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(EmployeeUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            // Find the employee by ID
            $employee = Employee::with(['user'])->where('uuid', $id)->first();

            $employee->fill($request->all());

            $employee->sub_department_id = $request->input('sub_department') ?? null;

            if ($request->hasFile('profile_image')) {
                if ($employee->profile_img_location) {
                    Storage::disk('public')->delete($employee->profile_img_location);
                }
                $request_file = $request->file('profile_image');
                $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
                $path = $request_file->storeAs('employee', $filename, 'public');
                $employee->profile_img_name = $filename;
                $employee->profile_img_location = $path;
            }

            $employee->save();

            // Update user information
            $user = $employee->user;
            $user->user_name = strtolower($request->first_name . ' ' . $request->last_name);
            $user->full_name = strtolower($request->first_name . ' ' . $request->last_name);
            $user->email = $request->email;
            $user->contact_no = $request->phone;
            $user->save();

            if ($request->hasFile('profile_image')) {
                $destination = public_path('storage/employee/' . $employee->profile_image ?? null);

                if ($employee->profile_image != null && file_exists($destination)) {
                    unlink($destination);
                }

                $request_file = $request->file('profile_image');
                $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
                $path = $request_file->storeAs('employee', $filename, 'public');
                $employee->profile_img_name = $filename;
                $employee->profile_img_location = $path;
            }

            $bankInfo = [
                'employee_id' => $employee->id,
                'acc_number' => $request->account_number,
                'bank_name' => $request->bank_name,
                'route_number' => $request->route_number,
                'branch_name' => $request->branch_name,
                'bban_num' => $request->bban_num,
                'branch_address' => $request->branch_address,
            ];

            $employeeFile = [
                'tin_no' => $request->tin_no,
                'gross_salary' => $request->gross_salary,
                'basic' => $request->basic_salary,
                'transport' => $request->transport_allowance,
                'medical_benefit' => $request->medical_benefit,
                'other_benefit' => $request->other_benefit,
                'family_benefit' => $request->family_benefit,
                'transportation_benefit' => $request->transportation_benefit,
            ];

            $bank_info = BankInfo::where('employee_id', $employee->id)->first();
            $bank_info->update($bankInfo);

            $employee_file = EmployeeFile::firstOrCreate(['employee_id' => $employee->id]);
            $employee_file->update($employeeFile);

            if ($request->employee_docs) {
                foreach ($request->employee_docs as $key => $employee_document) {

                    $employee_doc = isset($employee_document['doc_id']) ? EmployeeDocs::find($employee_document['doc_id']) : new EmployeeDocs();

                    $employee_doc->employee_id = $employee->id;

                    if (isset($employee_document['file']) && !empty($employee_document['file'])) {
                        $request_file = $employee_document['file'];
                        $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
                        $path = $request_file->storeAs('employee/docs', $filename, 'public');
                        $employee_doc->file_name = $filename;
                        $employee_doc->file_path = $path;
                    }

                    $employee_doc->doc_title = @$employee_document['document_title'];
                    $employee_doc->expiry_date = @$employee_document['expiry_date'];
                    $employee_doc->save();
                }
            }

            DB::commit();

            Toastr::success('Employee Updated successfully :)', 'Success');
            return redirect()->route('employees.index');
        } catch (\Exception $e) {
            DB::rollback();
            activity()
                ->causedBy(auth()->user()) // The user causing the activity
                ->log('An error occurred: ' . $e->getMessage());
            Toastr::error('Something went wrong :)', 'Errors');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function active($id)
    {
        $employee = Employee::where('id', $id)->first();

        if ($employee->is_active == false) {
            $employee->is_active = true;
            $employee->save();
            Toastr::success('Successfully Active :)', 'Success');
        } else {
            Toastr::info('Employee is already Active');
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function statusChange($id)
    {
        $employee = Employee::where('id', $id)->first();

        if ($employee->is_active == true) {
            $employee->is_active = false;
            $employee->save();
        } else {
            $employee->is_active = true;
            $employee->save();
        }

        Toastr::success('Status Successfully Changed :)', 'Success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::with(['user', 'subCode' => function ($query) {
                $query->where('acc_subtype_id', 1);
            }])->where('id', $id)->first();

            $employee->user->delete();
            $employee->delete();

            DB::commit();
            return response()->json(['success' => 'success']);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back();
        }
    }

    /**
     * Download employee PDF.
     * @param int $id
     * @return Renderable
     */
    public function download($id)
    {
        $employee = Employee::where('id', $id)->firstOrFail();
        $bank_info = BankInfo::where('employee_id', $employee->id)->first();
        $employee_file = EmployeeFile::where('employee_id', $employee->id)->first();
        $academicInfos = EmployeeAcademicInfo::where('employee_id', $employee->id)->get();

        $rules = SetupRule::where('is_active', true)->with('employee_salary_types', function ($q) use ($employee) {
            return $q->where('employee_id', $employee->id);
        })->get();

        $times = $rules->filter(function ($value) {
            return $value->type == 'time';
        });

        $deductions = $rules->filter(function ($value) use ($employee) {
            return $value->type == 'deduction';
        });

        $allowances = $rules->filter(function ($value) {
            return $value->type == 'allowance';
        });

        $pdf = PDF::loadView('humanresource::employee.employee_pdf', compact('employee', 'bank_info', 'employee_file', 'times', 'deductions', 'allowances', 'academicInfos'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('employee-info.pdf');
    }

    /**
     * Store Employee Skill Type.
     * @param int $id
     * @return Renderable
     */
    public function skillTypeStore(SkillTypeRequest $request)
    {

        $skillType = new SkillType();
        $skillType->fill($request->all());
        $skillType->save();
        return response()->json(['success' => 'Skill Type Add Successfully Done..!!']);
    }

    /**
     * Get Employee Skill Type.
     * @param int $id
     * @return Renderable
     */
    public function getSkillType(Request $request)
    {

        $skillType = SkillType::get();
        return response()->json($skillType);
    }

    /**
     * Get Employee Skill Type.
     * @param int $id
     * @return Renderable
     */
    public function deleteSkillType(Request $request, $id)
    {
        SkillType::findOrFail($id)->delete();
        return response()->json(['success' => 'Skill Type Delete Successfully Done..!!']);
    }

    /**
     * Store Employee Skill Type.
     * @param int $id
     * @return Renderable
     */
    public function certificateTypeStore(CertificateTypeRequest $request)
    {

        $certificateType = new CertificateType();
        $certificateType->fill($request->all());
        $certificateType->save();
        return response()->json(['success' => 'Certificate Type Add Successfully Done..!!']);
    }

    /**
     * Get Employee certificate Type.
     * @param int $id
     * @return Renderable
     */
    public function getCertificateType(Request $request)
    {

        $certificateType = CertificateType::get();
        return response()->json($certificateType);
    }

    /**
     * Get Employee certificate Type.
     * @param int $id
     * @return Renderable
     */
    public function deleteCertificateType(Request $request, $id)
    {
        CertificateType::findOrFail($id)->delete();
        return response()->json(['success' => 'Certificate Type Delete Successfully Done..!!']);
    }

    public function getEmployeeByID($id)
    {
        $employee = Employee::with('employee_files')->find($id);
        return response()->json($employee);
    }

    /**
     * Employee Information.
     * @return Renderable
     */
    public function employeeInfo(Request $request)
    {
        $data = AccSubcode::with('employee')->where('acc_subtype_id', 1)->where('id', $request->id)->first();
        if ($data) {
            return ['info' => $data];
        }
    }

    protected function setup_rules(array $allowances, array $deductions, array $bonuses, float $basic_salary, float $gross_salary, int $employee_id, int $basic_setup_rule_id)
    {
        $rules = [];

        if ($basic_salary) {
            $rules[] = [
                'employee_id' => $employee_id,
                'setup_rule_id' => $basic_setup_rule_id,
                'amount' => $basic_salary,
                'percentage' => null,
                'on_basic' => 0,
                'on_gross' => 0,
                'is_active' => 1,
                'type' => 'basic',
            ];
        }

        if ($allowances) {
            foreach ($allowances['setup_rule_id'] as $key => $value) {
                if ($value == 0 || $value == '' || $value == null || empty($value)) {
                    // escape
                    continue;
                }
                if ($allowances['is_percent'][$key] == 1) {
                    if ($allowances['effect_on'] == 'on_basic') {
                        $amount = ($basic_salary * $allowances['value'][$key]) / 100;
                    } else {
                        $amount = ($basic_salary * $allowances['value'][$key]) / 100;
                    }
                } else {
                    $amount = $allowances['amount'][$key];
                }
                $rules[] = [
                    'employee_id' => $employee_id,
                    'setup_rule_id' => $value,
                    'amount' => $amount,
                    'percentage' => $allowances['is_percent'][$key] == 1 ? $allowances['value'][$key] : null,
                    'on_basic' => $allowances['effect_on'] == 'on_basic' ? 1 : 0,
                    'on_gross' => $allowances['effect_on'] == 'on_gross' ? 1 : 0,
                    'is_active' => 1,
                    'type' => 'allowance',
                ];
            }
        }

        if ($deductions) {
            foreach ($deductions['setup_rule_id'] as $key => $value) {
                if ($value == 0 || $value == '' || $value == null || empty($value)) {
                    // escape
                    continue;
                }
                if ($deductions['is_percent'][$key] == 1) {
                    if ($deductions['effect_on'] == 'on_basic') {
                        $amount = ($basic_salary * $deductions['value'][$key]) / 100;
                    } else {
                        $amount = ($basic_salary * $deductions['value'][$key]) / 100;
                    }
                } else {
                    $amount = $deductions['amount'][$key];
                }
                $rules[] = [
                    'employee_id' => $employee_id,
                    'setup_rule_id' => $value,
                    'amount' => $amount,
                    'percentage' => $deductions['is_percent'][$key] == 1 ? $deductions['value'][$key] : null,
                    'on_basic' => $deductions['effect_on'] == 'on_basic' ? 1 : 0,
                    'on_gross' => $deductions['effect_on'] == 'on_gross' ? 1 : 0,
                    'is_active' => 1,
                    'type' => 'deduction',
                ];
            }
        }

        if ($bonuses) {
            foreach ($bonuses['setup_rule_id'] as $key => $value) {
                if ($value == 0 || $value == '' || $value == null || empty($value)) {
                    // escape
                    continue;
                }
                if ($bonuses['is_percent'][$key] == 1) {
                    if ($bonuses['effect_on'] == 'on_basic') {
                        $amount = ($basic_salary * $bonuses['value'][$key]) / 100;
                    } else {
                        $amount = ($basic_salary * $bonuses['value'][$key]) / 100;
                    }
                } else {
                    $amount = $bonuses['amount'][$key];
                }
                $rules[] = [
                    'employee_id' => $employee_id,
                    'setup_rule_id' => $value,
                    'amount' => $amount,
                    'percentage' => $bonuses['is_percent'][$key] == 1 ? $bonuses['value'][$key] : null,
                    'on_basic' => $bonuses['effect_on'] == 'on_basic' ? 1 : 0,
                    'on_gross' => $bonuses['effect_on'] == 'on_gross' ? 1 : 0,
                    'is_active' => 1,
                    'type' => 'bonus',
                ];
            }
        }

        return $rules;
    }
}
