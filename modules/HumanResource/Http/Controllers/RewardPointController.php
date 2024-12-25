<?php

namespace Modules\HumanResource\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HumanResource\Entities\PointCategory;
use Modules\HumanResource\Entities\PointManagement;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\RewardPoint;
use Modules\HumanResource\Entities\PointCollaborative;
use Modules\HumanResource\Entities\PointSettings;
use Modules\HumanResource\Entities\PointAttendance;
use Modules\HumanResource\DataTables\EmployeePointsDataTable;


class RewardPointController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_point_settings')->only(['index']);
        $this->middleware('permission:read_point_categories')->only(['pointCategories']);
        $this->middleware('permission:read_management_points', ['only' => ['managementPoints']]);
        $this->middleware('permission:read_collaborative_points', ['only' => ['collaborativePoints']]);
        $this->middleware('permission:read_attendance_points', ['only' => ['attendancePoints']]);
        $this->middleware('permission:read_employee_points', ['only' => ['employeePoints']]);

        $this->middleware('permission:update_point_settings')->only(['store']);

        $this->middleware('permission:create_point_categories', ['only' => ['pointCategoryStore']]);
        $this->middleware('permission:create_management_points')->only(['pointManagementStore']);
        $this->middleware('permission:create_collaborative_points', ['only' => ['pointCollaborativeStore']]);

        $this->middleware('permission:update_point_categories', ['only' => ['update']]);

        $this->middleware('permission:delete_point_categories', ['only' => ['destroy']]);
        $this->middleware('permission:delete_management_points', ['only' => ['pointManagementDelete']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        // point_settings info
        $point_settings = PointSettings::select('*')
            ->first();

        return view('humanresource::reward-point.index', compact('point_settings'));
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
        $validated = $request->validate([
            'general_point' => 'required',
            'attendance_point' => 'required',
            'collaborative_start' => 'required',
            'collaborative_end' => 'required',
            'attendance_start' => 'required',
            'attendance_end' => 'required',
        ]);

        // Check if the point_settings table is empty
        $count = PointSettings::count();
        // Check if the count is zero
        if ($count === 0) {

            PointSettings::create($validated);

            return redirect()->route('reward.index')->with('success', localize('data_saved_successfully'));
        } else {

            // point_settings info
            $point_settings = PointSettings::select('*')
                ->first();

            // Update the record using the model
            PointSettings::where('id', $point_settings->id)->update([
                'general_point' => $request->input('general_point'),
                'attendance_point' => $request->input('attendance_point'),
                'collaborative_start' => $request->input('collaborative_start'),
                'collaborative_end' => $request->input('collaborative_end'),
                'attendance_start' => $request->input('attendance_start'),
                'attendance_end' => $request->input('attendance_end'),
            ]);

            return redirect()->route('reward.index')->with('success', localize('data_updated_successfully'));
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

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function pointCategories()
    {
        // point_categories info
        $dbData = PointCategory::all();

        return view('humanresource::reward-point.point_categories', compact('dbData'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function pointCategoryStore(Request $request)
    {
        $validated = $request->validate([
            'point_category' => 'required',
        ]);

        PointCategory::create($validated);

        return redirect()->route('reward.point-categories')->with('success', localize('data_saved_successfully'));
    }

    public function update(Request $request, $uuid)
    {

        // Update the record using the model
        PointCategory::where('uuid', $uuid)->update([
            'point_category' => $request->input('point_category'),
        ]);

        return redirect()->route('reward.point-categories')->with('update', localize('data_updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(PointCategory $pointcat)
    {

        $pointcat->delete();
        Toastr::success('Message Deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function managementPoints()
    {
        // Get the authenticated user ID
        $userId = Auth::id();

        $point_categories  = PointCategory::all();
        $employees = Employee::where('is_active', 1)
            ->whereNotIn('user_id', [$userId])
            ->get();

        // point_managements info
        $dbData = PointManagement::with('employee', 'pointCategory')->get();

        return view('humanresource::reward-point.managements', compact('dbData', 'point_categories', 'employees'));
    }

    /**
     * pointManagementStore a newly created resource.
     * @param Request $request
     * @return Renderable
     */
    public function pointManagementStore(Request $request)
    {
        $validated = $request->validate([
            'employee_id'    => 'required',
            'point_category' => 'required',
            'description'    => '',
            'point'          => 'required',
        ]);

        $res = $this->managementPointCreateEligibility($request->input());
        if ($res) {

            PointManagement::create($validated);

            return redirect()->route('reward.management-points')->with('success', localize('data_saved_successfully'));
        }
        return redirect()->route('reward.management-points')->with('fail', localize('something_went_wrong'));
    }

    public function managementPointCreateEligibility($data)
    {
        $currentDate = Carbon::now()->toDateString();

        $date         = Carbon::parse($currentDate);
        $date_y       = $date->year;
        $date_m       = $date->month;
        $time         = $date->format('H:i:s');
        $data['date'] = $date->toDateString();

        $point_reward_rec = RewardPoint::where('employee_id', $data['employee_id'])
            ->whereYear('date', '=', $date_y)
            ->whereMonth('date', '=', $date_m)
            ->first();

        if ($point_reward_rec && $point_reward_rec->id != null) {

            // Adding management point with existing management reward point, if employee already exists in point_reward table..
            $management_point = (int)$point_reward_rec->management + (int)$data['point'];
            $total = (int)$point_reward_rec->attendance + (int)$point_reward_rec->collaborative + $management_point;
            $point_reward_data['management'] = $management_point;
            $point_reward_data['total'] = $total;

            $update_reward_point = RewardPoint::where('id', $point_reward_rec->id)
                ->update($point_reward_data);

            if ($update_reward_point) {
                return true;
            } else {
                return false;
            }
        } else {

            // Inserting management point, if employee not exists in point_reward table..
            $point_reward_insert['date'] = $data['date'];
            $point_reward_insert['management'] = $data['point'];
            $point_reward_insert['total'] = $data['point'];
            $point_reward_insert['employee_id'] = $data['employee_id'];

            $insert_reward_point = RewardPoint::create($point_reward_insert);

            if ($insert_reward_point) {
                return true;
            } else {
                return false;
            }
        }

        // return $point_reward_rec;
    }


    public function pointManagementDelete($id)
    {

        $res = $this->managementPointDelete($id);
        if ($res) {

            Toastr::success('Deleted successfully :)', 'Success');
            return response()->json(['success' => 'success']);
        }

        Toastr::error('Deletion failed :(', 'Error');
        return response()->json(['error' => 'Deletion failed']);
    }

    public function managementPointDelete($id)
    {
        $management_point = PointManagement::where('id', $id)
            ->first();

        $carbonDate = Carbon::parse($management_point->created_at);

        $date = $carbonDate->format('Y-m-d');
        $date_y = $carbonDate->year;
        $date_m = $carbonDate->month;
        $time = $carbonDate->format('H:i:s');

        $point_reward_rec = RewardPoint::where('employee_id', $management_point->employee_id)
            ->whereYear('date', $date_y)
            ->whereMonth('date', $date_m)
            ->first();

        if ($point_reward_rec && $point_reward_rec->id != null) {

            // Deducting management point from existing management reward point..
            $management_point_upd = (int)$point_reward_rec->management - (int)$management_point->point;
            $total = (int)$point_reward_rec->total - (int)$management_point->point;
            $point_reward_data['management'] = $management_point_upd;
            $point_reward_data['total'] = $total;

            $update_reward_point = RewardPoint::where('id', $point_reward_rec->id)
                ->update($point_reward_data);

            if ($update_reward_point) {

                $deleted = PointManagement::where('id', $id)->delete();
                if ($deleted) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function collaborativePoints()
    {
        // Get the authenticated user ID
        $userId = Auth::id();

        $employee_info = Employee::where('is_active', 1)
            ->where('user_id', $userId)
            ->first();

        $log_emp_id = null;
        $log_emp_id = isset($employee_info->id) && $employee_info->id != null ? $employee_info->id : '';

        $employees = Employee::where('is_active', 1)
            ->whereNotIn('user_id', [$userId])
            ->whereNotIn('id', [$log_emp_id])
            ->get();

        // point_managements info
        $dbData = PointCollaborative::with('pointShareEmployee', 'pointReceiveEmployee')
            ->where('point_shared_by', $log_emp_id)
            ->get();
        if (!$log_emp_id) {
            $dbData = PointCollaborative::with('pointShareEmployee', 'pointReceiveEmployee')
                ->get();
        }

        return view('humanresource::reward-point.collaboratives', compact('dbData', 'employees'));
    }

    /**
     * pointCollaborativeStore a newly created resource.
     * @param Request $request
     * @return Renderable
     */
    public function pointCollaborativeStore(Request $request)
    {
        // Get the authenticated user ID
        $userId = Auth::id();

        $validated = $request->validate([
            'employee_id' => 'required',
            'reason'      => 'required',
            'point'       => 'required',
        ]);

        $logged_employee_info = Employee::where('is_active', 1)
            ->where('user_id', $userId)
            ->first();

        if ($logged_employee_info) {

            $point = $request->input('point');

            if ((int)$point != 1) {
                return redirect()->route('reward.collaborative-points')->with('fail', localize('maximum_point_value_is_one'));
            }

            $res = $this->collaborativePointCreateEligibility($request->input(), $logged_employee_info);
            if ($res['status']) {
                return redirect()->route('reward.collaborative-points')->with('success', $res['msg']);
            }
            return redirect()->route('reward.collaborative-points')->with('fail', $res['msg']);
        } else {
            return redirect()->route('reward.collaborative-points')->with('fail', localize('must_be_an_employee'));
        }
    }

    public function collaborativePointCreateEligibility($data, $logged_employee_info)
    {
        $date_time = Carbon::now();
        $date_d    = $date_time->format('d');

        //Get point settings
        $point_settings = PointSettings::orderBy('id', 'desc')->first();
        if(!$point_settings){
            $resp = [
                'status' => false,
                'msg'    => localize('point_setting_required'),
            ];
            return $resp;
        }

        $point_shared_with = $data['employee_id'];

        //collaborative point start date
        $collaborative_start = $point_settings->collaborative_start;
        $dt_collaborative_start = Carbon::parse($collaborative_start);
        $collaborative_start_d = $dt_collaborative_start->format('d');

        // Collaborative point start date
        $collaborative_end = $point_settings->collaborative_end;
        $dt_collaborative_end = Carbon::parse($collaborative_end);
        $collaborative_end_d = $dt_collaborative_end->format('d');

        //Checking collaborative point start and end date
        if ((int)$date_d >= (int)$collaborative_start_d && (int)$date_d <= (int)$collaborative_end_d) {

            //Checking if trying to share point with own self
            if ((int)$point_shared_with == (int)$logged_employee_info->id) {
                $resp = [
                    'status' => false,
                    'msg'    => localize('you_can_not_share_point_with_yourself'),
                ];
                return $resp;
            } else {

                //Getting Collaborative point for the logged in employee for current month..
                $collaborative_point_count = $this->collaborative_point_count($logged_employee_info->id);
                if ((int)$collaborative_point_count >= (int)$point_settings->general_point) {
                    $resp = [
                        'status' => false,
                        'msg'    => localize('point_share_limit_is_over'),
                    ];
                    return $resp;
                } else {

                    //Check employee sharing point more than one with point receiver employee for current month
                    $check_emp_collab_point = $this->check_emp_collab_point($data['employee_id'], $logged_employee_info->id);
                    if ((int)$check_emp_collab_point < (int)$point_settings->attendance_point) {

                        $postData = [
                            'point_shared_with' => $data['employee_id'],
                            'reason'            => $data['reason'],
                            'point'             => $data['point'],
                        ];
                        if ($this->collaborative_point_create($postData, $logged_employee_info->id)) {

                            $resp = [
                                'status' => true,
                                'msg'    => localize('point_shared_successfully'),
                            ];
                            return $resp;
                        } else {
                            $resp = [
                                'status' => false,
                                'msg'    => localize('can_not_create_collaborative_point'),
                            ];
                            return $resp;
                        }
                    } else {
                        $resp = [
                            'status' => false,
                            'msg'    => localize('can_share_only_one_point_with_an_employee_for_a_month'),
                        ];
                        return $resp;
                    }
                }
            }
        } else {
            $resp = [
                'status' => false,
                'msg'    => localize('point_sharing_date_is_over'),
            ];
            return $resp;
        }
    }

    /*Adding Collaborative point to point_reward table*/
    public function collaborative_point_create($data = array(), $point_shared_by = null)
    {

        //Only Employee can share collaborative point..
        if ($point_shared_by != null) {

            $data['point_shared_by'] = $point_shared_by;

            $current_date = Carbon::now();
            $data['create_date'] = $current_date->format('Y-m-d h:i:sa');
            $date = $current_date->format('Y-m-d');
            $date_y = $current_date->format('Y');
            $date_m = $current_date->format('m');
            $date_d = $current_date->format('d');
            $time = $current_date->format('H:i:s');


            $date_m = $date_m - 1;
            if ($date_m == 0) {
                $date_y = $date_y - 1;
                $date_m = 12;
            }
            $date = $date_y . "-" . $date_m . "-" . $date_d;

            $point_date = Carbon::createFromFormat('Y-m-d', $date)->startOfDay();
            $data['point_date'] = $point_date->toDateString();

            if ($this->add_collaborative_point_to_reward($data)) {
                $insert_collaborative_point = PointCollaborative::create($data);
                if ($insert_collaborative_point) {
                    return true;
                }
                return false;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*Insert collaborative point to employee point_reward database table*/
    private function add_collaborative_point_to_reward($data = array())
    {

        $dateString = substr($data['create_date'], 0, 19);
        // Extract only the first 19 characters (YYYY-MM-DD HH:MM:SS)
        $dt = Carbon::createFromFormat('Y-m-d H:i:s', $dateString);
        $date = $dt->toDateString();
        $date_y = $dt->year;
        $date_m = $dt->month;
        $date_d = $dt->day;
        $time = $dt->toTimeString();
        $data['date'] = $date;

        //Get point settings
        $point_settings = $this->get_last_record();

        //collaborative point start date
        $collaborative_start = $point_settings->collaborative_start;
        $dt_collaborative_start = Carbon::parse($collaborative_start);
        $collaborative_start_d = $dt_collaborative_start->day;

        // Convert collaborative_end from point_settings to Carbon instance
        $collaborative_end = $point_settings->collaborative_end;
        $dt_collaborative_end = Carbon::parse($collaborative_end);
        $collaborative_end_d = $dt_collaborative_end->day;

        /*Managing collaborative Points between collaborative_start_d and collaborative_end_d in current month for previous month*/
        if ((int)$date_d >= (int)$collaborative_start_d && (int)$date_d <= (int)$collaborative_end_d) {

            $date_m = $date_m - 1;

            if ($date_m == 0) {
                $date_y = $date_y - 1;
                $date_m = 12;
            }
            $date = $date_y . "-" . $date_m . "-" . $date_d;

            $point_reward_rec = RewardPoint::where('employee_id', $data['point_shared_with'])
                ->whereYear('date', $date_y)
                ->whereMonth('date', $date_m)
                ->first();

            if ($point_reward_rec && $point_reward_rec->id != null) {

                // Adding collaborative point with existing collaborative reward point, if employee already exists in point_reward table..
                $collaborative_point = (int)$point_reward_rec->collaborative + (int)$data['point'];
                $total = (int)$point_reward_rec->attendance + (int)$point_reward_rec->management + $collaborative_point;
                $point_reward_data['collaborative'] = $collaborative_point;
                $point_reward_data['total'] = $total;

                $update_reward_point = RewardPoint::findOrFail($point_reward_rec->id);
                $update_reward_point->update($point_reward_data);

                if ($update_reward_point) {
                    return true;
                } else {
                    return false;
                }
            } else {

                // Inserting collaborative point, if employee not exists in point_reward table..
                $point_reward_insert['date'] = $date;
                $point_reward_insert['collaborative'] = $data['point'];
                $point_reward_insert['total'] = $data['point'];
                $point_reward_insert['employee_id'] = $data['point_shared_with'];

                $insert_reward_point = RewardPoint::create($point_reward_insert);

                if ($insert_reward_point) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        /*End of Managing collaborative Points between day 1 and 5 in current month for previous month*/
    }

    /*Get Point Settings*/
    public function get_last_record()
    {

        //Get point settings
        $point_settings = PointSettings::orderBy('id', 'desc')->first();

        return $point_settings;
    }

    /*Check employee sharing point more than one with point receiver employee for current month*/
    public function check_emp_collab_point($employee_id = null, $point_shared_by = null)
    {

        $point_shared_with = $employee_id;
        $point_shared_by = $point_shared_by;

        $current_date = Carbon::now();
        $date = $current_date->format('Y-m-d');
        $date_y = $current_date->year;
        $date_m = $current_date->month;
        $date_d = $current_date->day;
        $time = $current_date->format('H:i:s');

        $date_m = $date_m - 1;
        if ($date_m == 0) {
            $date_y = $date_y - 1;
            $date_m = 12;
        }
        $date = $date_y . "-" . $date_m . "-" . $date_d;

        $collaborative_point_rec = PointCollaborative::where('point_shared_with', $point_shared_with)
            ->where('point_shared_by', $point_shared_by)
            ->whereYear('point_date', $date_y)
            ->whereMonth('point_date', $date_m)
            ->count();

        return $collaborative_point_rec;
    }

    /*Collaborative point record count for logged in employee in application for current month*/
    public function collaborative_point_count($employee_id = null)
    {

        $logged_employee_id = $employee_id;

        $current_date = Carbon::now();
        $date   = $current_date->format('Y-m-d');
        $date_y = $current_date->format('Y');
        $date_m = $current_date->format('m');
        $date_d = $current_date->format('d');
        $time   = $current_date->format('H:i:s');

        $date_m = $date_m - 1;
        if ($date_m == 0) {
            $date_y = $date_y - 1;
            $date_m = 12;
        }
        $date = $date_y . "-" . $date_m . "-" . $date_d;

        $collaborative_point_recs = PointCollaborative::where('point_shared_by', $logged_employee_id)
            ->whereYear('point_date', '=', $date_y)
            ->whereMonth('point_date', '=', $date_m)
            ->count();

        return $collaborative_point_recs;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function attendancePoints()
    {

        // point_managements info
        $dbData = PointAttendance::with('employee')
            ->get();

        return view('humanresource::reward-point.attendances', compact('dbData'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function employeePoints(EmployeePointsDataTable $dataTable)
    {
        return $dataTable->render('humanresource::reward-point.employee_points');
    }
}
