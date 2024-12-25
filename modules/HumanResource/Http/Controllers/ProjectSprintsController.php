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

class ProjectSprintsController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read_sprint')->only(['sprints']);
        $this->middleware('permission:create_sprint', ['only' => ['sprintStore']]);
        $this->middleware('permission:update_sprint', ['only' => ['sprintUpdate']]);
        $this->middleware('permission:delete_sprint', ['only' => ['sprintDestroy']]);
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
    public function getSprints(Request $request)
    {
        // Putting data into session
        session(['sprint_project_id' => $request->input('project_id')]);
        $allSessionData = session()->all();

        echo json_encode($allSessionData);
    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function sprints()
    {
        $allSessionData = session()->all();
        $sprint_project_id = $allSessionData['sprint_project_id'];

        $sprint_lists = $this->all_sprints($sprint_project_id);
		$total_sprint = $this->project_sprints_count($sprint_project_id);
		$sprint_no = "Sprint-".strval($total_sprint + 1);
		$project_info = ProjectManagement::where('id', $sprint_project_id)->first();

        return view('humanresource::project.sprints.sprint_list', 
        compact(
            'project_info',
            'sprint_lists',
            'sprint_no'
        ));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function sprintCreate()
    {
        $allSessionData = session()->all();
        $sprint_project_id = $allSessionData['sprint_project_id'];

        $sprint_lists = $this->all_sprints($sprint_project_id);
		$total_sprint = $this->project_sprints_count($sprint_project_id);
		$sprint_no = "Sprint-".strval($total_sprint + 1);
		$project_info = ProjectManagement::where('id', $sprint_project_id)->first();

        return view('humanresource::project.sprints.create_sprint', 
            compact(
                'project_info',
                'sprint_lists',
                'sprint_no'
            )   
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function sprintStore(Request $request, $req_project_id)
    {
        $allSessionData = session()->all();
        $sprint_project_id = $allSessionData['sprint_project_id'];

        // If anyone tries to change project_id from inspect element, then will catch exception here.
		if($sprint_project_id != $req_project_id){
			return redirect()->route('project.sprints')->with('fail', localize('you_have_already_opened_project_in_another_tab'));	
		}

        $validated = $request->validate([
            'sprint_name'   => 'required',
            'duration'      => 'required',
            'start_date'    => 'required',
            'sprint_goal'   => 'required',
        ]);

        $project_id = $sprint_project_id;
        $sprint_name = $request->input('sprint_name');
        $start_date = $request->input('start_date');
        $duration = $request->input('duration');

        $postData = [
            'project_id' 	=> $project_id,
            'sprint_name'  	=> $sprint_name,
            'duration'   	=> $duration,
            'start_date'  	=> $start_date,
            'sprint_goal'   => $request->input('sprint_goal'),

        ];

        // Validation after form submit when creating Sprint for any project..
        $exception = false;

        $project_info  = $this->single_project_data($project_id);
        $days_spend_for_sprints  = $this->days_spend_for_sprint($project_id);
        $duplicate_sprint_check = $this->duplicate_sprint_check($project_id,$sprint_name);
        $sprint_not_finished = $this->sprint_not_finished($project_id);

        $remain_project_days = $project_info->project_duration - $days_spend_for_sprints;
        $total_sprint_days = $days_spend_for_sprints + $duration;

        if($total_sprint_days > $project_info->project_duration){
            return redirect()->route('project.sprints')->with('fail', $remain_project_days." days remaining for this project, so can't set more than this for a sprint !");
            $exception = true;

        }elseif($sprint_not_finished){
            return redirect()->route('project.sprints')->with('fail', $sprint_not_finished->sprint_name." is not finished yet !");
            $exception = true;

        }elseif($duplicate_sprint_check){
            return redirect()->route('project.sprints')->with('fail', $sprint_name." already exists for this project.");
            $exception = true;

        }elseif(strtotime($start_date) < strtotime(date("Y-m-d"))){
            return redirect()->route('project.sprints')->with('fail', localize('start_date_must_be_current_date_or_greater'));
            $exception = true;

        }elseif($duration > 14 || $duration <= 0){
            return redirect()->route('project.sprints')->with('fail', "Sprint duration must in 1-14 days.");
            $exception = true;
        }

        try {
            // Attempt to create the ProjectTasks instance
            $sprint = ProjectSprints::create($postData);
            if($sprint){
                // Update the project start date.. if this is the first sprint(when sprint_nums is 1) of the project..
        		$sprint_nums  = $this->sprint_nums($project_id);
        		if($sprint_nums == 1){

        			 $updateData = [
			            'start_date'  	=> $start_date,
			        ];
        			$respo_project_up = $this->update_project_start_date($updateData, $project_id);
        		}
            }
            // If the creation was successful, redirect with success message
            return redirect()->route('project.sprints')->with('success', localize('data_saved_successfully'));

        } catch (\Exception $e) {
            // If an exception occurs (e.g., validation error, database error), handle it here
            // You can customize the error message based on the type of exception
            return redirect()->back()->withInput()->with('fail', 'Failed to save data: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function transferSprintTasks($sprint_id)
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['sprint_project_id'];
        $project_info = ProjectManagement::where('id', $project_id)->first();
        $sprint_info = ProjectSprints::where('id', $sprint_id)->where('project_id', $project_id)->first();

        // Check valid sprint for the project, if anyone request by invalid sprint_id.. show invalid request...
		if(!$sprint_info){
			return redirect()->route('project.sprints')->with('fail', localize('you_have_already_opened_project_in_another_tab'));
		}
        // Get all tasks for the sprint where task_status = 0 means it's not 'In progress' or 'Done'.
	    $sprint_tasks = $this->all_sprints_tasks($sprint_id, $project_id);

        $id = $sprint_id;

        return view('humanresource::project.sprints.transfer_sprint_tasks', 
        compact(
            'project_info',
            'sprint_info',
            'sprint_tasks',
            'sprint_id'
        ));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function transferSprintTasksStore(Request $request, $sprint_id)
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['sprint_project_id'];
        $project_info = ProjectManagement::where('id', $project_id)->first();
        $sprint_info = ProjectSprints::where('id', $sprint_id)->where('project_id', $project_id)->first();

	    $sprint_tasks = $request->input('sprint_tasks');
        // If not select any sprint_tasks , then show exception message and redirect to transfer_tasks page.
        if($sprint_tasks == null){
            return redirect()->route('project.transfer-sprint-tasks', $sprint_id)->withErrors(['fail' => localize('please_select_tasks_to_transfer_as_backlogs')]);
        }
        $sprint_tasks_transfer_status = false;

        foreach ($sprint_tasks as $key => $value) {
            $task_id = $value;
            //Sprint task check, if valid or not(it will happen if anyone try to take input from inspect element for sprint_tasks[] value)
            $sprint_task_check = $this->sprint_task_check($project_id,$sprint_id,$task_id);
            if($sprint_task_check){
                $sprintTaskData = [
                    'sprint_id' 	=> null,
                    'is_task'  		=> 0,
                ];
                // Updating Task for the above data in pm_task_list table to move it again in backlogs..
                $resp = $this->update_task_from_sprints($sprintTaskData, $task_id);
                if($resp){
                    $sprint_tasks_transfer_status = true;
                }
            }
        }
        if ($sprint_tasks_transfer_status) {
            return redirect()->route('project.transfer-sprint-tasks', $sprint_id)->with('success', localize('tasks_transferred_successfully'));
        }else{
            return redirect()->route('project.transfer-sprint-tasks', $sprint_id)->with('fail', localize('something_went_wrong'));
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function sprintEdit(ProjectSprints $sprint)
    {
        if($sprint){

            $sprint_data  = $sprint;
            $sprint_id    = $sprint_data->id;
            $project_info = $this->single_project_data($sprint_data->project_id);
            // if close_open is true , means close_open dropdow can be shown to send request for closing a sprint.
            $close_open   = $this->sprint_close_open($sprint_data->project_id, $sprint_data->id);

            if($sprint_data->is_finished){
                return redirect()->route('project.sprints')->with('fail', $sprint_data->sprint_name." is already finished !");
            }else{
                
                // Putting data into session
                session([
                    'sprint_id' => $sprint_data->id,
                    'old_sprint_name' => $sprint_data->sprint_name,
                    'old_start_date' => $sprint_data->start_date,
                ]);
                $allSessionData = session()->all();

                // Check if the sprint belongs to the supervisor / project_lead's project who is logged in the application.
                $login_user_id = Auth::id();
                $employee_is_supervisor = Employee::where('user_id', $login_user_id)->where('is_active', 1)->where('is_supervisor', 1)->first();

	    		if($employee_is_supervisor){

	    			$verify_project = $this->verify_project($sprint_data->project_id, $project_info->project_lead);
	    			if(!$verify_project){
                        return redirect()->route('project.sprints')->with('fail', localize('you_are_not_associated_with_this_sprint'));
	    			}
	    		}
            }
            return view('humanresource::project.sprints.edit_sprint', 
                compact(
                    'sprint_data',
                    'project_info',
                    'close_open',
                    'sprint_id',
                )   
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function getSprintUndoneTasks(Request $request)
    {
        $allSessionData = session()->all();
		$project_id = $allSessionData['sprint_project_id'];
        $sprint_id = $allSessionData['sprint_id'];

        $to_do_tasks = 0;
        $in_progress_tasks = 0;
        $done_tasks = 0;

        $to_do_tasks = $this->sprint_to_do_tasks($project_id , $sprint_id);
        $in_progress_tasks = $this->sprint_in_progress_tasks($project_id , $sprint_id);
        $done_tasks = $this->sprint_done_tasks($project_id , $sprint_id);

        $resp_data['to_do_tasks'] = $to_do_tasks;
        $resp_data['in_progress_tasks'] = $in_progress_tasks;
        $resp_data['done_tasks'] = $done_tasks;

		echo json_encode($resp_data);

    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function sprintUpdate(Request $request, $sprint_id)
    {
        $allSessionData = session()->all();
        $project_id = $allSessionData['sprint_project_id'];
		$old_sprint_name = $allSessionData['old_sprint_name'];
        $old_start_date = $allSessionData['old_start_date'];
        $sprint_id = $allSessionData['sprint_id'];

        $login_user_id = Auth::id();
        $isSupervisor  = Employee::where('user_id', $login_user_id)->where('is_active', 1)->where('is_supervisor', 1)->first();

        $current_sprint_data = $this->single_sprint_data($sprint_id);
        if($current_sprint_data->project_id != $project_id){
            return redirect()->route('project.sprints')->with('fail', localize('you_have_already_opened_project_in_another_tab'));
        }

        $validated = $request->validate([
            'sprint_name'  => 'required',
            'duration'     => 'required',
            'start_date'   => 'required',
            'sprint_goal'  => 'required',
        ]);

        $duration    = $request->input('duration');
        $start_date  = $request->input('start_date');
        $sprint_name = $request->input('sprint_name');
        $is_finished = $request->input('sprint_status');

        $postData = [
        	'sprint_id' 	=> $sprint_id,
            'project_id' 	=> $project_id,
            'sprint_name'  	=> $request->input('sprint_name'),
            'duration'   	=> $request->input('duration'),
            'start_date'  	=> $request->input('start_date'),
            'sprint_goal'   => $request->input('sprint_goal'),
            'is_finished'  	=> $request->input('sprint_status')?$request->input('sprint_status'):0,

        ];
        
        // Validation after form submit when creating Sprint for any project..
        $exception = false;

        $project_info  = $this->single_project_data($project_id);
        $days_spend_for_sprints  = $this->days_spend_for_sprint($project_id);
        $duplicate_sprint_check = $this->duplicate_sprint_check($project_id,$sprint_name);

        $remain_project_days = $project_info->project_duration - $days_spend_for_sprints;
        $total_sprint_days = $days_spend_for_sprints + $duration;

        if($total_sprint_days > $project_info->project_duration){
            return redirect()->route('project.sprints')->with('fail', $remain_project_days." days remaining for this project, so can't set more than this for a sprint !");
            $exception = true;

        }elseif($sprint_name != $old_sprint_name){

            if($duplicate_sprint_check){

                return redirect()->route('project.sprints')->with('fail', $sprint_name." already exists for this project.");
                $exception = true;
            }

        }elseif($start_date != $old_start_date){

            if(strtotime($start_date) < strtotime(date("Y-m-d"))){

                return redirect()->route('project.sprints')->with('fail', localize('start_date_must_be_current_date_or_greater'));
                $exception = true;
            }

        }elseif($duration > 14 || $duration <= 0){

            return redirect()->route('project.sprints')->with('fail', "Sprint duration must be 1-14 days.");
            $exception = true;
        }

        try {
            // Attempt to update the ProjectTasks instance
            $sprint = ProjectSprints::findOrFail($sprint_id);
            $up_res = $sprint->update($postData);
            if($up_res){

                // Update the project start date.. if this is the first sprint of the project..
        		$updateData = [
		            'start_date'  	=> $start_date,
		        ];
                // get the first_sprint_id of the project
				$first_sprint_by_project = $this->first_sprint_by_project($project_id);
                // Check if this is the first sprint
				if($sprint_id == $first_sprint_by_project){

		        	// update_project_start_date, if the first sprint is updated
        			$resp_project_up = $this->update_project_start_date($updateData, $project_id);
				}

                // Also, take all undone tasks of the sprint to backlogs of the sprint project and also update project completed_tasks.
	        	if($is_finished){

	        		$undone_tasks = $this->sprint_undone_tasks($project_id , $sprint_id);

	        		foreach ($undone_tasks as $task_id) {
	        			$task_details = $this->task_details($task_id);
	        			$taskData = [
				        	'sprint_id' 	=> null,
				            'is_task' 		=> 0,
				            'task_status' 	=> 1,

				        ];
				        $task_update_resp = $this->update_backlog_task($taskData, $task_id);
	        		}
	        		// Update Project completed_tasks after updating the sprint as closed and transfer remaining tasks to backlogs.
	        		$tasks_completed_by_project = 0;
	        		$tasks_completed_by_project = $this->tasks_completed_by_project($project_id);

					// Update project completed_tasks...
					$project_data['complete_tasks'] = $tasks_completed_by_project;

					$update_project = $this->update_project($project_data, $project_id);
					// End of Updating project's completed_tasks
	        	}

            }
        
            // If the creation was successful, redirect with success message
            return redirect()->route('project.sprints')->with('success', localize('data_updated_successfully'));

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
    public function sprintDestroy(ProjectSprints $sprint)
    {
        $exception = false;
		$is_first_sprint = false;

		$sprint_data = $this->single_sprint_data($sprint->id);
		$sprint_asscociate_task = $this->sprint_asscociate_task($sprint_data);
    
        // If sprint is already finished, then not allow to delete the sprint...
		if($sprint_data->is_finished){

            Toastr::error($sprint_data->sprint_name." is already finished !", 'Errors');
            return response()->json(['error' => $sprint_data->sprint_name." is already finished !"]);

		}elseif($sprint_asscociate_task >= 1){

            Toastr::error("Tasks are already associated with".$sprint_data->sprint_name, 'Errors');
            return response()->json(['error' => "Tasks are already associated with".$sprint_data->sprint_name]);
		}

        // Check if this sprint is the first sprint_id($id) of the project, which is trying to delete
		$first_sprint_resp = $this->first_sprint_by_project($sprint_data->project_id);
		if($first_sprint_resp == $sprint->id){
			$is_first_sprint = true;
		}

        // Check, the sprint to be deleted is the only sprint of any project.
		$sprint_nums  = $this->sprint_nums($sprint_data->project_id);
		if($sprint_nums == 1){
			// updateData.. as this is the first sprint data of the project now..
    		$updateData = [
	            'project_id' 	=> $sprint_data->project_id,
	            'start_date'  	=> null,
	        ];
		}

        $de_res = ProjectSprints::where('id' ,$sprint->id)->delete();
        if($de_res){

            // Check if this sprint project has many other sprints, then if this was the first sprint, need to update project start_date by finding the first sprint
			if($is_first_sprint == true && $sprint_nums > 0){

				// if delete first sprint, then get the first_sprint_id where will get the first sprint data also
				$first_sprint_by_project = $this->first_sprint_by_project($sprint_data->project_id);
				$first_sprint_data       = $this->single_sprint_data($first_sprint_by_project);

				// updateData.. as this is the first sprint data of the project now..
        		$updateData = [
		            'start_date'  	=> $first_sprint_data->start_date,
		        ];
        		// update_project_start_date with the new first_sprint_id
        		$resp_project_up = $this->update_project_start_date($updateData, $sprint_data->project_id);

			}else{
				
				// update_project_start_date with the new first_sprint_id
        		$resp_project_up = $this->update_project_start_date($updateData, $sprint_data->project_id);
			}

        }

        Toastr::success('Message Deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }

    public function sprint_asscociate_task($sprint_data){

        return ProjectTasks::where('sprint_id',$sprint_data->id)->where('project_id', $sprint_data->project_id)->count();
    }

    public function update_project($data = [], $project_id = null)
    {
        $project = ProjectManagement::findOrFail($project_id);
            $up_res = $project->update($data);
    }

    // update_task_from_pm_kanban board
    public function tasks_completed_by_project($project_id = null)
    {
        return ProjectTasks::where('project_id', $project_id)->where('task_status',3)->count();
    }

    public function update_backlog_task($data = [], $task_id = null)
    {
        $sprint = ProjectTasks::findOrFail($task_id);
            $up_res = $sprint->update($data);
    }

    // update_task_from_pm_kanban board
    public function task_details($task_id = null)
    {
        return ProjectTasks::where('id', $task_id)->first();
    }

    public function sprint_undone_tasks($project_id = null , $sprint_id = null){

        $query_string = "task_status < 3 AND task_status > 0";

        $result = ProjectTasks::where('sprint_id', $sprint_id)
            ->where('project_id', $project_id)
            ->whereRaw($query_string)
            ->pluck('id');

        return $result;
    }

    // Check first_sprint_by_project, to update start date of the project...
    public function first_sprint_by_project($project_id = null)
    {
        return ProjectSprints::where('project_id', $project_id)
        ->min('id');
    }

    public function sprint_done_tasks($project_id = null , $sprint_id = null){
        return ProjectTasks::where('project_id', $project_id)->where('sprint_id', $sprint_id)->where('task_status', 3)->count();
    }

    public function sprint_in_progress_tasks($project_id = null , $sprint_id = null){

        return ProjectTasks::where('project_id', $project_id)->where('sprint_id', $sprint_id)->where('task_status', 2)->count();
    }

    public function sprint_to_do_tasks($project_id = null , $sprint_id = null){

        return ProjectTasks::where('project_id', $project_id)->where('sprint_id', $sprint_id)->where('task_status', 1)->count();
    }

    // If returns true means, close/open dropdown will show when updating any sprint.. to close the sprint.
    public function sprint_close_open($project_id = null , $sprint_id = null){

        $total_tasks = 0;
        $open_tasks = 0;
        $closed_tasks = 0;
        $status = false;

        $total_tasks = ProjectTasks::where('project_id', $project_id)->where('sprint_id', $sprint_id)->count();
        if($total_tasks > 0){

            $open_tasks = ProjectTasks::where('project_id', $project_id)->where('sprint_id', $sprint_id)->where('task_status', '!=', 3)->count();
            $closed_tasks = ProjectTasks::where('project_id', $project_id)->where('sprint_id', $sprint_id)->where('task_status', 3)->count();
            if($open_tasks < $closed_tasks){
                $status = true;
            }
        }
        return $status;

        
    }

    public function single_sprint_data($id){
        
        return ProjectSprints::where('id', $id)->first();
    }

    // When transferring from backlogs.. backlog tasks will be updated as only tasks along with selected sprint_id and will vanish from backlog
    public function update_task_from_sprints($data = [], $task_id = null)
    {
        // Attempt to update the ProjectTasks instance
        $task = ProjectTasks::findOrFail($task_id);
        $resp = $task->update($data);

        return $resp;
    }

    // Check sprint, when transferring tasks from sprint to backlogs
    public function sprint_task_check($project_id = null , $sprint_id = null ,$task_id = null)
    {
        return ProjectTasks::where('project_id', $project_id)
                            ->where('sprint_id',$sprint_id)
                            ->where('id',$task_id)
                            ->first();
    }

    // Get all tasks for the sprint where task_status = 1 means it's not 'In Progress' or 'Done'.
    public function all_sprints_tasks($sprint_id = null, $project_id = null)
    {

        $res = ProjectTasks::select('pm_tasks_list.*', 'e.first_name as team_mem_firstname', 'e.last_name as team_mem_lastname', 'p.project_name', 'pms.sprint_name', 'ep.first_name as proj_lead_firstname', 'ep.last_name as proj_lead_lastname')
                ->leftJoin('pm_projects as p', 'pm_tasks_list.project_id', '=', 'p.id')
                ->leftJoin('employees as ep', 'pm_tasks_list.project_lead', '=', 'ep.id')
                ->leftJoin('employees as e', 'pm_tasks_list.employee_id', '=', 'e.id')
                ->leftJoin('pm_sprints as pms', 'pm_tasks_list.sprint_id', '=', 'pms.id')
                ->where('pm_tasks_list.project_id', $project_id)
                ->where('pm_tasks_list.sprint_id', $sprint_id)
                ->where('pm_tasks_list.task_status', 1)
                ->where('pm_tasks_list.is_task', 1)
                ->orderBy('pm_tasks_list.priority', 'desc')
                ->orderBy('pm_tasks_list.id', 'desc')
                ->get();
        
        return $res;
    }

    // Insert start_date to the project table by project_id...
    public function update_project_start_date($data = [], $project_id = null)
    {
        // Attempt to update the ProjectTasks instance
        $project = ProjectManagement::findOrFail($project_id);
        $resp = $project->update($data);

        return $resp;
    }

    // Check available sprint for project, to insert start date to the project table by project_id...
    public function sprint_nums($project_id = null)
    {
        return ProjectSprints::where('project_id', $project_id)->count();
    }

    // Check if running sprint still not finished..
    public function sprint_not_finished($project_id = null)
    {
        return ProjectSprints::where('project_id', $project_id)->where('is_finished',0)->first();
    }

    public function duplicate_sprint_check($project_id = null , $sprint_name = null)
    {
        return ProjectSprints::where('project_id', $project_id)->where('sprint_name',$sprint_name)->first();
    }

    public function days_spend_for_sprint($project_id = null)
    {
        $total_days = 0;

        $result = ProjectSprints::where('project_id', $project_id)->where('is_finished',1)->get();
             
        foreach ($result as $value) {

            $total_days = $total_days + (int)$value->duration;
        }
        return $total_days;
    }

    public function single_project_data($project_id){

        return ProjectManagement::where('id', $project_id)->first();
    }

    public function project_sprints_count($project_id = null)
    {
        return ProjectSprints::where('project_id', $project_id)->count();
    }

    // Get all backlogs task where is_task = 0 and must match the project_id
    public function all_sprints($project_id = null)
    {
            
        $res =  ProjectSprints::select('pm_sprints.*','pm.project_name')
             ->leftJoin('pm_projects as pm', 'pm_sprints.project_id', '=', 'pm.id')
             ->where('pm_sprints.project_id',$project_id)
             ->orderBy('pm_sprints.id','desc')
             ->get();
        
        return $res;
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
