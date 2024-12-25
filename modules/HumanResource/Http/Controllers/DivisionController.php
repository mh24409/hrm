<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Modules\HumanResource\DataTables\SubDepartmentDataTable;
use Modules\HumanResource\Entities\Department;

class DivisionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_sub_departments', ['only' => ['index']]);
        $this->middleware('permission:create_sub_departments', ['only' => ['store']]);
        $this->middleware('permission:edit_sub_departments', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_sub_departments', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(SubDepartmentDataTable $dataTable)
    {
        $departments = Department::whereNull('parent_id')->get();

        return $dataTable->render('humanresource::division.index', [
            'departments'   => $departments
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
            'division_name' => 'required',
            'parent_id'     => 'required'
        ]);

        $department = new Department();
        $department->department_name    = $request->division_name;
        $department->parent_id          = $request->parent_id;
        $department->is_active          = $request->is_active;
        $department->save();

        Toastr::success('Division added successfully :)', 'Success');
        return redirect()->route('divisions.index');
    }

    public function edit($uuid)
    {
        $division = Department::where('uuid', $uuid)->firstOrFail();
        $departments = Department::whereNull('parent_id')->get();

        return view('humanresource::division.modal.edit', [
            'division'      => $division,
            'departments'   => $departments
        ]);
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
            'division_name' => 'required',
            'parent_id'     => 'required'
        ]);

        $department = Department::where('uuid', $uuid)->firstOrFail();
        $department->department_name    = $request->division_name;
        $department->parent_id          = $request->parent_id;
        $department->is_active          = $request->is_active;
        $department->update();

        Toastr::success('Division Updated successfully :)', 'Success');
        return redirect()->route('divisions.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $uuid
     * @return Renderable
     */
    public function destroy($uuid)
    {
        Department::where('uuid', $uuid)->delete();
        Toastr::success('Division deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }
}
