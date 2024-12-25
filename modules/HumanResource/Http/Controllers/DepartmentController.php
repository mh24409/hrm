<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Modules\HumanResource\DataTables\DepartmentDataTable;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\Department;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_department', ['only' => ['index', 'getSubDepartments']]);
        $this->middleware('permission:create_department', ['only' => ['store']]);
        $this->middleware('permission:edit_department', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_department', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(DepartmentDataTable $dataTable)
    {
        $departments = Department::whereNull('parent_id')->paginate(5);
        $sub_departments = Department::whereNotNull('parent_id')->get();

        return $dataTable->render('humanresource::department.index', [
            'departments' => $departments,
            'sub_departments' => $sub_departments,
        ]);
    }

    public function getSubDepartments()
    {
        $sub_departments = Department::whereNotNull('parent_id')->paginate();
        $departments = Department::whereNull('parent_id')->paginate(5);

        return view('humanresource::department.sub-department-index', [
            'departments' => $sub_departments,
            'all_departments' => $departments
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_name' => 'required'
        ]);

        Department::create($request->all());
        Toastr::success('Department added successfully :)', 'Success');
        $route = $request->has('parent_id') ? 'sub-departments.index' : 'departments.index';
        return redirect()->route($route);
    }

    public function edit($id)
    {
        $department = Department::where('uuid', $id)->firstOrFail();
        return view('humanresource::department.modal.edit', compact('department'));
    }


    public function update(Request $request, $uuid)
    {
        $request->validate([
            'department_name' => 'required'
        ]);

        $department = Department::where('uuid', $uuid)->firstOrFail();
        $department->update($request->all());

        Toastr::success('Department Updated successfully :)', 'Success');
        $route = $request->has('parent_id') ? 'sub-departments.index' : 'departments.index';
        return redirect()->route($route);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($uuid)
    {
        $department = Department::where('uuid', $uuid)->first();
        $has_sub_departments = Department::where('parent_id', $department->id)->count();
        if ($has_sub_departments > 0) {
            Toastr::error('Department has already sub department. Can not delete.', 'Error');
            return response()->json(['success' => 'success'], 200);
        }
        $department->delete();
        Toastr::success('Department deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }

    public function getEmployees(Request $request)
    {
        $department = Department::findOrFail($request->id);
        $employees = Employee::where('department_id', $department->id)
            ->where('is_active', true)
            ->get(['id', 'first_name', 'last_name', 'middle_name']);
        return response()->json($employees);
    }
}
