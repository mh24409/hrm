<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Modules\HumanResource\Entities\Country;
use Illuminate\Contracts\Support\Renderable;
use Modules\HumanResource\DataTables\ProjectReportDataTable;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\ProjectTasks;
use Modules\HumanResource\Entities\ProjectClients;
use Modules\HumanResource\Entities\ProjectSprints;
use Modules\HumanResource\Entities\ProjectEmployees;
use Modules\HumanResource\Entities\ProjectManagement;

class ProjectReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_project_reports', ['only' => ['reports', 'projectWiseReport']]);
        $this->middleware('permission:read_team_member', ['only' => ['teamMemberSearch']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function reports()
    {
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        if (!$employee_info) {
            // isAdmin, if admin then employee data will not be available in employees table.
            // isAdmin user
            $projects      = ProjectManagement::with('clientDetail', 'projectLead')->get();
            $project_leads = $this->supervisor_dropdown();

            return view(
                'humanresource::project.reports.project_list',
                compact(
                    'projects',
                    'employee_info',
                    'project_leads',
                )
            );
        } elseif ($employee_info->is_supervisor == 1 && !empty($employee_info->id)) {
            // Project lead or supervisor
            $project_lead = $employee_info->id; // if employee is supervisor, then he will be the project lead
            $projects     = $this->project_manager_projects($project_lead);
            // this will use as reporter / project_lead, who will lead the project
            $project_leads = $this->project_manager_supervisor_dropdown($project_lead);

            return view(
                'humanresource::project.reports.project_list',
                compact(
                    'projects',
                    'employee_info',
                    'project_leads',
                )
            );
        } elseif ($employee_info->is_supervisor != 1 && !empty($employee_info->id)) {
            // Team Members or employees
            return redirect()->route('project.employee-projects-list');
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function projectWiseReport($project_id)
    {
        //store project_id to session for future use like kanban board..
        session(['project_id' => $project_id]);
        $allSessionData = session()->all();

        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();

        if ($employee_info != null && $employee_info->is_supervisor == 1) {
            // Check the requested project is for the project_lead / supervisor
            $verify_project = $this->verify_project($project_id, $employee_info->id);
            if (!$verify_project) {
                Toastr::error(localize('you_are_not_associated_with_this_project'));
                return redirect()->route('project.reports');
            }
        }
        if ($employee_info != null && $employee_info->is_supervisor != 1 && !empty($employee_info->id)) {
            // Check the requested project is for the employee / team_member
            $verify_employee_project = $this->project_employee_check($project_id, $employee_info->id);
            if (!$verify_employee_project > 0) {
                Toastr::error(localize('you_are_not_associated_with_this_project'));
                return redirect()->route('project.reports');
            }
        }

        $project_info = $this->single_project_data($project_id);
        $project_all_employees_id = $this->get_project_all_employees($project_id);

        $projects = ProjectTasks::with(['employee:id,first_name,middle_name,last_name'])
            ->where('project_id', $project_id)
            ->get()
            ->groupBy('employee_id');

        $total_to_do = 0;
        $total_in_progress = 0;
        $total_done = 0;
        $grand_total = 0;

        $table = [];

        foreach ($projects as $employee_id => $employee_tasks) {
            $employee = $employee_tasks->first()->employee->full_name;
            $to_do = $employee_tasks->where('task_status', 1)->count();
            $in_progress = $employee_tasks->where('task_status', 2)->count();
            $done = $employee_tasks->where('task_status', 3)->count();
            $total = $to_do + $in_progress + $done;

            $table[] = [
                'employee' => $employee,
                'to_do' => $to_do,
                'in_progress' => $in_progress,
                'done' => $done,
                'total' => $total,
            ];

            $total_to_do += $to_do;
            $total_in_progress += $in_progress;
            $total_done += $done;
            $grand_total += $total;
        }

        return view(
            'humanresource::project.reports.project_report',
            [
                'project_id' => $project_id,
                'project_info' => $project_info,
                'project_all_employees_id' => $project_all_employees_id,
                'table' => $table,
                'total_to_do' => $total_to_do,
                'total_in_progress' => $total_in_progress,
                'total_done' => $total_done,
                'grand_total' => $grand_total,
            ]
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function projectRemainingCompleted()
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];

        $project_info = $this->single_project_data($project_id);

        // Getting value for approximate_tasks vs completed_tasks to show in donut chart report in project_wise_report
        $percentage = 100;
        $complete_percentage = 0;
        $remianing_percentage = 0;

        $approximate_tasks = $project_info->approximate_tasks ? $project_info->approximate_tasks : 0;
        $complete_tasks = $project_info->complete_tasks ? $project_info->complete_tasks : 0;

        $complete_percentage = ($complete_tasks / $approximate_tasks) * 100;
        $complete_percentage = round($complete_percentage);

        $remianing_percentage = $percentage - $complete_percentage;
        $remianing_percentage = round($remianing_percentage);

        $respo_data = array();
        $respo_data[] = $complete_percentage;
        $respo_data[] = $remianing_percentage;

        return json_encode($respo_data);
    }

    /**
     * Display a listing of the resource.
     */
    public function projectAllEmployeesName()
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];

        $project_info = $this->single_project_data($project_id);

        $arr_employee_name = array();

        $get_project_all_employees = $this->get_project_all_employees($project_id);
        foreach ($get_project_all_employees as $employee_id) {

            $resp = Employee::select('first_name', 'last_name')
                ->where('id', $employee_id)
                ->first();

            $arr_employee_name[] = $resp->first_name . ' ' . $resp->last_name;
        }

        return json_encode($arr_employee_name);
    }

    /**
     * Display a listing of the resource.
     */
    public function taskToDoByEmployee()
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];

        $arr_employee_todo_tasks = array();

        $get_project_all_employees = $this->get_project_all_employees($project_id);

        foreach ($get_project_all_employees as $employee_id) {

            $num_of_todo_tasks = ProjectTasks::where('project_id', $project_id)
                ->where('employee_id', $employee_id)
                ->where('task_status', 1)
                ->count();

            $arr_employee_todo_tasks[] = $num_of_todo_tasks;
        }

        return json_encode(array_sum($arr_employee_todo_tasks));
    }

    /**
     * Display a listing of the resource.
     */
    public function taskInProgressByEmployee()
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];

        $arr_employee_inprogress_tasks = array();

        $get_project_all_employees = $this->get_project_all_employees($project_id);

        foreach ($get_project_all_employees as $employee_id) {

            $num_of_inprogress_tasks = ProjectTasks::where('project_id', $project_id)
                ->where('employee_id', $employee_id)
                ->where('task_status', 2)
                ->count();

            $arr_employee_inprogress_tasks[] = $num_of_inprogress_tasks;
        }

        return json_encode(array_sum($arr_employee_inprogress_tasks));
    }

    /**
     * Display a listing of the resource.
     */
    public function taskDoneByEmployee()
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];

        $arr_employee_done_tasks = array();

        $get_project_all_employees = $this->get_project_all_employees($project_id);

        foreach ($get_project_all_employees as $employee_id) {

            $num_of_done_tasks = ProjectTasks::where('project_id', $project_id)
                ->where('employee_id', $employee_id)
                ->where('task_status', 3)
                ->count();

            $arr_employee_done_tasks[] = $num_of_done_tasks;
        }

        return json_encode(array_sum($arr_employee_done_tasks));
    }

    /**
     * Display a listing of the resource.
     */
    public function projectVariousStatusTasks()
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];

        $project_info = $this->single_project_data($project_id);

        //Get To Do tasks
        $project_to_do_tasks = $this->project_to_do_tasks($project_id);

        //Get In Progress tasks
        $project_in_progress_tasks = $this->project_in_progress_tasks($project_id);

        //Get Done tasks
        $project_done_tasks = $this->project_done_tasks($project_id);

        $resp_data = array();
        $resp_data[] = $project_to_do_tasks ? $project_to_do_tasks : 0;
        $resp_data[] = $project_in_progress_tasks ? $project_in_progress_tasks : 0;
        $resp_data[] = $project_done_tasks ? $project_done_tasks : 0;

        return json_encode($resp_data);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function employeeProjectLists()
    {
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();

        $employee_id = $employee_info->id;
        $projects    = $this->employee_projects($employee_id);

        return view(
            'humanresource::project.reports.employee_project_list',
            compact(
                'projects',
                'employee_info',
            )
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function teamMemberSearch()
    {
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();

        if ($employee_info == null) {

            $project_info = $this->project_all_employees_list();

            return view(
                'humanresource::project.reports.team_member_project',
                compact(
                    'project_info',
                    'employee_info',
                )
            );
        }
        if ($employee_info != null && $employee_info->is_supervisor == 1) {

            $project_info = $this->project_lead_all_employees_list($employee_info->id);
            return view(
                'humanresource::project.reports.team_member_project',
                compact(
                    'project_info',
                    'employee_info',
                )
            );
        }
        if ($employee_info != null && $employee_info->is_supervisor != 1) {
            return redirect()->route('project.employee-projects-list');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function getEmployeeProjects($employee_id = null)
    {
        $login_user_id = Auth::id();
        $logged_employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();

        if ($logged_employee_info == null) {

            $project_lists = $this->employee_projects($employee_id);

            $html = "";
            $html .=  "<option value=''>Select Project</option>";
            foreach ($project_lists as $data) {
                $html .= "<option value='$data->id'>$data->project_name</option>";
            }

            return $html;
        }
        if ($logged_employee_info != null && $logged_employee_info->is_supervisor == 1) {

            $project_lists = $this->get_employee_projects($employee_id, $logged_employee_info->id);

            $html = "";
            $html .=  "<option value=''>Select Project</option>";
            foreach ($project_lists as $data) {
                $html .= "<option value='$data->id'>$data->project_name</option>";
            }

            return $html;
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function getEmployeeProjectTasks(Request $request)
    {

        $login_user_id = Auth::id();
        $logged_employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();

        $employee_id = $request->input('employee_id');
        $project_id = $request->input('project_id');

        $employee_info = Employee::select('employee_id', 'id', 'first_name', 'middle_name', 'last_name')
            ->where('id', $employee_id)
            ->first();

        $project_info = $this->single_project_data($project_id);

        $project_tasks_list = $this->employee_project_tasks_list($employee_id, $project_id);
        $title = localize('all_tasks') . " for " . $employee_info->first_name . " " . $employee_info->last_name . " from " . $project_info->project_name;

        return view(
            'humanresource::project.reports.project_tasks_list',
            compact(
                'title',
                'project_info',
                'employee_info',
                'project_tasks_list',
            )
        );
    }

    // Get all pm_tasks_list where priority is desc and must match the project_id and employee_id
    public function employee_project_tasks_list($employee_id = null, $project_id = null)
    {
        return ProjectTasks::select(
            'pm_tasks_list.*',
            'e.first_name as team_mem_firstname',
            'e.last_name as team_mem_lastname',
            'p.project_name',
            'ep.first_name as proj_lead_firstname',
            'ep.last_name as proj_lead_lastname',
            'ps.sprint_name',
            'u.full_name as task_creator_user_name'
        )
            ->leftJoin('pm_projects as p', 'pm_tasks_list.project_id', '=', 'p.id')
            ->leftJoin('employees as ep', 'pm_tasks_list.project_lead', '=', 'ep.id')
            ->leftJoin('employees as e', 'pm_tasks_list.employee_id', '=', 'e.id')
            ->leftJoin('pm_sprints as ps', 'pm_tasks_list.sprint_id', '=', 'ps.id')
            ->leftJoin('users as u', 'pm_tasks_list.created_by', '=', 'u.id')
            ->where('pm_tasks_list.project_id', $project_id)
            ->where('pm_tasks_list.employee_id', $employee_id)
            ->orderByDesc('pm_tasks_list.priority')
            ->orderByDesc('pm_tasks_list.id')
            ->get();
    }

    public function get_employee_projects($team_member_id = null, $project_lead_id = null)
    {

        $arr_result = array();
        $projects_info = array();

        $data = DB::select("
            SELECT DISTINCT project_id 
            FROM pm_tasks_list 
            WHERE project_lead = ? AND employee_id = ? 
            ORDER BY project_id DESC
        ", [$project_lead_id, $team_member_id]);

        foreach ($data as $row) {

            $arr_result[] = $row->project_id;
            $projects_info[] = $this->project_details($row->project_id);
        }

        return $projects_info;
    }

    // Get project_lead_all_employees_list if any single task created for any employee.
    public function project_lead_all_employees_list($project_lead = null)
    {

        $list = array('' => 'Select One...');

        $project_all_employees_info = $this->get_project_lead_all_employees_info($project_lead);

        foreach ($project_all_employees_info as $employee_id) {

            $resp = Employee::select('employee_id', 'id', 'first_name', 'middle_name', 'last_name')
                ->where('id', $employee_id)
                ->where('is_active', 1)
                ->first();

            $list[$resp->id] = $resp->first_name . ' ' . $resp->last_name;
        }

        return $list;
    }

    public function get_project_lead_all_employees_info($project_lead = null)
    {

        $arr_result = array();

        $data =  ProjectTasks::where('project_lead', $project_lead)
            ->distinct('employee_id')
            ->orderBy('employee_id', 'asc')
            ->get();

        foreach ($data as $row) {

            $arr_result[] = $row->employee_id;
        }

        return $arr_result;
    }

    // Get project_all_employees_list if any single task created for any employee.
    public function project_all_employees_list()
    {

        $list = array('' => 'Select One...');

        $project_all_employees_info = $this->get_project_all_employees_info();

        foreach ($project_all_employees_info as $employee_id) {

            $resp = Employee::select('employee_id', 'id', 'first_name', 'middle_name', 'last_name')
                ->where('id', $employee_id)
                ->where('is_active', 1)
                ->first();

            $list[$resp->id] = $resp->first_name . ' ' . $resp->last_name;
        }

        return $list;
    }

    public function get_project_all_employees_info()
    {
        $arr_result = array();

        $data = Employee::distinct('id')
            ->where('is_active', 1)
            ->where('is_supervisor', 0)
            ->orderBy('id', 'asc')
            ->get();

        foreach ($data as $row) {

            $arr_result[] = $row->id;
        }

        return $arr_result;
    }

    public function employee_projects($team_member_id)
    {
        $projects_info = [];

        $queryResult = DB::table('pm_tasks_list')
            ->select('project_id')
            ->where('employee_id', $team_member_id)
            ->distinct()
            ->orderByDesc('project_id')
            ->get();

        foreach ($queryResult as $row) {
            $res = ProjectEmployees::where('employee_id', $team_member_id)
                ->where('project_id', $row->project_id)
                ->first();
            if ($res) {
                $projects_info[] = $this->project_details($row->project_id);
            }
        }

        return $projects_info;
    }

    public function project_details($project_id = null)
    {
        return ProjectManagement::with('clientDetail', 'projectLead')->select('pm_projects.*', 'e.first_name', 'e.last_name', 'pmc.client_name')
            ->leftJoin('employees as e', 'pm_projects.project_lead', '=', 'e.id')
            ->leftJoin('pm_clients as pmc', 'pm_projects.client_id', '=', 'pmc.id')
            ->where('pm_projects.id', $project_id)
            ->first();
    }

    public function project_done_tasks($project_id = null)
    {
        return ProjectTasks::where('project_id', $project_id)
            ->where('task_status', 3)
            ->count();
    }

    public function project_in_progress_tasks($project_id = null)
    {
        return ProjectTasks::where('project_id', $project_id)
            ->where('task_status', 2)
            ->count();
    }

    public function project_to_do_tasks($project_id = null)
    {
        return ProjectTasks::where('project_id', $project_id)
            ->where('task_status', 1)
            ->count();
    }

    public function get_project_all_employees($project_id = null)
    {
        $arr_result = array();

        $data = ProjectTasks::select('employee_id')
            ->distinct()
            ->where('project_id', $project_id)
            ->orderBy('employee_id', 'asc')
            ->get();

        foreach ($data as $row) {

            $arr_result[] = $row->employee_id;
        }

        return $arr_result;
    }

    public function single_project_data($project_id)
    {
        return ProjectManagement::where('id', $project_id)->first();
    }

    public function project_employee_check($project_id = null, $employee_id = null)
    {
        return ProjectTasks::where('project_id', $project_id)
            ->where('employee_id', $employee_id)
            ->count();
    }

    public function verify_project($project_id = null, $project_lead = null)
    {
        return ProjectManagement::where('id', $project_id)->where('project_lead', $project_lead)->first();
    }

    /*Getting employee who are created as supervisor when logged in as supervisor / project_lead*/
    public function project_manager_supervisor_dropdown($employee_id = null)
    {
        $data = Employee::where('id', $employee_id)
            ->where('is_active', 1)
            ->where('is_supervisor', 1)
            ->get();

        $list = array('' => 'Select One...');
        if (!empty($data)) {
            foreach ($data as $value) {
                $list[$value->id] = $value->first_name . " " . $value->last_name;
            }
        }
        return $list;
    }

    /*Get all projects by project_manager / project lead*/
    public function project_manager_projects($project_lead = null)
    {
        return ProjectManagement::with('clientDetail', 'projectLead')
            ->select('pm_projects.*', 'e.first_name', 'e.last_name', 'pmc.client_name')
            ->leftJoin('employees as e', 'pm_projects.project_lead', '=', 'e.id')
            ->leftJoin('pm_clients as pmc', 'pm_projects.client_id', '=', 'pmc.id')
            ->where('project_lead', $project_lead)
            ->orderByDesc('pm_projects.id')
            ->get();
    }

    /*Getting employee who are created as supervisor*/
    public function supervisor_dropdown()
    {
        $data = Employee::where('is_active', 1)
            ->where('is_supervisor', 1)
            ->get();

        $list = array('' => 'Select One...');
        if (!empty($data)) {
            foreach ($data as $value) {
                $list[$value->id] = $value->full_name;
            }
        }
        return $list;
    }
}
