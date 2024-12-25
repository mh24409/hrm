<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Modules\HumanResource\Entities\EmployeePerformanceType;
use Modules\HumanResource\Entities\EmployeePerformanceCriteria;
use Modules\HumanResource\DataTables\PerformanceCriteriasDataTable;
use Modules\HumanResource\Entities\EmployeePerformanceEvaluationType;

class EmployeePerformanceCriteriaController extends Controller {
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(PerformanceCriteriasDataTable $dataTable) {

        return $dataTable->render('humanresource::employee.performance.criteria.index', [
            'performance_types'     => EmployeePerformanceType::all(),
            'evaluation_types'      => EmployeePerformanceEvaluationType::all(),
        ]);
    }

    public function store(Request $request) {
        
        $request->validate([
            'title'               => 'required',
            'performance_type_id' => 'required',
        ]);

        $criteria                      = new EmployeePerformanceCriteria();
        $criteria->title               = $request->title;
        $criteria->performance_type_id = $request->performance_type_id;
        $criteria->description         = $request->description;
        $criteria->evaluation_type_id = $request->evaluation_type_id;
        $criteria->save();

        Toastr::success('Employee Performance Criteria added successfully :)', 'Success');
        return redirect()->route('performance-criterias.index');
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id) {

        $request->validate([
            'title'               => 'required',
            'performance_type_id' => 'required',
        ]);

        $criteria                      = EmployeePerformanceCriteria::find($id);
        $criteria->title               = $request->title;
        $criteria->performance_type_id = $request->performance_type_id;
        $criteria->description         = $request->description;
        $criteria->evaluation_type_id = $request->evaluation_type_id;
        $criteria->update();

        Toastr::success('Employee Performance Criteria Updated successfully :)', 'Success');
        return redirect()->route('performance-criterias.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id) {

        $employee_performance_criteria = EmployeePerformanceCriteria::where('id', $id)->firstOrFail();
        $employee_performance_criteria->delete();
        Toastr::success('Performance Criteria Deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }
}
