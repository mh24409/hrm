<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Modules\HumanResource\Entities\ProjectClients;
use Modules\HumanResource\Entities\Country;
use Modules\HumanResource\Entities\ProjectManagement;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\ProjectEmployees;
use Modules\HumanResource\Entities\ProjectSprints;
use Modules\HumanResource\Entities\ProjectTasks;

class ProjectTasksController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_projects', ['only' => ['employeeProjects']]);

        $this->middleware('permission:read_task', ['only' => ['emplSprintAllTasks', 'emplKanbanBoard', 'emplSprintOwnTasks', 'pmKanbanBoard', 'pmSprintTasks', 'sprintTasks', 'projectAllTasks', 'employeeProjectTasks', 'transferTasks', 'manageTasks', 'projectTasks', 'projectsLeadTasks', 'pmProjectAllTasks']]);
        $this->middleware('permission:create_task', ['only' => ['employeeProjectTaskStore', 'employeeProjectTaskCreate', 'pmProjectTaskStore', 'transferTasksStore', 'projectTaskCreate', 'projectTaskStore', 'pmProjectTaskCreate']]);
        $this->middleware('permission:update_task', ['only' => ['kanbanTaskUpdate', 'employeeProjectTaskUpdate', 'employeeProjectTaskEdit', 'pmProjectTaskEdit', 'pmProjectTaskUpdate', 'projectTaskEdit', 'projectTaskUpdate']]);

        $this->middleware('permission:read_sprint', ['only' => ['emplProjectSprints', 'pmProjectSprints', 'projectSprints']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('humanresource::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('humanresource::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
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
        return view('humanresource::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function transferTasks()
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];
        $project_info = ProjectManagement::where('id', $project_id)->first();
        $project_lead = $project_info->project_lead;

        // Get all backlogs tasks for the project where is_task = 0  means it still consists in backlogs.
        $backlogs_tasks = $this->all_backlogs_tasks($project_id);
        // Get the active sprint for the project.. which is_finished = 0 , means the sprints is still not finished.
        $sprints = $this->get_sprints($project_id);

        return view(
            'humanresource::project.backlogs.transfer_tasks',
            compact(
                'project_info',
                'backlogs_tasks',
                'sprints'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function transferTasksStore(Request $request)
    {

        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];
        $project_info = ProjectManagement::where('id', $project_id)->first();
        $project_lead = $project_info->project_lead;

        $sprint_project_id = $request->input('project_id');
        $sprint_id = $request->input('sprint_id');
        $backlogs_tasks = $request->input('backlog_tasks');

        $validated = $request->validate([
            'sprint_id'        => 'required',
        ]);

        if ($project_id != $sprint_project_id) {
            return redirect()->route('project.transfer-tasks')->with('fail', localize('you_are_not_authorize_with_the_project_as_opened_project_in_other_tab'));
        }
        // If not select any backlogs , then show exception message and redirect to transfer_tasks page.
        if ($backlogs_tasks == null || count($backlogs_tasks) <= 0) {
            return redirect()->route('project.transfer-tasks')->with('fail', localize('please_select_backlogs_to_transfer_as_sprint_tasks'));
        }

        $backlog_transfer_status = false;
        if (!empty($sprint_id)) {
            foreach ($backlogs_tasks as $key => $value) {
                $backlogData = [
                    'task_id'          => $value,
                    'sprint_id'     => $sprint_id,
                    'is_task'          => 1,
                ];
                $resp = $this->update_task_from_backlogs($backlogData, $value);
                if ($resp) {
                    $backlog_transfer_status = true;
                }
            }
        }
        if ($backlog_transfer_status) {
            return redirect()->route('project.transfer-tasks')->with('success', localize('tasks_transferred_successfully'));
        } else {
            return redirect()->route('project.transfer-tasks')->with('fail', localize('something_went_wrong'));
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function manageTasks()
    {
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        if (!$employee_info) {
            // isAdmin, if admin then employee data will not be available in employees table.
            // isAdmin user
            $project_lists = $this->all_projects();
            return view(
                'humanresource::project.tasks.manage_tasks',
                compact(
                    'project_lists',
                )
            );
        } elseif ($employee_info->is_supervisor == 1 && !empty($employee_info->id)) {
            // Project lead or supervisor
            return redirect()->route('project.projects-lead-tasks', $employee_info->id);
        } elseif ($employee_info->is_supervisor != 1 && !empty($employee_info->id)) {
            // Team Members or employees
            return redirect()->route('project.employee-projects', $employee_info->id);
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function projectTasks($project_id)
    {
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        if ($employee_info != null) {
            return redirect()->route('project.manage-tasks')->with('fail', localize('something_went_wrong'));
        }

        $project_info = $this->single_project_data($project_id);
        if ($project_info->is_completed == 1) {
            return redirect()->route('project.project-tasks', $project_id)->with('fail', localize('project_already_closed'));
        }

        //store project_id to session for future use like kanban board..
        session(['project_id' => $project_id]);
        $allSessionData = session()->all();

        // this will use as reporter / project_lead, who will lead the project
        $project_leads = $this->supervisor_dropdown();
        // this will use as assignee / team_members, who will work on the project
        $team_members = $this->assigned_empdropdown($project_id);
        // available sprints for the project
        $available_sprints = $this->get_sprints($project_id);
        $project_tasks = $this->project_tasks($project_id);

        // isAdmin user
        return view(
            'humanresource::project.tasks.project_tasks',
            compact(
                'project_info',
                'project_leads',
                'team_members',
                'available_sprints',
                'project_tasks',
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function projectTaskCreate($project_id)
    {
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        if ($employee_info != null) {
            return redirect()->route('project.manage-tasks')->with('fail', localize('something_went_wrong'));
        }

        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];
        $project_info = ProjectManagement::where('id', $project_id)->first();
        $project_lead = $project_info->project_lead;
        if ($project_info->is_completed == 1) {
            return redirect()->route('project.project-tasks', $project_id)->with('fail', localize('project_already_closed'));
        }



        // this will use as reporter / project_lead, who will lead the project
        $project_leads = $this->supervisor_dropdown();
        // this will use as assignee / team_members, who will work on the project
        $team_members = $this->assigned_empdropdown($project_id);
        // available sprints for the project
        $available_sprints = $this->get_sprints($project_id);

        return view(
            'humanresource::project.tasks.create_project_task',
            compact(
                'project_info',
                'project_leads',
                'team_members',
                'available_sprints'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function projectTaskStore(Request $request, $req_project_id)
    {
        $allSessionData = session()->all();
        $project_id = $sess_project_id = $allSessionData['project_id'];
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        if ($employee_info) {
            return redirect()->route('project.project-tasks', $req_project_id)->with('fail', localize('invalid_request'));
        }
        // If anyone tries to change project_id from inspect element, then will catch exception here.
        if ($req_project_id != $project_id) {
            return redirect()->route('project.project-tasks', $req_project_id)->with('fail', localize('you_have_already_opened_project_in_another_tab'));
        }

        $project_info = ProjectManagement::where('id', $project_id)->first();

        //Total tasks for an individual project is over then not allow to create any further task for the project
        $project_all_backlogs_tasks  = $this->project_all_backlogs_tasks($project_id);
        if ($project_all_backlogs_tasks >= $project_info->approximate_tasks) {
            return redirect()->route('project.project-tasks', $req_project_id)->with('fail', localize('sorry_your_approximate_tax_limit_is_over'));
        }

        $validated = $request->validate([
            'summary'        => 'required',
            'description'    => 'required',
            'employee_id'    => 'required',
            'priority'       => 'required',
            'sprint_id'      => 'required',
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $request_file = $request->file('attachment');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('attachments', $filename, 'public');
        }

        $postData = [
            'project_id'     => $project_info->id,
            'summary'          => $request->input('summary'),
            'description'   => $request->input('description'),
            'project_lead'  => $project_info->project_lead,
            'employee_id'   => $request->input('employee_id'),
            'sprint_id'       => $request->input('sprint_id'),
            'priority'       => $request->input('priority'),
            'attachment'       => $path,
        ];

        if (!empty($postData['sprint_id'])) {

            $postData['is_task'] = 1;
        } else {
            $postData['is_task'] = 0;
        }

        try {
            // Attempt to create the ProjectTasks instance
            $task = ProjectTasks::create($postData);

            // If the creation was successful, redirect with success message
            return redirect()->route('project.project-tasks', $req_project_id)->with('success', localize('data_saved_successfully'));
        } catch (\Exception $e) {
            // If an exception occurs (e.g., validation error, database error), handle it here
            // You can customize the error message based on the type of exception
            return redirect()->back()->withInput()->with('fail', 'Failed to save data: ' . $e->getMessage());
        }
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function projectTaskEdit(ProjectTasks $task)
    {
        $task_data = $task;

        $allSessionData = session()->all();
        $project_id = $sess_project_id = $allSessionData['project_id'];

        // Verify, if this task is associated or not with the project_leader/Reporter..
        $valid_task = $this->valid_task_check($task->id, $project_id);
        // Sprint finished, still trying to access the task to update , then show exception that.. sprint sinished!!!
        $is_sprint_finished = $this->valid_sprint_check($valid_task->sprint_id);

        $project_info = ProjectManagement::where('id', $project_id)->first();
        $project_lead = $project_info->project_lead;

        // this will use as reporter / project_lead, who will lead the project
        $project_leads = $this->supervisor_dropdown();
        // this will use as assignee / team_members, who will work on the project
        $team_members = $this->assigned_empdropdown($project_id);
        // available sprints for the project
        $available_sprints = $this->get_sprints($project_id);

        return view(
            'humanresource::project.tasks.edit_task',
            compact(
                'task_data',
                'project_info',
                'project_leads',
                'team_members',
                'available_sprints'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function projectTaskUpdate(Request $request, ProjectTasks $task)
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];

        // Verify, if this task is associated or not with the project_leader/Reporter..
        $valid_task = $this->valid_task_check($task->id, $project_id);
        if (!$valid_task) {
            return redirect()->route('project.project-tasks', $project_id)->with('fail', localize('ypu_are_not_associated_with_this_task'));
        }
        // Sprint finished, still trying to access the task to update , then show exception that.. sprint sinished!!!
        $is_sprint_finished = $this->valid_sprint_check($valid_task->sprint_id);
        if ($is_sprint_finished) {
            return redirect()->route('project.project-tasks', $project_id)->with('fail', localize('sprint_already_finished'));
        }

        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        if ($employee_info != null) {
            //Check, if the task_id is for the logged in supervisor/project_lead and existis in the pm_tasks_list for the supervisor...
            $project_task_check = $this->project_task_check($task->id, $employee_info->id);
            if (!$project_task_check || $employee_info->is_supervisor != 1) {
                return redirect()->route('project.project-tasks', $project_id)->with('fail', localize('invalid_request'));
            }
        }

        $validated = $request->validate([
            'summary'        => 'required',
            'description'    => 'required',
            'priority'       => 'required',
        ]);

        $postData = [
            'task_id'          => $task->id,
            'summary'          => $request->input('summary'),
            'description'   => $request->input('description'),
            'employee_id'   => $request->input('employee_id'),
            'sprint_id'       => $request->input('sprint_id'),
            'priority'       => $request->input('priority'),
            'task_status'   => $request->input('task_status'),

        ];

        $path = null;
        if ($request->hasFile('attachment')) {
            $request_file = $request->file('attachment');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('attachments', $filename, 'public');
            $postData['attachment'] = $path;
        }

        if (!empty($postData['sprint_id'])) {

            $postData['is_task'] = 1;
        } else {
            $postData['is_task'] = 0;
        }

        try {
            // Attempt to update the ProjectTasks instance
            $task = ProjectTasks::findOrFail($task->id);
            $task->update($postData);

            if ($task) {
                // Updating project's completed_tasks
                $tasks_completed_by_project = 0;

                $task_details = $this->task_details($task->id);
                $tasks_completed_by_project = $this->tasks_completed_by_project($task_details->project_id);

                // Update project completed_tasks...
                $project_data['complete_tasks'] = $tasks_completed_by_project;

                $update_project = $this->update_project($project_data, $task_details->project_id);
                // End of Updating project's completed_tasks
            }

            // If the creation was successful, redirect with success message
            return redirect()->route('project.project-tasks', $project_id)->with('success', localize('data_updated_successfully'));
        } catch (\Exception $e) {
            // If an exception occurs (e.g., validation error, database error), handle it here
            // You can customize the error message based on the type of exception
            return redirect()->back()->withInput()->with('fail', 'Failed to update data: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function projectsLeadTasks($projects_lead_id)
    {
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        if ($employee_info != null && $employee_info->id != $projects_lead_id) {
            return redirect()->route('project.projects-lead-tasks', $employee_info->id);
        }
        $project_lists = $this->projects_lead_projects($projects_lead_id);
        return view(
            'humanresource::project.tasks.pm_projects',
            compact(
                'project_lists',
            )
        );
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function pmProjectAllTasks($project_id = null)
    {
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        $project_info = $this->single_project_data($project_id);
        if ($project_info->is_completed == 1) {
            return redirect()->route('project.projects-lead-tasks', $employee_info->id)->with('fail', localize('project_already_closed'));
        }
        // Check, if the request project_id is for the logged in supervisor/project_lead and existis in the project_lists for the supervisor...
        $project_lead_check = $this->project_lead_check($project_id, $employee_info->id);
        if (!$project_lead_check) {
            return redirect()->route('project.projects-lead-tasks', $employee_info->id)->with('fail', localize('invalid_request'));
        }

        //store project_id to session for future use like kanban board..
        session(['project_id' => $project_id]);
        $allSessionData = session()->all();

        $all_tasks = $this->project_tasks($project_id);
        // this will use as reporter / project_lead, who will lead the project
        $project_leads = $this->project_manager_supervisor_dropdown($employee_info->id);
        // this will use as assignee / team_members, who will work on the project
        $team_members = $this->assigned_empdropdown($project_id);
        // available sprints for the project
        $available_sprints = $this->get_sprints($project_id);

        return view(
            'humanresource::project.tasks.pm_project_all_tasks',
            compact(
                'project_info',
                'all_tasks',
                'project_leads',
                'team_members',
                'available_sprints',
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function pmProjectTaskCreate($project_id)
    {
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();

        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];
        $project_info = ProjectManagement::where('id', $project_id)->first();
        $project_lead = $project_info->project_lead;
        if ($project_info->is_completed == 1) {
            return redirect()->route('project.project-tasks', $project_id)->with('fail', localize('project_already_closed'));
        }
        // Check, if the request project_id is for the logged in supervisor/project_lead and existis in the project_lists for the supervisor...
        $project_lead_check = $this->project_lead_check($project_id, $project_lead);
        if (!$project_lead_check) {
            return redirect()->route('project.projects-lead-tasks', $employee_info->id)->with('fail', localize('invalid_request'));
        }

        // this will use as reporter / project_lead, who will lead the project
        $project_leads = $this->supervisor_dropdown();
        // this will use as assignee / team_members, who will work on the project
        $team_members = $this->assigned_empdropdown($project_id);
        // available sprints for the project
        $available_sprints = $this->get_sprints($project_id);

        return view(
            'humanresource::project.tasks.create_project_task',
            compact(
                'project_info',
                'project_leads',
                'team_members',
                'available_sprints'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function pmProjectTaskStore(Request $request, $req_project_id)
    {
        $allSessionData = session()->all();
        $project_id = $sess_project_id = $allSessionData['project_id'];
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        if ($employee_info && $employee_info->is_supervisor != 1) {
            return redirect()->route('project.pm-project-all-tasks', $project_id)->with('fail', localize('invalid_request'));
        }
        // If anyone tries to change project_id from inspect element, then will catch exception here.
        if ($req_project_id != $project_id) {
            return redirect()->route('project.pm-project-all-tasks', $project_id)->with('fail', localize('you_have_already_opened_project_in_another_tab'));
        }

        $project_info = ProjectManagement::where('id', $project_id)->first();

        //Total tasks for an individual project is over then not allow to create any further task for the project
        $project_all_backlogs_tasks  = $this->project_all_backlogs_tasks($project_id);
        if ($project_all_backlogs_tasks >= $project_info->approximate_tasks) {
            return redirect()->route('project.pm-project-all-tasks', $project_id)->with('fail', localize('sorry_your_approximate_tax_limit_is_over'));
        }

        $validated = $request->validate([
            'summary'        => 'required',
            'description'    => 'required',
            'employee_id'    => 'required',
            'priority'       => 'required',
            'sprint_id'      => 'required',
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $request_file = $request->file('attachment');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('attachments', $filename, 'public');
        }

        $postData = [
            'project_id'     => $project_info->id,
            'summary'          => $request->input('summary'),
            'description'   => $request->input('description'),
            'project_lead'  => $project_info->project_lead,
            'employee_id'   => $request->input('employee_id'),
            'sprint_id'       => $request->input('sprint_id'),
            'priority'       => $request->input('priority'),
            'attachment'       => $path,
        ];

        if (!empty($postData['sprint_id'])) {

            $postData['is_task'] = 1;
        } else {
            $postData['is_task'] = 0;
        }

        try {
            // Attempt to create the ProjectTasks instance
            $task = ProjectTasks::create($postData);
            // If the creation was successful, redirect with success message
            return redirect()->route('project.pm-project-all-tasks', $project_id)->with('success', localize('data_saved_successfully'));
        } catch (\Exception $e) {
            // If an exception occurs (e.g., validation error, database error), handle it here
            // You can customize the error message based on the type of exception
            return redirect()->back()->withInput()->with('fail', 'Failed to save data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function pmProjectTaskEdit(ProjectTasks $task)
    {
        $task_data = $task;

        $allSessionData = session()->all();
        $project_id = $sess_project_id = $allSessionData['project_id'];

        // Verify, if this task is associated or not with the project_leader/Reporter..
        $valid_task = $this->valid_task_check($task->id, $project_id);
        // Sprint finished, still trying to access the task to update , then show exception that.. sprint sinished!!!
        $is_sprint_finished = $this->valid_sprint_check($valid_task->sprint_id);

        $project_info = ProjectManagement::where('id', $project_id)->first();
        $project_lead = $project_info->project_lead;

        // this will use as reporter / project_lead, who will lead the project
        $project_leads = $this->supervisor_dropdown();
        // this will use as assignee / team_members, who will work on the project
        $team_members = $this->assigned_empdropdown($project_id);
        // available sprints for the project
        $available_sprints = $this->get_sprints($project_id);

        return view(
            'humanresource::project.tasks.edit_task',
            compact(
                'task_data',
                'project_info',
                'project_leads',
                'team_members',
                'available_sprints'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function pmProjectTaskUpdate(Request $request, ProjectTasks $task)
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];

        // Verify, if this task is associated or not with the project_leader/Reporter..
        $valid_task = $this->valid_task_check($task->id, $project_id);
        if (!$valid_task) {
            return redirect()->route('project.pm-project-all-tasks', $project_id)->with('fail', localize('ypu_are_not_associated_with_this_task'));
        }
        // Sprint finished, still trying to access the task to update , then show exception that.. sprint sinished!!!
        $is_sprint_finished = $this->valid_sprint_check($valid_task->sprint_id);
        if ($is_sprint_finished) {
            return redirect()->route('project.pm-project-all-tasks', $project_id)->with('fail', localize('sprint_already_finished'));
        }
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        if ($employee_info != null) {
            //Check, if the task_id is for the logged in supervisor/project_lead and existis in the pm_tasks_list for the supervisor...
            $project_task_check = $this->project_task_check($task->id, $employee_info->id);
            if (!$project_task_check || $employee_info->is_supervisor != 1) {
                return redirect()->route('project.pm-project-all-tasks', $project_id)->with('fail', localize('invalid_request'));
            }
        }

        $validated = $request->validate([
            'summary'        => 'required',
            'description'    => 'required',
            'priority'       => 'required',
        ]);

        $postData = [
            'task_id'          => $task->id,
            'summary'          => $request->input('summary'),
            'description'   => $request->input('description'),
            'employee_id'   => $request->input('employee_id'),
            'sprint_id'       => $request->input('sprint_id'),
            'priority'       => $request->input('priority'),
            'task_status'   => $request->input('task_status'),

        ];

        $path = null;
        if ($request->hasFile('attachment')) {
            $request_file = $request->file('attachment');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('attachments', $filename, 'public');
            $postData['attachment'] = $path;
        }

        if (!empty($postData['sprint_id'])) {

            $postData['is_task'] = 1;
        } else {
            $postData['is_task'] = 0;
        }

        try {
            // Attempt to update the ProjectTasks instance
            $task = ProjectTasks::findOrFail($task->id);
            $task->update($postData);

            if ($task) {
                // Updating project's completed_tasks
                $tasks_completed_by_project = 0;

                $task_details = $this->task_details($task->id);
                $tasks_completed_by_project = $this->tasks_completed_by_project($task_details->project_id);

                // Update project completed_tasks...
                $project_data['complete_tasks'] = $tasks_completed_by_project;

                $update_project = $this->update_project($project_data, $task_details->project_id);
                // End of Updating project's completed_tasks
            }

            // If the creation was successful, redirect with success message
            return redirect()->route('project.pm-project-all-tasks', $project_id)->with('success', localize('data_updated_successfully'));
        } catch (\Exception $e) {
            // If an exception occurs (e.g., validation error, database error), handle it here
            // You can customize the error message based on the type of exception
            return redirect()->back()->withInput()->with('fail', 'Failed to update data: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function employeeProjects($employee_id = null)
    {
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        if ($employee_info != null && $employee_info->id != $employee_id) {
            return redirect()->route('project.employee-projects', $employee_info->id);
        }
        $project_lists = $this->employee_projects($employee_id);
        return view(
            'humanresource::project.tasks.employee_projects',
            compact(
                'project_lists',
            )
        );
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function employeeProjectTasks($project_id = null)
    {
        //store project_id to session for future use like kanban board..
        session(['project_id' => $project_id]);
        $allSessionData = session()->all();

        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();

        $project_info = $this->single_project_data($project_id);
        if ($project_info->is_completed == 1) {
            return redirect()->route('project.employee-project-tasks', $project_id)->with('fail', localize('project_already_closed'));
        }
        // Check, if the request project_id is for the logged in employee/team_member..
        $project_tasks_check = $this->project_task_check_by_employee($project_id, $employee_info->id);
        if (!$project_tasks_check > 0) {
            return redirect()->route('project.employee-project-tasks', $project_id)->with('fail', localize('invalid_request'));
        }

        $all_tasks = $this->employee_project_tasks($project_id, $employee_info->id);
        // this will use as reporter / project_lead, who will lead the project
        $project_leads = $this->project_manager_supervisor_dropdown($project_info->project_lead);
        // available sprints for the project
        $available_sprints = $this->get_sprints($project_id);

        return view(
            'humanresource::project.tasks.project_employee_all_tasks',
            compact(
                'project_info',
                'all_tasks',
                'project_leads',
                'available_sprints',
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function employeeProjectTaskCreate($project_id)
    {
        //store project_id to session for future use like kanban board..
        session(['project_id' => $project_id]);
        $allSessionData = session()->all();

        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();

        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];
        $project_info = ProjectManagement::where('id', $project_id)->first();
        $project_lead = $project_info->project_lead;
        if ($project_info->is_completed == 1) {
            return redirect()->route('project.employee-project-tasks', $project_id)->with('fail', localize('project_already_closed'));
        }
        // Check, if the request project_id is for the logged in employee/team_member..
        $project_tasks_check = $this->project_task_check_by_employee($project_id, $employee_info->id);
        if (!$project_tasks_check > 0) {
            return redirect()->route('project.employee-project-tasks', $project_id)->with('fail', localize('invalid_request'));
        }

        // this will use as reporter / project_lead, who will lead the project
        $project_leads = $this->supervisor_dropdown();
        // this will use as assignee / team_members, who will work on the project
        $team_members = $this->assigned_empdropdown($project_id);
        // available sprints for the project
        $available_sprints = $this->get_sprints($project_id);

        return view(
            'humanresource::project.tasks.create_emp_project_task',
            compact(
                'project_info',
                'project_leads',
                'team_members',
                'available_sprints'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function employeeProjectTaskStore(Request $request, $req_project_id)
    {
        $allSessionData = session()->all();
        $project_id = $sess_project_id = $allSessionData['project_id'];
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        // If anyone tries to change project_id from inspect element, then will catch exception here.
        if ($req_project_id != $project_id) {
            return redirect()->route('project.employee-projects', $employee_info->id)->with('fail', localize('invalid_request'));
        }
        //Check if employee is assigned to this project or not..
        $assigned_project_employee_check = $this->assigned_project_employee_check($project_id, $employee_info->id);
        if (!$assigned_project_employee_check > 0) {
            return redirect()->route('project.employee-projects', $employee_info->id)->with('fail', localize('you_are_not_assigned_to_this_project_anymore'));
        }
        $project_info = ProjectManagement::where('id', $project_id)->first();
        //Total tasks for an individual project is over then not allow to create any further task for the project
        $project_all_backlogs_tasks  = $this->project_all_backlogs_tasks($project_id);
        if ($project_all_backlogs_tasks >= $project_info->approximate_tasks) {
            return redirect()->route('project.employee-projects', $employee_info->id)->with('fail', localize('sorry_your_approximate_tax_limit_is_over'));
        }
        // Check, if the request project_id is for the logged in employee/team_member..
        $project_tasks_check = $this->project_task_check_by_employee($project_id, $employee_info->id);
        if (!$project_tasks_check > 0) {
            return redirect()->route('project.employee-projects', $employee_info->id)->with('fail', localize('invalid_request'));
        }

        $validated = $request->validate([
            'summary'        => 'required',
            'description'    => 'required',
            'priority'       => 'required',
            'sprint_id'      => 'required',
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $request_file = $request->file('attachment');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('attachments', $filename, 'public');
        }

        $postData = [
            'project_id'     => $project_info->id,
            'summary'          => $request->input('summary'),
            'description'   => $request->input('description'),
            'project_lead'  => $project_info->project_lead,
            'employee_id'   => $employee_info->id,
            'sprint_id'       => $request->input('sprint_id'),
            'priority'       => $request->input('priority'),
            'attachment'       => $path,
        ];

        if (!empty($postData['sprint_id'])) {

            $postData['is_task'] = 1;
        } else {
            $postData['is_task'] = 0;
        }

        try {
            // Attempt to create the ProjectTasks instance
            $task = ProjectTasks::create($postData);
            // If the creation was successful, redirect with success message
            return redirect()->route('project.employee-project-tasks', $project_id)->with('success', localize('data_saved_successfully'));
        } catch (\Exception $e) {
            // If an exception occurs (e.g., validation error, database error), handle it here
            // You can customize the error message based on the type of exception
            return redirect()->back()->withInput()->with('fail', 'Failed to save data: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function employeeProjectTaskEdit(ProjectTasks $task)
    {
        $task_data = $task;

        $allSessionData = session()->all();
        $project_id = $sess_project_id = $allSessionData['project_id'];

        // Verify, if this task is associated or not with the project_leader/Reporter..
        $valid_task = $this->valid_task_check($task->id, $project_id);
        // Sprint finished, still trying to access the task to update , then show exception that.. sprint sinished!!!
        $is_sprint_finished = $this->valid_sprint_check($valid_task->sprint_id);

        $project_info = ProjectManagement::where('id', $project_id)->first();
        $project_lead = $project_info->project_lead;

        // this will use as reporter / project_lead, who will lead the project
        $project_leads = $this->supervisor_dropdown();
        // this will use as assignee / team_members, who will work on the project
        $team_members = $this->assigned_empdropdown($project_id);
        // available sprints for the project
        $available_sprints = $this->get_sprints($project_id);

        return view(
            'humanresource::project.tasks.edit_emp_project_task',
            compact(
                'task_data',
                'project_info',
                'project_leads',
                'team_members',
                'available_sprints'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function employeeProjectTaskUpdate(Request $request, ProjectTasks $task)
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];

        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();

        // Verify, if this task is associated or not with the project_leader/Reporter..
        $valid_employee_task = $this->valid_employee_task_check($task->id, $project_id, $employee_info->id);
        if (!$valid_employee_task) {
            return redirect()->route('project.employee-project-tasks', $project_id)->with('fail', localize('you_are_not_associated_with_this_task'));
        }
        // Sprint finished, still trying to access the task to update , then show exception that.. sprint sinished!!!
        $is_sprint_finished = $this->valid_sprint_check($valid_employee_task->sprint_id);
        if ($is_sprint_finished) {
            return redirect()->route('project.employee-project-tasks', $project_id)->with('fail', localize('sprint_already_finished'));
        }
        // If anyone tries to change project_id from inspect element, then will catch exception here.
        if ($task->project_id != $project_id) {
            return redirect()->route('project.employee-projects', $employee_info->id)->with('fail', localize('invalid_request'));
        }
        //Check if employee is assigned to this project or not..
        $assigned_project_employee_check = $this->assigned_project_employee_check($project_id, $employee_info->id);
        if (!$assigned_project_employee_check > 0) {
            return redirect()->route('project.employee-projects', $employee_info->id)->with('fail', localize('you_are_not_associated_with_this_task'));
        }
        // Check, if the task_id is for the logged in employee/team member and existis in the pm_tasks_list for the employee/team member...
        $employee_task_check = $this->employee_task_check($task->id, $employee_info->id);
        if (!$employee_task_check || $employee_info->is_supervisor != 0) {
            return redirect()->route('home', $employee_info->id)->with('fail', localize('invalid_request'));
        }

        $validated = $request->validate([
            'summary'        => 'required',
            'description'    => 'required',
            'priority'       => 'required',
        ]);

        $postData = [
            'task_id'          => $task->id,
            'sprint_id'       => $request->input('sprint_id'),
            'priority'       => $request->input('priority'),
            'task_status'   => $request->input('task_status'),

        ];

        $path = null;
        if ($request->hasFile('attachment')) {
            $request_file = $request->file('attachment');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('attachments', $filename, 'public');
            $postData['attachment'] = $path;
        }

        if (!empty($postData['sprint_id'])) {
            $postData['is_task'] = 1;
        } else {
            $postData['is_task'] = 0;
        }

        try {
            // Attempt to update the ProjectTasks instance
            $task = ProjectTasks::findOrFail($task->id);
            $task->update($postData);

            if ($task) {
                // Updating project's completed_tasks
                $tasks_completed_by_project = 0;

                $task_details = $this->task_details($task->id);
                $tasks_completed_by_project = $this->tasks_completed_by_project($task_details->project_id);

                // Update project completed_tasks...
                $project_data['complete_tasks'] = $tasks_completed_by_project;

                $update_project = $this->update_project($project_data, $task_details->project_id);
                // End of Updating project's completed_tasks
            }

            // If the creation was successful, redirect with success message
            return redirect()->route('project.employee-project-tasks', $project_id)->with('success', localize('data_updated_successfully'));
        } catch (\Exception $e) {
            // If an exception occurs (e.g., validation error, database error), handle it here
            // You can customize the error message based on the type of exception
            return redirect()->back()->withInput()->with('fail', 'Failed to update data: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function projectAllTasks($project_id = null)
    {
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        $project_info = $this->single_project_data($project_id);
        // Check, if the request project_id is for the logged in employee/team_member..
        $project_tasks_check = $this->project_task_check_by_employee($project_id, $employee_info->id);
        if (!$project_tasks_check > 0) {
            return redirect()->route('project.employee-projects', $employee_info->id)->with('fail', localize('invalid_request'));
        }

        $all_tasks = $this->project_tasks($project_id);
        return view(
            'humanresource::project.tasks.project_all_tasks',
            compact(
                'project_info',
                'all_tasks',
            )
        );
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function projectSprints($project_id = null)
    {
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        if (!$employee_info) {
            // isAdmin, if admin then employee data will not be available in employees table.
            // isAdmin user
            //store project_id to session for future use like kanban board..
            session(['admin_sprint_project_id' => $project_id]);
            $allSessionData = session()->all();

            $sprints_lists = $this->all_sprints($project_id);
            return view(
                'humanresource::project.tasks.sprint_list',
                compact(
                    'sprints_lists',
                )
            );
        } else {
            return redirect()->route('project.manage-tasks')->with('fail', localize('invalid_request'));
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function sprintTasks($sprint_id = null)
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['admin_sprint_project_id'];

        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        $project_info = $this->single_project_data($project_id);
        if ($employee_info != null) {
            return redirect()->route('project.project-sprints', $project_id)->with('fail', localize('invalid_request'));
        }
        // Check, if the request sprint_id is for the sprint_project...
        $sprint_check_by_project = $this->sprint_check_by_project($project_id, $sprint_id);
        if (!$sprint_check_by_project) {
            return redirect()->route('project.project-sprints', $project_id)->with('fail', localize('if_open_another_project_in_different_tab_then_needs_to_close_that_first'));
        }
        // Check if the sprint is finished or not, if finish then redirect and say that, sprint already finished
        $sprint_check_resp = $this->sprint_check($project_id, $sprint_id);
        if (!$sprint_check_resp) {
            return redirect()->route('project.project-sprints', $project_id)->with('fail', localize('sprint_already_finished'));
        }

        $sprints_tasks = $this->sprint_tasks($sprint_id);
        return view(
            'humanresource::project.tasks.sprint_tasks',
            compact(
                'project_info',
                'sprints_tasks',
            )
        );
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function pmProjectSprints($project_id = null)
    {
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();

        // Check, if the request project_id is for the logged in supervisor/project_lead and existis in the project_lists for the supervisor...
        $project_lead_check = $this->project_lead_check($project_id, $employee_info->id);
        if (!$project_lead_check) {
            return redirect()->route('project.projects-lead-tasks', $employee_info->id)->with('fail', localize('invalid_request'));
        }

        //store project_id to session for future use like kanban board..
        session(['sprint_project_id' => $project_id]);
        $allSessionData = session()->all();

        $sprint_lists  = $this->all_sprints($project_id);
        return view(
            'humanresource::project.tasks.pm_sprints_list',
            compact(
                'sprint_lists',
            )
        );
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function pmSprintTasks($sprint_id = null)
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['sprint_project_id'];

        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        $project_info = $this->single_project_data($project_id);
        // Check, if the request sprint_id is for the sprint_project...
        $sprint_check_by_project = $this->sprint_check_by_project($project_id, $sprint_id);
        if (!$sprint_check_by_project) {
            return redirect()->route('project.pm-project-sprints', $project_id)->with('fail', localize('invalid_request'));
        }
        // Check if the sprint is finished or not, if finish then redirect and say that, sprint already finished
        $sprint_check_resp = $this->sprint_check($project_id, $sprint_id);
        if (!$sprint_check_resp) {
            return redirect()->route('project.pm-project-sprints', $project_id)->with('fail', localize('sprint_already_finished'));
        }

        $sprints_tasks = $this->sprint_tasks($sprint_id);
        return view(
            'humanresource::project.tasks.pm_sprints_tasks',
            compact(
                'sprint_id',
                'project_info',
                'sprints_tasks',
            )
        );
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function pmKanbanBoard($sprint_id = null)
    {
        $allSessionData = session()->all();
        $sprint_project_id = $allSessionData['sprint_project_id'];

        // Check, if the request sprint_id is for the sprint_project or sprint closed then redirect to pm_project_sprints page
        $sprint_check_by_project = $this->sprint_check_by_project($sprint_project_id, $sprint_id);
        if (!$sprint_check_by_project) {
            return redirect()->route('project.pm-project-sprints', $sprint_project_id)->with('fail', localize('invalid_request'));
        } elseif ($sprint_check_by_project->is_finished == 1) {
            return redirect()->route('project.pm-project-sprints', $sprint_project_id)->with('fail', localize('sprint_already_closed'));
        }

        // Task Status
        $statusResult = array(
            array("id" => 1, "status_name" => localize('to_do')),
            array("id" => 2, "status_name" => localize('in_progress')),
            array("id" => 3, "status_name" => localize('done'))
        );

        $project_sprint = ProjectSprints::where('project_id', $sprint_project_id)->where('id', $sprint_id)->where('is_finished', 0)->first();
        return view(
            'humanresource::project.tasks.pm_kanban_board',
            compact(
                'sprint_id',
                'project_sprint',
                'statusResult',
            )
        );
    }

    public function kanbanTaskUpdate(Request $request)
    {
        $task_status = $request->query('task_status');
        $task_id = $request->query('task_id');

        // Check, if the request sprint_id is for the sprint_project...
        $update_task_kanban = $this->update_task_from_kanban($task_id, $task_status);
        if ($update_task_kanban) {

            $tasks_completed_by_project = 0;

            $task_details = $this->task_details($task_id);
            $tasks_completed_by_project = $this->tasks_completed_by_project($task_details->project_id);

            // Update project completed_tasks...
            $project_id = $task_details->project_id;
            $data['complete_tasks'] = $tasks_completed_by_project;

            $update_project = $this->update_project($data, $project_id);

            echo json_encode($update_project);
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function emplProjectSprints($project_id = null)
    {
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();

        // Check, if the request project_id is for the logged in employee/team_member and existis in the pm_tasks_list for the employee_id...
        $project_employee_check = $this->project_employee_check($project_id, $employee_info->id);
        // If greater than 0 , means the employee/team_member is associated with the project..
        if (!$project_employee_check > 0) {
            return redirect()->route('project.employee-projects', $employee_info->id)->with('fail', localize('invalid_request'));
        }

        //store project_id to session for future use like kanban board..
        session(['sprint_project_id' => $project_id]);
        $allSessionData = session()->all();

        $sprint_lists  = $this->all_sprints($project_id);
        return view(
            'humanresource::project.employee_tasks.employee_sprints_list',
            compact(
                'sprint_lists',
            )
        );
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function emplSprintOwnTasks($sprint_id = null)
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['sprint_project_id'];

        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        $project_info = $this->single_project_data($project_id);
        // Check, if the request sprint_id is for the logged in employee/team_member..
        $sprint_tasks_by_employee = $all_tasks = $this->sprint_tasks_by_employee($project_id, $employee_info->id, $sprint_id);
        if (!count($sprint_tasks_by_employee) > 0) {
            return redirect()->route('project.empl-project-sprints', $project_id)->with('fail', localize('you_have_no_tasks_for_this_sprint'));
        }
        // Check, if the request sprint is closed or open?..
        $single_sprint_data = ProjectSprints::where('id', $sprint_id)->first();
        if ($single_sprint_data->is_finished) {
            return redirect()->route('project.empl-project-sprints', $project_id)->with('fail', localize('sprint_already_finished'));
        }

        return view(
            'humanresource::project.employee_tasks.employee_sprint_tasks',
            compact(
                'sprint_id',
                'project_info',
                'all_tasks',
            )
        );
    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function emplKanbanBoard($sprint_id = null)
    {
        $allSessionData = session()->all();
        $sprint_project_id = $allSessionData['sprint_project_id'];

        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        $project_info = $this->single_project_data($sprint_project_id);
        $employee_id = $employee_info->id;
        $project_id = $sprint_project_id;

        // Check, if the request sprint_id is for the logged in employee/team_member..
        $sprint_tasks_by_employee = $this->sprint_tasks_by_employee($sprint_project_id, $employee_info->id, $sprint_id);
        if (!count($sprint_tasks_by_employee) > 0) {
            return redirect()->route('project.empl-project-sprints', $sprint_project_id)->with('fail', localize('you_have_no_tasks_for_this_sprint'));
        }
        // Task Status
        $statusResult = array(
            array("id" => 1, "status_name" => localize('to_do')),
            array("id" => 2, "status_name" => localize('in_progress')),
            array("id" => 3, "status_name" => localize('done'))
        );

        $project_sprint = ProjectSprints::where('project_id', $sprint_project_id)->where('id', $sprint_id)->where('is_finished', 0)->first();
        return view(
            'humanresource::project.employee_tasks.empl_kanban_board',
            compact(
                'employee_id',
                'project_id',
                'sprint_id',
                'project_sprint',
                'statusResult',
            )
        );
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function emplSprintAllTasks($sprint_id = null)
    {
        $allSessionData = session()->all();
        $sprint_project_id = $allSessionData['sprint_project_id'];

        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        $project_info = $this->single_project_data($sprint_project_id);
        $employee_id = $employee_info->id;
        $project_id = $sprint_project_id;

        // Check, if the request sprint_id is for the logged in employee/team_member..
        $sprint_all_tasks = $all_tasks = $this->sprint_all_tasks($sprint_project_id, $sprint_id);
        if (!count($sprint_all_tasks) > 0) {
            return redirect()->route('project.empl-project-sprints', $project_id)->with('fail', localize('no_tasks_available_for_this_sprint'));
        }

        return view(
            'humanresource::project.employee_tasks.empl_sprint_all_tasks',
            compact(
                'sprint_id',
                'project_info',
                'all_tasks',
            )
        );
    }

    public function sprint_all_tasks($project_id = null, $sprint_id = null)
    {
        return ProjectTasks::select('pm_tasks_list.*', 'e.first_name', 'e.last_name', 'epid.first_name as ep_firstname', 'epid.last_name as ep_lastname', 'p.project_name', 'pms.sprint_name', 'pms.is_finished as sprint_status')
            ->leftJoin('pm_projects as p', 'pm_tasks_list.project_id', '=', 'p.id')
            ->leftJoin('pm_sprints as pms', 'pm_tasks_list.sprint_id', '=', 'pms.id')
            ->leftJoin('employees as e', 'pm_tasks_list.project_lead', '=', 'e.id')
            ->leftJoin('employees as epid', 'pm_tasks_list.employee_id', '=', 'epid.id')
            ->where('pm_tasks_list.project_id', $project_id)
            ->where('pm_tasks_list.sprint_id', $sprint_id)
            ->orderByDesc('pm_tasks_list.id')
            ->get();
    }

    public function sprint_tasks_by_employee($project_id = null, $employee_id = null, $sprint_id = null)
    {
        return ProjectTasks::select('pm_tasks_list.*', 'e.first_name', 'e.last_name', 'epid.first_name as ep_firstname', 'epid.last_name as ep_lastname', 'p.project_name', 'pms.sprint_name', 'pms.is_finished as sprint_status', 'u.full_name as task_creator_user_name')
            ->leftJoin('pm_projects as p', 'pm_tasks_list.project_id', '=', 'p.id')
            ->leftJoin('pm_sprints as pms', 'pm_tasks_list.sprint_id', '=', 'pms.id')
            ->leftJoin('employees as e', 'pm_tasks_list.project_lead', '=', 'e.id')
            ->leftJoin('employees as epid', 'pm_tasks_list.employee_id', '=', 'epid.id')
            ->leftJoin('users as u', 'pm_tasks_list.created_by', '=', 'u.id')
            ->where('pm_tasks_list.project_id', $project_id)
            ->where('pm_tasks_list.employee_id', $employee_id)
            ->where('pm_tasks_list.sprint_id', $sprint_id)
            ->orderByDesc('pm_tasks_list.id')
            ->get();
    }

    public function project_employee_check($project_id = null, $employee_id = null)
    {
        return ProjectTasks::where('project_id', $project_id)
            ->where('employee_id', $employee_id)
            ->count();
    }

    // update_task_from_pm_kanban board
    public function update_task_from_kanban($task_id = null, $task_status = null)
    {
        $data['task_status'] = $task_status;

        $tasUpKanban = ProjectTasks::findOrFail($task_id);
        $up_res = $tasUpKanban->update($data);

        return $up_res;
    }

    public function sprint_tasks($sprint_id)
    {
        return ProjectTasks::select('pm_tasks_list.*', 'e.first_name', 'e.last_name', 'epid.first_name as ep_firstname', 'epid.last_name as ep_lastname', 'p.project_name', 'pms.sprint_name')
            ->leftJoin('pm_projects as p', 'pm_tasks_list.project_id', '=', 'p.id')
            ->leftJoin('pm_sprints as pms', 'pm_tasks_list.sprint_id', '=', 'pms.id')
            ->leftJoin('employees as e', 'pm_tasks_list.project_lead', '=', 'e.id')
            ->leftJoin('employees as epid', 'pm_tasks_list.employee_id', '=', 'epid.id')
            ->where('pm_tasks_list.sprint_id', $sprint_id)
            ->orderByDesc('pm_tasks_list.id')
            ->get();
    }

    // Check sprint, when transferring tasks from sprint to backlogs
    public function sprint_check($project_id = null, $sprint_id = null)
    {
        return ProjectSprints::where('project_id', $project_id)
            ->where('id', $sprint_id)
            ->where('is_finished', 0)
            ->first();
    }

    // Check sprint, when transferring tasks from sprint to backlogs
    public function sprint_check_by_project($project_id = null, $sprint_id = null)
    {
        return ProjectSprints::where('project_id', $project_id)
            ->where('id', $sprint_id)
            ->first();
    }

    public function all_sprints($project_id)
    {
        return  ProjectSprints::select('pm_sprints.*', 'p.project_name')
            ->leftJoin('pm_projects as p', 'pm_sprints.project_id', '=', 'p.id')
            ->where('pm_sprints.project_id', $project_id)
            ->orderByDesc('pm_sprints.id')
            ->get();
    }

    public function employee_task_check($task_id = null, $employee_id = null)
    {
        return ProjectTasks::where('id', $task_id)
            ->where('employee_id', $employee_id)
            ->first();
    }

    public function valid_employee_task_check($task_id = null, $project_id = null, $employee_id = null)
    {

        return ProjectTasks::where('id', $task_id)
            ->where('project_id', $project_id)
            ->where('employee_id', $employee_id)
            ->first();
    }

    public function assigned_project_employee_check($project_id = null, $employee_id = null)
    {
        return ProjectEmployees::where('project_id', $project_id)
            ->where('employee_id', $employee_id)
            ->count();
    }

    public function employee_project_tasks($project_id = null, $employee_id = null)
    {
        return ProjectTasks::select('pm_tasks_list.*', 'e.first_name', 'e.last_name', 'epid.first_name as ep_firstname', 'epid.last_name as ep_lastname', 'p.project_name', 'pms.sprint_name', 'pms.is_finished as sprint_status', 'u.full_name as task_creator_user_name')
            ->leftJoin('pm_projects as p', 'pm_tasks_list.project_id', '=', 'p.id')
            ->leftJoin('pm_sprints as pms', 'pm_tasks_list.sprint_id', '=', 'pms.id')
            ->leftJoin('employees as e', 'pm_tasks_list.project_lead', '=', 'e.id')
            ->leftJoin('employees as epid', 'pm_tasks_list.employee_id', '=', 'epid.id')
            ->leftJoin('users as u', 'pm_tasks_list.created_by', '=', 'u.id')
            ->where('pm_tasks_list.project_id', $project_id)
            ->where('pm_tasks_list.employee_id', $employee_id)
            ->where('pm_tasks_list.sprint_id', '>', 0)
            ->orderByDesc('pm_tasks_list.id')
            ->get();
    }

    public function project_task_check_by_employee($project_id = null, $employee_id = null)
    {
        return ProjectTasks::where('project_id', $project_id)
            ->where('employee_id', $employee_id)
            ->count();
    }

    public function employee_projects($team_member_id)
    {

        $arr_result = [];
        $projects_info = [];

        $queryResult = DB::table('pm_tasks_list')
            ->select('project_id')
            ->where('employee_id', $team_member_id)
            ->distinct()
            ->orderByDesc('project_id')
            ->get();

        foreach ($queryResult as $row) {

            $arr_result[] = $row->project_id;

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
        return ProjectManagement::select('pm_projects.*', 'e.first_name', 'e.last_name', 'pmc.client_name')
            ->leftJoin('employees as e', 'pm_projects.project_lead', '=', 'e.id')
            ->leftJoin('pm_clients as pmc', 'pm_projects.client_id', '=', 'pmc.id')
            ->where('pm_projects.id', $project_id)
            ->first();
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
                $list[$value->id] = $value->full_name;
            }
        }
        return $list;
    }

    public function project_lead_check($project_id = null, $project_lead_id = null)
    {
        return ProjectManagement::where('id', $project_id)
            ->where('project_lead', $project_lead_id)
            ->first();
    }

    public function projects_lead_projects($projects_lead_id = null)
    {
        return ProjectManagement::select('pm_projects.*', 'e.first_name', 'e.last_name', 'pmc.client_name')
            ->leftJoin('employees as e', 'pm_projects.project_lead', '=', 'e.id')
            ->leftJoin('pm_clients as pmc', 'pm_projects.client_id', '=', 'pmc.id')
            ->where('pm_projects.project_lead', $projects_lead_id)
            ->get();
    }

    public function update_project($data = [], $project_id = null)
    {
        $project = ProjectManagement::findOrFail($project_id);
        $up_res = $project->update($data);
    }

    // update_task_from_pm_kanban board
    public function tasks_completed_by_project($project_id = null)
    {
        return ProjectTasks::where('project_id', $project_id)->where('task_status', 3)->count();
    }

    // update_task_from_pm_kanban board
    public function task_details($task_id = null)
    {
        return ProjectTasks::where('id', $task_id)->first();
    }

    public function project_task_check($task_id = null, $project_lead_id = null)
    {
        return ProjectTasks::where('id', $task_id)->where('project_lead', $project_lead_id)->first();
    }

    public function valid_sprint_check($sprint_id = null)
    {
        return $result = ProjectSprints::where('id', $sprint_id)->where('is_finished', 1)->first();
    }

    public function valid_task_check($task_id = null, $project_id = null)
    {
        return ProjectTasks::where('id', $task_id)->where('project_id', $project_id)->first();
    }

    public function single_project_data($project_id)
    {

        return ProjectManagement::where('id', $project_id)->first();
    }

    public function project_tasks($project_id)
    {
        return $result = ProjectTasks::select(
            'pm_tasks_list.*',
            'e.first_name',
            'e.last_name',
            'epid.first_name as ep_firstname',
            'epid.last_name as ep_lastname',
            'p.project_name',
            'pms.sprint_name',
            'pms.is_finished as sprint_status',
            'u.full_name as task_creator_user_name',
        )
            ->leftJoin('pm_projects as p', 'pm_tasks_list.project_id', '=', 'p.id')
            ->leftJoin('pm_sprints as pms', 'pm_tasks_list.sprint_id', '=', 'pms.id')
            ->leftJoin('employees as e', 'pm_tasks_list.project_lead', '=', 'e.id')
            ->leftJoin('employees as epid', 'pm_tasks_list.employee_id', '=', 'epid.id')
            ->leftJoin('users as u', 'pm_tasks_list.created_by', '=', 'u.id')
            ->where('pm_tasks_list.project_id', $project_id)
            ->orderByDesc('pm_tasks_list.id')
            ->get();

        return $result;
    }

    /*Get all projects*/
    public function all_projects()
    {
        return ProjectManagement::select('pm_projects.*', 'e.first_name', 'e.last_name', 'pmc.client_name')
            ->leftJoin('employees as e', 'pm_projects.project_lead', '=', 'e.id')
            ->leftJoin('pm_clients as pmc', 'pm_projects.client_id', '=', 'pmc.id')
            ->orderByDesc('pm_projects.id')
            ->get();
    }

    // When transferring from backlogs.. backlog tasks will be updated as only tasks along with selected sprint_id and will vanish from backlog
    public function update_task_from_backlogs($data = [], $task_id = null)
    {
        // Attempt to update the ProjectTasks instance
        $backlog_task = ProjectTasks::findOrFail($task_id);
        $resp = $backlog_task->update($data);

        return $resp;
    }

    // Get all backlogs task where is_task = 0 and must match the project_id
    public function all_backlogs_tasks($project_id = null)
    {
        $tasks = ProjectTasks::select('pm_tasks_list.*', 'e.first_name as team_mem_firstname', 'e.last_name as team_mem_lastname', 'p.project_name', 'ep.first_name as proj_lead_firstname', 'ep.last_name as proj_lead_lastname')
            ->leftJoin('pm_projects as p', 'pm_tasks_list.project_id', '=', 'p.id')
            ->leftJoin('employees as ep', 'pm_tasks_list.project_lead', '=', 'ep.id')
            ->leftJoin('employees as e', 'pm_tasks_list.employee_id', '=', 'e.id')
            ->where('pm_tasks_list.project_id', $project_id)
            ->where('pm_tasks_list.is_task', 0)
            ->orderBy('pm_tasks_list.priority', 'desc')
            ->orderBy('pm_tasks_list.id', 'desc')
            ->get();

        return $tasks;
    }

    public function valid_backlog_task($task_id = null, $project_id = null)
    {
        return ProjectTasks::where('id', $task_id)->where('project_id', $project_id)->where('is_task', 0)->first();
    }

    //Total tasks along with backlogs for an individual project
    public function project_all_backlogs_tasks($project_id = null)
    {
        return ProjectTasks::where('project_id', $project_id)->where('is_task', 0)->count();
    }

    /*Get sprint*/
    public function get_sprints($project_id = null)
    {
        $result = ProjectSprints::where('project_id', $project_id)->where('is_finished', 0)->get();
        $list = array('' => 'Select One...');
        if (!empty($result)) {
            foreach ($result as $value) {
                $list[$value->id] = $value->sprint_name;
            }
        }
        return $list;
    }

    /*Getting employee who are not created as supervisor*/
    public function assigned_empdropdown($project_id)
    {
        $team_members = ProjectEmployees::where('project_id', $project_id)->get();

        $list = array('' => 'Select One...');
        if (!empty($team_members)) {

            foreach ($team_members as $value) {
                $data = Employee::where('id', $value->employee_id)->where('is_active', 1)->where('is_supervisor', 0)->first();
                if ($data) {

                    $list[$data->id] = $data->full_name;
                }
            }
        }
        return $list;
    }

    /*Getting employee who are created as supervisor*/
    public function supervisor_dropdown()
    {
        $data = Employee::where('is_active', 1)->where('is_supervisor', 1)->get();

        $list = array('' => 'Select One...');
        if (!empty($data)) {
            foreach ($data as $value) {
                $list[$value->id] = $value->full_name;
            }
        }
        return $list;
    }

    // Get all backlogs task where is_task = 0 and must match the project_id
    public function all_backlogs($project_id = null)
    {
        $tasks = ProjectTasks::select('pm_tasks_list.*', 'e.first_name as team_mem_firstname', 'e.last_name as team_mem_lastname', 'p.project_name', 'ep.first_name as proj_lead_firstname', 'ep.last_name as proj_lead_lastname')
            ->leftJoin('pm_projects as p', 'pm_tasks_list.project_id', '=', 'p.id')
            ->leftJoin('employees as ep', 'pm_tasks_list.project_lead', '=', 'ep.id')
            ->leftJoin('employees as e', 'pm_tasks_list.employee_id', '=', 'e.id')
            ->leftJoin('pm_sprints as pms', 'pm_tasks_list.sprint_id', '=', 'pms.id')
            ->where('pm_tasks_list.project_id', (int)$project_id)
            ->where('pm_tasks_list.is_task', 0)
            ->orderBy('pm_tasks_list.id', 'desc')
            ->get();

        return $tasks;
    }

    // Get all previues version project backlogs task where is_task = 0 and must match the project_id
    public function total_previous_project_backlogs($project_id = null)
    {
        return ProjectTasks::where('project_id', $project_id)->where('is_task', 0)->count();
    }

    public function verify_project($project_id = null, $project_lead = null)
    {
        return ProjectManagement::where('id', $project_id)->where('project_lead', $project_lead)->first();
    }
}
