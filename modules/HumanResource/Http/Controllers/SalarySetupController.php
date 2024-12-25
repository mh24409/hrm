<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Modules\HumanResource\DataTables\SalarySetupDataTable;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\SetupRule;
use Modules\HumanResource\Entities\EmployeeSalaryType;

class SalarySetupController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_salary_setup', ['only' => ['index']]);
        $this->middleware('permission:create_salary_setup', ['only' => ['create', 'store']]);
        $this->middleware('permission:update_salary_setup', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_salary_setup', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(SalarySetupDataTable $dataTable)
    {
        return $dataTable->render('humanresource::salary-setup.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $rules = SetupRule::where('is_active', true)->get();

        $basic = $rules->filter(function ($value) {
            return $value->type == 'basic';
        })->first();

        $deductions = $rules->filter(function ($value) {
            return $value->type == 'deduction';
        });
        $allowances = $rules->filter(function ($value) {
            return $value->type == 'allowance';
        });

        $employees = Employee::where('is_active', true)->where('is_left', false)->get();

        return view('humanresource::salary-setup.create', [
            'basic' => $basic,
            'deductions' => $deductions,
            'allowances' => $allowances,
            'employees' => $employees,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
        ]);

        foreach($request->allowances as $key => $allowance){

            $setup_rule = SetupRule::find($key);
            $salary_setup = EmployeeSalaryType::where('employee_id', $request->employee_id)->where('setup_rule_id',$key)->first();

            if($salary_setup){
                $salary_setup->employee_id = $request->employee_id;
                $salary_setup->setup_rule_id = $key;
                $salary_setup->type = $setup_rule->type;
                $salary_setup->amount = $allowance;
                $salary_setup->on_basic = $setup_rule->on_basic;
                $salary_setup->on_gross = $setup_rule->on_gross;
                $salary_setup->update();
            } else {
                $salary_setup = new EmployeeSalaryType();
                $salary_setup->employee_id = $request->employee_id;
                $salary_setup->setup_rule_id = $key;
                $salary_setup->type = $setup_rule->type;
                $salary_setup->amount = $allowance;
                $salary_setup->on_basic = $setup_rule->on_basic;
                $salary_setup->on_gross = $setup_rule->on_gross;
                $salary_setup->save();
            }
        }

        Toastr::success('Salary setup done successfully :)', 'Success');
        return redirect()->route('salary-setup.index')->with('success', 'Salary setup done successfully');

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('humanresource::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $employee = Employee::find($id);

        $rules = EmployeeSalaryType::where('employee_id',$id)->get();

        $basic = $rules->filter(function ($value) {
            return $value->type == 'basic';
        })->first();

        $deductions = $rules->filter(function ($value) {
            return $value->type == 'deduction';
        });
        $allowances = $rules->filter(function ($value) {
            return $value->type == 'allowance';
        });

        $employees = Employee::where('is_active', true)->where('is_left', false)->get();

        return view('humanresource::salary-setup.edit', [
            'basic' => $basic,
            'deductions' => $deductions,
            'allowances' => $allowances,
            'employees' => $employees,
            'employee' => $employee,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        foreach($request->allowances as $key => $allowance){
            $salary_setup = EmployeeSalaryType::where('employee_id', $id)->where('setup_rule_id',$key)->first();
            $salary_setup->amount = $allowance;
            $salary_setup->update();
        }

        Toastr::success('Salary setup updated successfully :)', 'Success');
        return redirect()->route('salary-setup.index')->with('success', 'Salary setup updated successfully');

    }

}
