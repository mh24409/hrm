<?php

namespace Modules\HumanResource\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Modules\HumanResource\DataTables\EmployeePerformanceDataTable;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\EmployeePerformance;
use Modules\HumanResource\Entities\EmployeePerformanceValue;
use Modules\HumanResource\Entities\EmployeePerformanceKeyGoal;
use Modules\HumanResource\Entities\EmployeePerformanceEvaluation;
use Modules\HumanResource\Entities\EmployeePerformanceDevelopmentPlan;

class EmployeePerformanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:read_employee_performance'])->only('index');
        $this->middleware(['permission:create_employee_performance'])->only(['create', 'store']);
        $this->middleware(['permission:update_employee_performance'])->only(['edit', 'update']);
        $this->middleware(['permission:delete_employee_performance'])->only('destroy');
    }

    public function index(EmployeePerformanceDataTable $dataTable)
    {
        $employee_performances = EmployeePerformance::where('is_teacher', false)->get();

        return $dataTable->render('humanresource::employee.performance.index', [
            'employee_performances' => $employee_performances
        ]);
    }

    public function create()
    {
        $performance_evaluations = EmployeePerformanceEvaluation::pluck('title', 'short_code');

        $employees = Employee::where('is_active', true)->get();

        return view('humanresource::employee.performance.create', [
            'performance_evaluations' => $performance_evaluations,
            'employees'               => $employees,
        ]);
    }

    public function store(Request $request)
    {

        try {
            DB::beginTransaction();
            $assessment_score = $request->assessment_a_total_score + $request->assessment_b_total_score;

            $employee_performance_data = [
                'employee_id'            => $request->employee_id,
                'review_period'          => $request->review_period,
                'position_of_supervisor' => $request->position_of_supervisor,
                'total_score'            => $assessment_score,
                'employee_comments'      => $request->employee_comments,
                'date'                   => Carbon::now()->format('Y-m-d'),
            ];

            $employee_performance = EmployeePerformance::create($employee_performance_data);

            $employee_perform_values = new EmployeePerformanceValue();

            $postData_a_demonstrated = [
                'performance_id'          => $employee_performance->id,
                'performance_type_id'     => 1,
                'performance_criteria_id' => 1,
                'emp_perform_eval'        => $request->demonstrated,
                'score'                   => $request->demonstrated_score,
                'comments'                => $request->demonstrated_comments,
            ];
            $employee_perform_values->create($postData_a_demonstrated);

            $postData_a_demonstrated = [
                'performance_id'          => $employee_performance->id,
                'performance_type_id'     => 1,
                'performance_criteria_id' => 2,
                'emp_perform_eval'        => $request->timeliness,
                'score'                   => $request->timeliness_score,
                'comments'                => $request->timeliness_score_commnets,
            ];
            $employee_perform_values->create($postData_a_demonstrated);

            $postData_a_demonstrated = [
                'performance_id'          => $employee_performance->id,
                'performance_type_id'     => 1,
                'performance_criteria_id' => 3,
                'emp_perform_eval'        => $request->impact,
                'score'                   => $request->impact_score,
                'comments'                => $request->impact_score_commnets,
            ];
            $employee_perform_values->create($postData_a_demonstrated);

            $postData_a_demonstrated = [
                'performance_id'          => $employee_performance->id,
                'performance_type_id'     => 1,
                'performance_criteria_id' => 4,
                'emp_perform_eval'        => $request->overall,
                'score'                   => $request->overall_score,
                'comments'                => $request->overall_score_commnets,
            ];
            $employee_perform_values->create($postData_a_demonstrated);

            $postData_a_demonstrated = [
                'performance_id'          => $employee_performance->id,
                'performance_type_id'     => 1,
                'performance_criteria_id' => 5,
                'emp_perform_eval'        => $request->beyond_duty,
                'score'                   => $request->beyond_duty,
                'comments'                => $request->beyond_duty_commnets,
            ];
            $employee_perform_values->create($postData_a_demonstrated);

            /*Assessment data*/
            $postData_a_demonstrated = [
                'performance_id'          => $employee_performance->id,
                'performance_type_id'     => 2,
                'performance_criteria_id' => 6,
                'emp_perform_eval'        => $request->interpersonal,
                'score'                   => $request->interpersonal_score,
                'comments'                => $request->interpersonal_commnets,
            ];
            $employee_perform_values->create($postData_a_demonstrated);

            $postData_a_demonstrated = [
                'performance_id'          => $employee_performance->id,
                'performance_type_id'     => 2,
                'performance_criteria_id' => 7,
                'emp_perform_eval'        => $request->attendance,
                'score'                   => $request->attendance_score,
                'comments'                => $request->attendance_commnets,
            ];
            $employee_perform_values->create($postData_a_demonstrated);

            $postData_a_demonstrated = [
                'performance_id'          => $employee_performance->id,
                'performance_type_id'     => 2,
                'performance_criteria_id' => 8,
                'emp_perform_eval'        => $request->communication,
                'score'                   => $request->communication_score,
                'comments'                => $request->communication_commnets,
            ];
            $employee_perform_values->create($postData_a_demonstrated);

            $postData_a_demonstrated = [
                'performance_id'          => $employee_performance->id,
                'performance_type_id'     => 2,
                'performance_criteria_id' => 9,
                'emp_perform_eval'        => $request->contributing,
                'score'                   => $request->contributing_score,
                'comments'                => $request->contributing_commnets,
            ];
            $employee_perform_values->create($postData_a_demonstrated);

            // Insert E. DEVELOPMENT PLAN data into database
            foreach ($request->recommend_areas as $i => $recommended_area) {
                $development_plan = [
                    'recommend_areas'    => $recommended_area,
                    'expected_outcomes'  => $request->expected_outcomes[$i],
                    'responsible_person' => $request->responsible_person[$i],
                    'start_date'          => $request->start_date[$i],
                    'end_date'              => $request->end_date[$i],
                    'performance_id'      => $employee_performance->id,
                ];
                EmployeePerformanceDevelopmentPlan::create($development_plan);
            }

            // Insert F. KEY GOALS FOR NEXT REVIEW PERIOD
            foreach ($request->key_goals as $i => $key_goal) {
                $key_goals_data = [
                    'key_goals'         => $key_goal,
                    'completion_period' => $request->completion_period[$i],
                    'performance_id'         => $employee_performance->id,
                ];
                EmployeePerformanceKeyGoal::create($key_goals_data);
            }

            DB::commit();
            Toastr::success(localize('Employee Performances Added Successfully'), 'Success');
            return redirect()->route('employee-performances.index');
        } catch (\Throwable $th) {
            Toastr::error(localize('Something Went Wrong'), 'Error');
            return redirect()->back();
        }
    }

    public function show($uuid)
    {
        $employee_performance = EmployeePerformance::where('uuid', $uuid)->firstOrFail();

        $assessment_one = EmployeePerformanceValue::where('performance_id', $employee_performance->id)->where('performance_type_id', 1)->orderBy('performance_criteria_id', 'asc')->get();
        $assessment_two = EmployeePerformanceValue::where('performance_id', $employee_performance->id)->where('performance_type_id', 2)->orderBy('performance_criteria_id', 'asc')->get();

        $development_plans = EmployeePerformanceDevelopmentPlan::where('performance_id', $employee_performance->id)->get();
        $key_goals = EmployeePerformanceKeyGoal::where('performance_id', $employee_performance->id)->get();

        return view('humanresource::employee.performance.show', [
            'employee_performance' => $employee_performance,
            'asssesment_one' => $assessment_one,
            'asssesment_two' => $assessment_two,
            'development_plans' => $development_plans,
            'key_goals' => $key_goals
        ]);
    }


    public function edit($uuid)
    {
        $employee_performance = EmployeePerformance::where('uuid', $uuid)->firstOrFail();

        $assessment_one = EmployeePerformanceValue::where('performance_id', $employee_performance->id)->where('performance_type_id', 1)->orderBy('performance_criteria_id', 'asc')->get();

        $assessment_two = EmployeePerformanceValue::where('performance_id', $employee_performance->id)->where('performance_type_id', 2)->orderBy('performance_criteria_id', 'asc')->get();

        $development_plans = EmployeePerformanceDevelopmentPlan::where('performance_id', $employee_performance->id)->get();
        $key_goals = EmployeePerformanceKeyGoal::where('performance_id', $employee_performance->id)->get();

        $performance_evaluations = EmployeePerformanceEvaluation::pluck('title', 'short_code');

        $employees = Employee::get();

        return view('humanresource::employee.performance.edit', [
            'employee_performance' => $employee_performance,
            'performance_evaluations' => $performance_evaluations,
            'employees'               => $employees,
            'assessment_one'          => $assessment_one,
            'assessment_two'          => $assessment_two,
            'development_plans'       => $development_plans,
            'key_goals'               => $key_goals
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $employee_performance = EmployeePerformance::findOrFail($id);
            $assessment_score = $request->assessment_a_total_score + $request->assessment_b_total_score;
            $employee_performance_data = [
                'employee_id'            => $request->employee_id,
                'review_period'          => $request->review_period,
                'position_of_supervisor' => $request->position_of_supervisor,
                'total_score'            => $assessment_score,
                'employee_comments'      => $request->employee_comments,
                'date'                   => Carbon::now()->format('Y-m-d'),
            ];

            $employee_performance->update($employee_performance_data);

            foreach ($request->assessment_one as $i => $value) {
                $employee_perform_value = EmployeePerformanceValue::find($value['eval_id']);
                $postData = [
                    'performance_id'          => $employee_performance->id,
                    'performance_type_id'     => $employee_perform_value->performance_type_id,
                    'performance_criteria_id' => $i + 1,
                    'emp_perform_eval'        => $value['performance_eval'] ?? null,
                    'score'                   => $value['score'] ?? 0,
                    'comments'                => $value['comments'] ?? null,
                ];
                $employee_perform_value->update($postData);
            }

            foreach ($request->assessment_two as $key => $value) {
                $employee_perform_value = EmployeePerformanceValue::find($value['eval_id']);
                $postData = [
                    'performance_id'          => $employee_performance->id,
                    'performance_type_id'     => $employee_perform_value->performance_type_id,
                    'performance_criteria_id' => $key + 1,
                    'emp_perform_eval'        => $value['performance_eval'] ?? null,
                    'score'                   => $value['score'] ?? 0,
                    'comments'                => $value['comments'] ?? null,
                ];
                $employee_perform_value->update($postData);
            }

            foreach ($request->recommend_areas as $i => $recommended_area) {
                $development_plan = [
                    'recommend_areas'    => $recommended_area,
                    'expected_outcomes'  => $request->expected_outcomes[$i],
                    'responsible_person' => $request->responsible_person[$i],
                    'start_date'          => $request->start_date[$i],
                    'end_date'              => $request->end_date[$i],
                    'performance_id'      => $employee_performance->id,
                ];
                EmployeePerformanceDevelopmentPlan::updateOrCreate(['id' => @$request->development_id[$i]], $development_plan);
            }

            // Insert F. KEY GOALS FOR NEXT REVIEW PERIOD
            foreach ($request->key_goals as $i => $key_goal) {
                $key_goals_data = [
                    'key_goals'         => $key_goal,
                    'completion_period' => $request->completion_period[$i],
                    'performance_id'         => $employee_performance->id,
                ];
                EmployeePerformanceKeyGoal::updateOrCreate(['id' => @$request->key_goal_id[$i]], $key_goals_data);
            }
            DB::commit();

            Toastr::success(localize('Employee Performances Updated Successfully'), 'Success');
            return redirect()->route('employee-performances.index');
        } catch (\Throwable $th) {
            DB::rollback();
            Toastr::error(localize('Something Went Wrong'), 'Error');
            return redirect()->back();
        }
    }

    public function destroy($uuid)
    {
        $employee_performance = EmployeePerformance::where('uuid', $uuid)->firstOrFail();
        $employee_performance->delete();
        Toastr::success('Employee Performance Deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }
}
