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

class ProjectManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_clients')->only(['index']);
        $this->middleware('permission:create_clients', ['only' => ['create','store']]);
        $this->middleware('permission:update_clients', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_clients', ['only' => ['destroy']]);

        $this->middleware('permission:read_projects', ['only' => ['projectLists']]);
        $this->middleware('permission:create_projects', ['only' => ['projectCreate','projectStore']]);
        $this->middleware('permission:update_projects', ['only' => ['projectEdit', 'projectUpdate']]);
        $this->middleware('permission:delete_projects', ['only' => ['employeePoints']]);

        $this->middleware('permission:read_task', ['only' => ['projectLists']]);
        $this->middleware('permission:create_task', ['only' => ['projectCreate','projectStore']]);
        $this->middleware('permission:update_task', ['only' => ['projectEdit', 'projectUpdate']]);
        $this->middleware('permission:delete_task', ['only' => ['employeePoints']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $dbData = ProjectClients::with('countryDetail')->get();
        return view('humanresource::project.clients.index', compact('dbData'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $countries = Country::all();
        return view('humanresource::project.clients.create_client', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name'  => 'required|unique:pm_clients',
            'country'      => 'required',
            'email'        => 'required|unique:pm_clients',
            'company_name' => 'required',
            'address'      => 'required',
        ]);

        try {
            // Attempt to create the ProjectClients instance
            $projectClient = ProjectClients::create($validated);
        
            // If the creation was successful, redirect with success message
            return redirect()->route('project.index')->with('success', localize('data_saved_successfully'));
        } catch (\Exception $e) {
            // If an exception occurs (e.g., validation error, database error), handle it here
            // You can customize the error message based on the type of exception
            return redirect()->back()->withInput()->with('fail', 'Failed to save data: ' . $e->getMessage());
        }
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
    public function edit(ProjectClients $client)
    {
        $countries = Country::all();
        return view('humanresource::project.clients.edit_client', compact('countries' ,'client'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $clientId)
    {
        $validated = $request->validate([
            'client_name'  => [
                'required',
                Rule::unique('pm_clients')->ignore($clientId),
            ],
            'country'      => 'required',
            'email'        => [
                'required',
                Rule::unique('pm_clients')->ignore($clientId),
            ],
            'company_name' => 'required',
            'address'      => 'required',
        ]);

        try {
           // Update the clients data
            $client = ProjectClients::findOrFail($clientId);
            $client->update($validated);
        
            // If the creation was successful, redirect with success message
            return redirect()->route('project.index')->with('success', localize('data_updated_successfully'));
        } catch (\Exception $e) {
            // If an exception occurs (e.g., validation error, database error), handle it here
            // You can customize the error message based on the type of exception
            return redirect()->back()->withInput()->with('fail', 'Failed to update data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(ProjectClients $client)
    {
        $client->delete();
        Toastr::success('Message Deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function projectLists()
    {
        $login_user_id = Auth::id();
        $userInfo = Auth::user();

        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();

        if($userInfo->user_type_id == 2){
            // Get employee projects id
            $project_ids = ProjectTasks::select('project_id')
            ->where('employee_id', $employee_info->id)
            ->groupBy('project_id')
            ->get()
            ->pluck('project_id')
            ->toArray();

            $projects = ProjectManagement::with('clientDetail', 'projectLead')
                ->whereIn('id', $project_ids)
                ->get();

            return view('humanresource::project.projects.index', compact('projects', 'employee_info'));
        }
        
        $projects = ProjectManagement::with('clientDetail', 'projectLead')->get();

        return view('humanresource::project.projects.index', compact('projects', 'employee_info'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function projectCreate()
    {
        $clients       = ProjectClients::all();
        $project_leads = Employee::where('is_active', 1)->where('is_supervisor', 1)->get();
        $team_members  = Employee::where('is_active', 1)->where('is_supervisor', 0)->get();

        // Versions for projects
        $version_projects = $this->versionProjects();
        return view('humanresource::project.projects.create_project', 
            compact(
                'clients',
                'project_leads',
                'team_members',
                'version_projects'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function projectStore(Request $request)
    {
        $validated = $request->validate([
            'project_name'         => 'required|unique:pm_projects',
            'client_id'            => 'required',
            'project_lead'         => 'required',
            'approximate_tasks'    => 'required',
            'project_summary'      => 'required',
            'project_start_date'   => 'required',
            'project_duration'     => 'required',
            'project_reward_point' => 'required',
        ]);

        // If previous version taken as input
        if($request->input('previous_version')){
            $previous_version_info = $this->previousVersionManage($request->input('previous_version'));
            if($previous_version_info['status']){
                
                $validated['first_parent_project_id']  = $previous_version_info['first_parent_project_id'];
                $validated['second_parent_project_id'] = $previous_version_info['second_parent_project_id'];
                $validated['version_no']               = $previous_version_info['version_no'];
            }else{
                return redirect()->back()->withInput()->with('fail', localize($previous_version_info['msg']));
            }
        }

        // Validate team_members separately
        $teamMembersValidated = $request->validate([
            'team_members' => 'required',
        ]);

        try {
            // Attempt to create the ProjectManagement instance
            $project = ProjectManagement::create($validated);

            if($project){
                $team_members = $request->input('team_members');

                $teamMembersData = [];
                foreach ($team_members as $team_member) {
                    $teamMembersData[] = [
                        'project_id' => $project->id,
                        'employee_id' => $team_member,
                    ];
                }

                // Perform batch insert
                ProjectEmployees::insert($teamMembersData);
            }
        
            // If the creation was successful, redirect with success message
            return redirect()->route('project.project-lists')->with('success', localize('data_saved_successfully'));

        } catch (\Exception $e) {
            // If an exception occurs (e.g., validation error, database error), handle it here
            // You can customize the error message based on the type of exception
            return redirect()->back()->withInput()->with('fail', 'Failed to save data: ' . $e->getMessage());
        }
    }

    //Get projects for version creation and backlogs task transfer
    public function versionProjects(){

        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->where('is_supervisor', 1)->first();
        if($employee_info){
            // Enable query logging
            return ProjectManagement::where('project_lead', $employee_info->id?$employee_info->id:null)->where('is_completed', 1)->get();
        }
        return [];
    }

    // Previous version project handling, also keeping record of previous version for the new created project.
    public function previousVersionManage($project_id){

        $first_parent_project_id  = 0;
        $second_parent_project_id = 0;
        $version_no   			  = 1;

        $previous_version_details = ProjectManagement::where('id', $project_id)->first();

        // return $previous_version_details;

        if($previous_version_details->is_completed != 1){
            return $resp_arr = [
                'status' => false,
                'msg' => localize('the_selected_previous_version_project_must_be_completed '),
            ];
        }else{

            // if not selected the first version of any project , then the selected project_id will be the second_parent_project_id and first_parent_project_id of the selected project will be the first_parent_project_id of new project.
            if($previous_version_details->first_parent_project_id > 0){

                $version_count = ProjectManagement::where('id', $previous_version_details->first_parent_project_id)
                                ->orWhere('first_parent_project_id', $previous_version_details->first_parent_project_id)->count();


                $first_parent_project_id  = $previous_version_details->first_parent_project_id;
                $second_parent_project_id = $previous_version_details->id;
                $version_no   			  = (int)$version_count + 1;

            }else{

                // if selected the first version of any project , then the selected project_id will be taken for the first_parent_project_id and second_parent_project_id for new version of the newly created project.
                $version_count = ProjectManagement::where('id', $previous_version_details->id)
                                ->orWhere('first_parent_project_id', $previous_version_details->id)->count();

                $first_parent_project_id  = $previous_version_details->id;
                $second_parent_project_id = $previous_version_details->id;
                $version_no   			  = (int)$version_count + 1;

            }
        }
        return $resp_arr = [
            'status'                   => true,
            'first_parent_project_id'  => $first_parent_project_id,
            'second_parent_project_id' => $second_parent_project_id,
            'version_no'               => $version_no,
        ];
    }

   /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function projectEdit(ProjectManagement $project)
    {
        // Employee Info
        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        $supervisor    = Employee::where('user_id', $login_user_id)->where('is_active', 1)->where('is_supervisor', 1)->first();

        $clients                = ProjectClients::all();
        $project_leads          = Employee::where('is_active', 1)->where('is_supervisor', 1)->get();
        $team_members           = Employee::where('is_active', 1)->where('is_supervisor', 0)->get();
        $existing_team_members  = ProjectEmployees::where('project_id', $project->id)->pluck('employee_id')->toArray();
        $project_sprints_count  = $this->project_sprints_count($project->id);

        $project_lead_check = true;
        if($employee_info && $supervisor){
            $project_lead_check = $this->project_lead_check($project->id, $supervisor->id);
            if(!$project_lead_check){
                // Redirect with fail message
                $project_lead_check = false;
            }
        }
        return view('humanresource::project.projects.edit_project', 
            compact(
                'clients',
                'project_leads',
                'team_members',
                'project',
                'existing_team_members',
                'project_sprints_count',
                'project_lead_check'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function projectUpdate(Request $request, $projectId)
    {
        $project = ProjectManagement::findOrFail($projectId);

        if($project->is_completed == 1){
            return redirect()->route('project.project-lists')->with('fail', localize('project_is_already_closed'));
        }

        $validated = $request->validate([
            'project_name'  => [
                'required',
                Rule::unique('pm_projects')->ignore($projectId),
            ],
            'client_id'            => 'required',
            'project_lead'         => 'required',
            'approximate_tasks'    => 'required',
            'project_summary'      => 'required',
            'project_start_date'   => 'required',
            'project_duration'     => 'required',
            'project_reward_point' => 'required',
        ]);

        $project_status = $request->input('is_completed');
        $project_lead   = $request->input('project_lead');

        // if project_status is 1 , means requesting for closing the project.
        if($project_status == 1){
            
            // Check if sprint finished/closed or not
            $sprint_not_finished = $this->sprint_not_finished($project->id);
            if($sprint_not_finished){
                return redirect()->route('project.project-lists')->with('fail', $sprint_not_finished->sprint_name." is not closed yet, please close the sprint first.");
            }

            //when closing project, then also count all_tasks as approximate_tasks, completed_tasks and update the project in database.
            $all_tasks_by_project = 0;
            $tasks_completed_by_project = 0;
            
            $all_tasks_by_project       = $this->all_tasks_by_project($project->id);
            $tasks_completed_by_project = $this->tasks_completed_by_project($project->id);

            $validated['approximate_tasks'] = $all_tasks_by_project;
            $validated['complete_tasks'] 	= $tasks_completed_by_project;
            $validated['is_completed'] 	    = 1;
            $validated['close_date'] 	    = date("Y-m-d");
        }

        //if there already associated sprints or tasks  with the project, then not allow to update Project Manager/Project Lead.. 
        $project_sprints_count = 0;
        $project_associated_tasks = 0;

        if($project_lead != $project->project_lead){

            $project_sprints_count = $this->project_sprints_count($project->id);
            $project_associated_tasks = $this->project_associated_tasks($project->id);

            if($project_sprints_count > 0 || $project_associated_tasks > 0){

                return redirect()->route('project.project-lists')->with('fail', localize('can_not_update_project_lead_as_this_project_already_associated_with_sprint_and_tasks'));
            }
        }

        try {
            // Update the clients data
            $project->update($validated);

            if($project){
                $team_members = $request->input('team_members');

                $teamMembersData = [];
                foreach ($team_members as $team_member) {
                    $teamMembersData[] = [
                        'project_id' => $project->id,
                        'employee_id' => $team_member,
                    ];
                }

                // Delete records from ProjectEmployees table where project_id matches
                ProjectEmployees::where('project_id', $project->id)->delete();
                // Perform batch insert
                ProjectEmployees::insert($teamMembersData);
            }
        
            // If the creation was successful, redirect with success message
            return redirect()->route('project.project-lists')->with('success', localize('data_updated_successfully'));
        } catch (\Exception $e) {
            // If an exception occurs (e.g., validation error, database error), handle it here
            // You can customize the error message based on the type of exception
            return redirect()->back()->withInput()->with('fail', 'Failed to update data: ' . $e->getMessage());
        }
    }

    //Total tasks for an individual project .. elminating backlogs tasks
    public function project_associated_tasks($project_id = null){

        return ProjectTasks::where('project_id', $project_id)->where('is_task',1)->count();
 
    }

    // update_task_from_pm_kanban board
    public function tasks_completed_by_project($project_id = null)
    {
        return ProjectTasks::where('project_id', $project_id)->where('task_status',3)->count();
    }

    // update_task_from_pm_kanban board
    public function all_tasks_by_project($project_id = null)
    {
        return ProjectTasks::where('project_id', $project_id)->count();
    }

    // Check if running sprint still not finished..
    public function sprint_not_finished($project_id = null)
    {

        return ProjectSprints::where('project_id', $project_id)->where('is_finished', 0)->first();
    }

    public function project_sprints_count($project_id = null)
    {
        return ProjectSprints::where('project_id', $project_id)->count();
    }

    public function project_lead_check($project_id = null, $supervisor = null)
    {
        return ProjectManagement::where('id', $project_id)->where('project_lead', $supervisor)->count();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function projectDestroy(ProjectManagement $project)
    {
        // Check if this project is associated with any sprints/tasks
		$project_sprints_count = $this->project_sprints_count($project->id);
	    $project_associated_tasks = $this->project_associated_tasks($project->id);

	    if($project_sprints_count > 0 || $project_associated_tasks > 0){

            Toastr::error("Can't be deleted, as the project already associated with sprints or tasks !", 'Errors');
            return response()->json(['error' => 'error']);
	    }

	    // Here starts the condition to delete the project.
		if ($project->delete()) {

			 // Delete records from ProjectEmployees table where project_id matches
             ProjectEmployees::where('project_id', $project->id)->delete();

            Toastr::success('Deleted successfully :)', 'Success');
            return response()->json(['success' => 'success']);

		} else {

			Toastr::success('Something went wrong :)', 'Errors');
            return response()->json(['error' => 'error']);
		}
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function getBacklogs(Request $request)
    {
        // Putting data into session
        session(['project_id' => $request->input('project_id')]);
        $allSessionData = session()->all();

        echo json_encode($allSessionData);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function backLogs()
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];
        $project_info = ProjectManagement::where('id', $project_id)->first();
        $project_lead = $project_info->project_lead;

        $login_user_id = Auth::id();
        $employee_info = Employee::where('user_id', $login_user_id)->where('is_active', 1)->first();
        if($employee_info){ 
            // Not admin, if admin then employee data will not be available in employees table.
            // Checking if supervisor or not... otherwise not allow to enter backlogs page..
			if(!$employee_info->is_supervisor){
				// If the creation was successful, redirect with success message
                return redirect()->route('project.project-lists')->with('fail', localize('you_are_not_allowed_to_access_this_page'));
			}else{
				$resp_project = $this->verify_project($project_id,$project_lead);
				if(!$resp_project){
                    return redirect()->route('project.project-lists')->with('fail', localize('you_are_not_authorized_to_access_this_project'));
				}
            }
        }
		// Check previous project backlogs, if available then keep open 'Get Retros' button open. 
		$previous_project_backlogs = 0;
		if((int)$project_info->second_parent_project_id > 0){

			$previous_project_backlogs = $this->total_previous_project_backlogs($project_info->second_parent_project_id);
		}
		// Ebd of check previous project backlogs
		$backlog_lists  = $this->all_backlogs($project_id);
		// this will use as reporter / project_lead, who will lead the project
		$project_leads = $this->supervisor_dropdown();
		// this will use as assignee / team_members, who will work on the project
		$team_members = $this->assigned_empdropdown($project_id);
		// available sprints for the project
		$available_sprints = $this->get_sprints($project_id);

        return view('humanresource::project.backlogs.backlog_tasks_list', 
        compact(
            'employee_info',
            'project_info',
            'previous_project_backlogs',
            'backlog_lists',
            'project_leads',
            'team_members',
            'available_sprints'
        ));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function backlogTaskCreate()
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];
        $project_info = ProjectManagement::where('id', $project_id)->first();
        $project_lead = $project_info->project_lead;

        // this will use as reporter / project_lead, who will lead the project
		$project_leads = $this->supervisor_dropdown();
		// this will use as assignee / team_members, who will work on the project
		$team_members = $this->assigned_empdropdown($project_id);
		// available sprints for the project
		$available_sprints = $this->get_sprints($project_id);

        // Versions for projects
        $version_projects = $this->versionProjects();

        return view('humanresource::project.backlogs.create_task', 
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
    public function backlogStore(Request $request, $req_project_id)
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];

        // If anyone tries to change project_id from inspect element, then will catch exception here.
		if($req_project_id != $project_id){
            return redirect()->route('project.backlogs')->with('fail', localize('you_have_already_opened_project_in_another_tab'));	
		}

        $project_info = ProjectManagement::where('id', $project_id)->first();

        //Total tasks for an individual project is over then not allow to create any further task for the project
		$project_all_backlogs_tasks  = $this->project_all_backlogs_tasks($project_id);
        if($project_all_backlogs_tasks >= $project_info->approximate_tasks){
			return redirect()->route('project.backlogs')->with('fail', localize('sorry_your_approximate_tax_limit_is_over'));
		}

        $validated = $request->validate([
            'summary'        => 'required',
            'description'    => 'required',
            'employee_id'    => 'required',
            'priority'       => 'required',
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $request_file = $request->file('attachment');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('attachments', $filename, 'public');
        }

        $postData = [

            'project_id' 	=> $project_info->id,
            'summary'  		=> $request->input('summary'),
            'description'   => $request->input('description'),
            'project_lead'  => $project_info->project_lead,
            'employee_id'   => $request->input('employee_id'),
            'sprint_id'   	=> $request->input('sprint_id'),
            'priority'   	=> $request->input('priority'),
            'attachment'   	=> $path,

        ];

        if(!empty($postData['sprint_id'])){

            $postData['is_task'] = 1;

        }else{
            $postData['is_task'] = 0;
        }

        try {
            // Attempt to create the ProjectTasks instance
            $task = ProjectTasks::create($postData);
        
            // If the creation was successful, redirect with success message
            return redirect()->route('project.backlogs')->with('success', localize('data_saved_successfully'));

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
    public function backlogEdit(ProjectTasks $backlog)
    {
        $backlog_task_data = $backlog;

        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];
        $project_info = ProjectManagement::where('id', $project_id)->first();
        $project_lead = $project_info->project_lead;

        // this will use as reporter / project_lead, who will lead the project
		$project_leads = $this->supervisor_dropdown();
		// this will use as assignee / team_members, who will work on the project
		$team_members = $this->assigned_empdropdown($project_id);
		// available sprints for the project
		$available_sprints = $this->get_sprints($project_id);

        // Versions for projects
        $version_projects = $this->versionProjects();

        return view('humanresource::project.backlogs.edit_task', 
            compact(
                'backlog_task_data',
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
    public function backlogUpdate(Request $request, $id)
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['project_id'];

        // Verify, if this task is associated or not with the project_leader/Reporter..
		$valid_backlog_task = $this->valid_backlog_task($id,$project_id);

        // If anyone tries to change project_id from inspect element, then will catch exception here.
		if(!$valid_backlog_task){
            return redirect()->route('project.backlogs')->with('fail', localize('you_are_not_associated_with_this_task'));	
		}

        $validated = $request->validate([
            'summary'        => 'required',
            'description'    => 'required',
            'priority'       => 'required',
        ]);

        $postData = [
            'summary'  		=> $request->input('summary'),
            'description'   => $request->input('description'),
            'employee_id'   => $request->input('employee_id'),
            'sprint_id'   	=> $request->input('sprint_id')?$request->input('sprint_id'):null,
            'priority'   	=> $request->input('priority'),

        ];

        $path = null;
        if ($request->hasFile('attachment')) {
            $request_file = $request->file('attachment');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('attachments', $filename, 'public');
            $postData['attachment'] = $path;
        }

        if(!empty($postData['sprint_id'])){

            $postData['is_task'] = 1;

        }else{
            $postData['is_task'] = 0;
        }

        try {
            // Attempt to update the ProjectTasks instance
            $backlog_task = ProjectTasks::findOrFail($id);
            $backlog_task->update($postData);
        
            // If the creation was successful, redirect with success message
            return redirect()->route('project.backlogs')->with('success', localize('data_updated_successfully'));

        } catch (\Exception $e) {
            // If an exception occurs (e.g., validation error, database error), handle it here
            // You can customize the error message based on the type of exception
            return redirect()->back()->withInput()->with('fail', 'Failed to update data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function backlogDestroy(ProjectTasks $backlog)
    {
        ProjectTasks::where('id' ,$backlog->id)->where('is_task' ,0)->delete();
        Toastr::success('Message Deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }

    public function valid_backlog_task($task_id = null , $project_id = null){
        return ProjectTasks::where('id', $task_id)->where('project_id', $project_id)->where('is_task',0)->first();
    }

    //Total tasks along with backlogs for an individual project
    public function project_all_backlogs_tasks($project_id = null){
        return ProjectTasks::where('project_id', $project_id)->where('is_task',0)->count();

    }

    /*Get sprint*/
    public function get_sprints($project_id = null)
    {
        $result = ProjectSprints::where('project_id', $project_id)->where('is_finished', 0)->get();
        $list = array('' => 'Select One...');
        if (!empty($result) ) {
            foreach ($result as $value) {
                $list[$value->id ] = $value->sprint_name;
            } 
        }
        return $list;
    } 

    /*Getting employee who are not created as supervisor*/
    public function assigned_empdropdown($project_id)
    {
        $team_members = ProjectEmployees::where('project_id', $project_id)->get();
       
        $list = array('' => 'Select One...');
        if (!empty($team_members) ) {

            foreach ($team_members as $value) {
                $data = Employee::where('id', $value->employee_id)->where('is_active', 1)->where('is_supervisor', 0)->first();
                if($data){
                    
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
        if (!empty($data) ) {
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
        return ProjectTasks::where('project_id', $project_id)->where('is_task',0)->count();
    }

    public function verify_project($project_id = null , $project_lead = null)
    {
        return ProjectManagement::where('id', $project_id)->where('project_lead', $project_lead)->first();
    }

}
