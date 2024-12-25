<?php

namespace Modules\HumanResource\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HumanResource\DataTables\SalaryAdvanceDataTable;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\SalaryAdvance;
use Modules\HumanResource\Http\Requests\SalaryAdvanceRequest;

class SalaryAdvanceController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read_salary_advance')->only(['index']);
        $this->middleware('permission:create_salary_advance', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit_salary_advance', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_salary_advance', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */

    public function index(SalaryAdvanceDataTable $dataTable)
    {
        $employees = Employee::all();
        return $dataTable->render('humanresource::salary_advance.index', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(SalaryAdvanceRequest $request)
    {
        $salary_advance = new SalaryAdvance();
        $salary_advance->fill($request->all());
        $salary_advance->save();

        Toastr::success('Salary Advance Created successfully :)', 'Success');
        return redirect()->route('salary-advance.index');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $uuid)
    {
        $request->validate([
            'amount' => 'required',
        ]);

        $advance = SalaryAdvance::where('uuid', $uuid)->firstOrFail();
        $advance->update($request->all());

        return redirect()->back()->with('success', localize('salary_advance_updated'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $salaryAdvance = SalaryAdvance::findOrFail($id);
        $salaryAdvance->delete();

        return redirect()->back()->with('success', localize('salary_advance_deleted'));
    }
}
