<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Imports\AttendanceImport;
use App\Imports\ManualAttendanceImport;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\HumanResource\Entities\Attendance;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\Holiday;
use Modules\HumanResource\Entities\ManualAttendance;
use Modules\HumanResource\Entities\WeekHoliday;
use Modules\HumanResource\Entities\PointSettings;
use Modules\HumanResource\Entities\PointAttendance;
use Modules\HumanResource\Entities\RewardPoint;

class ManualAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'permission:attendance_management']);
        $this->middleware('permission:attendance_management', ['only' => ['create', 'store', 'edit', 'update', 'destroy', 'bulk', 'monthlyAttendanceBulkImport', 'monthlyCreate', 'monthlyStore', 'missingAttendance', 'missingAttendanceStore']]);
        $this->middleware('permission:read_attendance', ['only' => ['create', 'store']]);
        $this->middleware('permission:create_attendance', ['only' => ['create', 'store']]);
        $this->middleware('permission:create_monthly_attendance', ['only' => ['monthlyCreate', 'monthlyStore']]);
        $this->middleware('permission:create_missing_attendance', ['only' => ['missingAttendance', 'missingAttendanceStore']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        $employee = Employee::where('is_active', 1)->get();

        return view('humanresource::attendance.create', compact('employee'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'time' => 'required',
        ]);

        $attendance_history = [
            'uid'    => $request->input('employee_id'),
            'state'  => 1,
            'id'     => 0,
            'time'   => $request->input('time'),
        ];

        $neTime = Carbon::parse($request->time)->format('Y-m-d H:i:s');
        $validated['time'] = $neTime;

        // attendance
        $resp = Attendance::create($validated);
        if ($resp) {
            $resp_attend = $this->insert_attendance_point($attendance_history);

            return redirect()->route('attendances.create')->with('success', localize('data_save'));
        } else {
            return redirect()->route('attendances.create')->with('error', localize('error'));
        }
    }

    /**
     * Insert attendance point when gets call from Attendance module for employee
     * this will both calculate attendance point on add
     * update and delete of attendance
     */
    public function insert_attendance_point($data = array())
    {
        /**
         * Getting from point settings
         */
        $point_settings = $this->get_last_record();
        if ($point_settings == null) {
            return false;
        }
        $attendence_start = strtotime($point_settings->attendance_start);
        $attendence_end = strtotime($point_settings->attendance_end);
        $attendence_point = $point_settings->attendance_point;

        /**
         * Getting Year,Month,day and time from Employee attendance in_time of Attendance Form
         **/
        $dt = Carbon::parse($data['time']);
        $date = $dt->format('Y-m-d');
        $date_y = $dt->year;
        $date_m = $dt->month;
        $date_d = $dt->day;
        $time_to_insert = $dt->format('H:i');
        $time = $dt->format('H:i:s');

        // Checking if attendance point already exists in point_attendance table 
        $point_attendence_rec = DB::table("point_attendances")
            ->where('employee_id', $data['uid'])
            ->whereRaw("YEAR(create_date) = ?", [$date_y])
            ->whereRaw("MONTH(create_date) = ?", [$date_m])
            ->whereRaw("DAY(create_date) = ?", [$date_d])
            ->first();

        $respo_s = true;

        if (!$point_attendence_rec) {

            //point attendence data to insert in point_attendence table
            $atten_data['employee_id'] = $data['uid'];
            $atten_data['in_time'] = $time_to_insert;
            $atten_data['create_date'] = $date;
            $atten_data['point'] = 0;

            $respo_s = PointAttendance::create($atten_data);
        } else {

            $worked_hour = $this->employee_worked_hour_today($data['uid'], $data['time']);
            $emp_in_time = $this->employee_attn_in_time($data);
            $attn_in_time = strtotime($emp_in_time);

            $point_attendence_data['in_time'] = $emp_in_time;

            //Checking if attendence punch time is occurred more than once
            $attn_history = $this->employee_attn_history($data);

            if ($attn_history >= 2) {

                //Check worked hour is more than 8 or equal 8 hours
                if ($worked_hour >= 8 && (int)$attn_in_time <= (int)$attendence_end) {

                    //Reward point data to insert in point_reward table
                    $point_reward_data['employee_id'] = $data['uid'];
                    $point_reward_data['attendence_point'] = (int)$attendence_point;
                    $point_reward_data['date'] = $date;
                    //If point_attendence is zero for today
                    if ((int)$point_attendence_rec->point <= 0) {
                        $add_reward_point = $this->add_attendence_point_to_reward($point_reward_data);

                        $point_attendence_data['point'] = (int)$attendence_point;
                        if ($add_reward_point) {

                            $pointAttendanceRecord = PointAttendance::find($point_attendence_rec->id);
                            // Update the record with new data
                            $respo_s = $pointAttendanceRecord->update($point_attendence_data);
                        }
                    }
                } else {

                    //if get point that will deduct from point_attendence and point_reward 
                    if ((int)$point_attendence_rec->point >= (int)$attendence_point) {

                        $point_attendence_data['point'] = 0;
                        $pointAttendanceRecord = PointAttendance::find($point_attendence_rec->id);
                        // Update the record with new data
                        $update_attendence_point_a = $pointAttendanceRecord->update($point_attendence_data);

                        if ($update_attendence_point_a) {
                            //Reward point data to insert in point_reward table
                            $point_reward_data_d['employee_id'] = $data['uid'];
                            $point_reward_data_d['deduct_attendence_point'] = (int)$attendence_point;
                            $point_reward_data_d['date'] = $date;

                            $respo_s = $this->deduct_attendence_point_to_reward($point_reward_data_d);
                        }
                    }
                }
            } else {
                if ((int)$point_attendence_rec->point >= (int)$attendence_point) {

                    $point_attendence_data['point'] = 0;

                    $pointAttendanceRecord = PointAttendance::find($point_attendence_rec->id);
                    // Update the record with new data
                    $update_attendence_point_b = $pointAttendanceRecord->update($point_attendence_data);

                    if ($update_attendence_point_b) {
                        //Reward point data to insert in point_reward table
                        $point_reward_data_e['employee_id'] = $data['uid'];
                        $point_reward_data_e['deduct_attendence_point'] = (int)$attendence_point;
                        $point_reward_data_e['date'] = $date;

                        $respo_s = $this->deduct_attendence_point_to_reward($point_reward_data_e);
                    }
                }
            }
        }

        if ($respo_s) {
            return true;
        } else {
            return false;
        }
    }

    /*Insert attendence point to employee point_reward database table*/
    private function add_attendence_point_to_reward($data = array())
    {
        $date = Carbon::parse($data['date']);
        $date_y = $date->year;
        $date_m = $date->month;
        $data['date'] = $date;

        $point_reward_rec = DB::table("reward_points")
            ->where('employee_id', $data['employee_id'])
            ->whereNull('deleted_at')
            ->whereYear('date', $date_y)
            ->whereMonth('date', $date_m)
            ->first();

        if ($point_reward_rec && $point_reward_rec->id != null) {

            // Adding attendence point with existing attendence reward point, if employee already exists in point_reward table..
            $attendence_point = (int)$point_reward_rec->attendance + (int)$data['attendence_point'];
            $total = (int)$point_reward_rec->management + (int)$point_reward_rec->collaborative + $attendence_point;
            $point_reward_data['attendance'] = $attendence_point;
            $point_reward_data['total'] = $total;

            $pointRewardRecord = RewardPoint::find($point_reward_rec->id);
            // Update the record with new data
            $update_reward_point = $pointRewardRecord->update($point_reward_data);

            if ($update_reward_point) {
                return true;
            } else {
                return false;
            }
        } else {
            // Inserting attendence point, if employee not exists in point_reward table..
            $point_reward_insert['date'] = $date;
            $point_reward_insert['attendance'] = $data['attendence_point'];
            $point_reward_insert['total'] = $data['attendence_point'];
            $point_reward_insert['employee_id'] = $data['employee_id'];

            $insert_reward_point = RewardPoint::create($point_reward_insert);

            if ($insert_reward_point) {
                return true;
            } else {
                return false;
            }
        }
    }

    /*Deduct attendence point to employee point_reward database table*/
    private function deduct_attendence_point_to_reward($data = array())
    {
        $date = Carbon::parse($data['date']);
        $date_y = $date->year;
        $date_m = $date->month;
        $data['date'] = $date;

        $point_reward_rec = DB::table("reward_points")
            ->where('employee_id', $data['employee_id'])
            ->whereNull('deleted_at')
            ->whereYear('date', $date_y)
            ->whereMonth('date', $date_m)
            ->first();

        if ($point_reward_rec && $point_reward_rec->id != null) {

            // Adding attendence point with existing attendence reward point, if employee already exists in point_reward table..
            $attendence_point = (int)$point_reward_rec->attendance - (int)$data['deduct_attendence_point'];
            $total = (int)$point_reward_rec->management + (int)$point_reward_rec->collaborative + $attendence_point;
            $point_reward_data['attendance'] = $attendence_point;
            $point_reward_data['total'] = $total;

            $pointRewardRecord = RewardPoint::find($point_reward_rec->id);
            // Update the record with new data
            $update_reward_point = $pointRewardRecord->update($point_reward_data);

            if ($update_reward_point) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function employee_attn_history($data)
    {
        $att_dates = date("Y-m-d", strtotime($data['time']));
        // Convert the given date to a Carbon instance
        $date = Carbon::createFromFormat('Y-m-d', $att_dates);
        // Get the next day's date
        $nextDayDate = $date->addDay()->toDateString();

        $att_in = DB::table('attendances')
            ->where('employee_id', $data['uid'])
            ->whereNull('deleted_at')
            ->whereRaw("time > ?", [$att_dates])
            ->whereRaw("time < ?", [$nextDayDate])
            ->orderBy('id', 'ASC')
            ->count();

        return $att_in;
    }

    public function employee_attn_in_time($data)
    {
        $attendence = DB::table('attendances as a')
            ->selectRaw('a.time, MIN(a.time) as intime, MAX(a.time) as outtime, a.employee_id as uid')
            ->where('a.time', '>', date('Y-m-d', strtotime($data['time'])))
            ->where('a.employee_id', $data['uid'])
            ->whereNull('a.deleted_at')
            ->orderBy('a.time', 'ASC')
            ->get();

        $in_time = null;
        if (!empty($attendence[0]->intime)) {
            $in_time = Carbon::createFromFormat('Y-m-d H:i:s', $attendence[0]->intime)->format('H:i');
        }

        return $in_time;
    }

    /**
     * Calculating totalNetworkHours for an employee current_day
     */
    public function employee_worked_hour_today($employee_id, $mydate)
    {

        $totalhour = 0;
        $totalwasthour = 0;
        $totalnetworkhour = 0;

        $attenddata = DB::table('attendances as a')
            ->select('a.time', DB::raw('MIN(a.time) as intime'), DB::raw('MAX(a.time) as outtime'), 'a.employee_id as uid')
            ->where('a.time', 'LIKE', '%' . date("Y-m-d", strtotime($mydate)) . '%')
            ->where('a.employee_id', $employee_id)
            ->whereNull('a.deleted_at')
            ->get();

        // Getting totalWorkHours
        $date_a = Carbon::createFromFormat('Y-m-d H:i:s', $attenddata[0]->outtime);
        $date_b = Carbon::createFromFormat('Y-m-d H:i:s', $attenddata[0]->intime);
        $interval = $date_a->diff($date_b);

        $totalwhour = $interval->format('%h:%i:%s');

        // End of Getting totalWorkHours

        $att_dates = date("Y-m-d", strtotime($attenddata[0]->time));
        // Convert the given date to a Carbon instance
        $exist_date = Carbon::createFromFormat('Y-m-d', $att_dates);
        // Get the next day's date
        $nextDayDate = $exist_date->addDay()->toDateString();
        $att_in = DB::table('attendances as a')
            ->select('a.*', 'b.first_name', 'b.last_name')
            ->leftJoin('employees as b', 'a.employee_id', '=', 'b.id')
            ->where('a.employee_id', $attenddata[0]->uid)
            ->whereRaw("a.time > ?", [$att_dates])
            ->whereRaw("a.time < ?", [$nextDayDate])
            ->whereNull('a.deleted_at')
            ->orderBy('a.time', 'ASC')
            ->get();

        $ix = 1;
        $in_data = [];
        $out_data = [];
        foreach ($att_in as $attendancedata) {

            if ($ix % 2) {
                $status = "IN";
                $in_data[$ix] = $attendancedata->time;
            } else {
                $status = "OUT";
                $out_data[$ix] = $attendancedata->time;
            }
            $ix++;
        }

        $result_in = array_values($in_data);
        $result_out = array_values($out_data);
        $total = [];
        $count_out = count($result_out);

        if ($count_out >= 2) {
            $n_out = $count_out - 1;
        } else {
            $n_out = 0;
        }
        for ($i = 0; $i < $n_out; $i++) {

            $date_a = Carbon::parse($result_in[$i + 1]);
            $date_b = Carbon::parse($result_out[$i]);
            $interval = $date_a->diff($date_b);

            $total[$i] = $interval->format('%h:%i:%s');
        }

        $hou = 0;
        $min = 0;
        $sec = 0;
        $totaltime = '00:00:00';
        $length = sizeof($total);

        for ($x = 0; $x <= $length; $x++) {
            $split = explode(":", @$total[$x]);
            $hou += @(int)$split[0];
            $min += @$split[1];
            $sec += @$split[2];
        }

        $seconds = $sec % 60;
        $minutes = $sec / 60;
        $minutes = (int)$minutes;
        $minutes += $min;
        $hours = $minutes / 60;
        $minutes = $minutes % 60;
        $hours = (int)$hours;
        $hours += $hou % 24;

        $totalwasthour = $hours . ":" . $minutes . ":" . $seconds;

        $date_a = Carbon::parse($totalwhour);
        $date_b = Carbon::parse($totalwasthour);
        $networkhours = $date_a->diff($date_b);

        $totalnetworkhour = $networkhours->h;

        return (int)$totalnetworkhour;
    }

    /**
     * Get Point Settings
     */
    public function get_last_record()
    {
        // point_settings info
        return PointSettings::select('*')
            ->first();
    }

    public function edit(ManualAttendance $attendance)
    {
        $attendance->load('employee');
        $employee = Employee::where('is_active', 1)->get();

        return view('humanresource::attendance.edit', compact('attendance', 'employee'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'employee_id' => 'required',
            'time' => 'required',
        ]);

        $attendance_history = [
            'uid'    => $request->input('employee_id'),
            'state'  => 1,
            'id'     => 0,
            'time'   => $request->input('time'),
        ];


        $neTime = Carbon::parse($request->time)->format('Y-m-d H:i:s');
        $validated['time'] = $neTime;

        // manual attendance
        $resp = $attendance->update($validated);
        if ($resp) {

            $resp_attend = $this->insert_attendance_point($attendance_history);
            return redirect()->route('reports.attendance-log-details', $attendance->employee_id)->with('success', localize('data_save'));
        } else {
            return redirect()->back()->with('error', localize('error'));
        }
    }

    /**
     * @param Attendance $attendance
     */
    public function destroy(Attendance $attendance)
    {

        $attendance_history = [
            'uid'    => $attendance->employee_id,
            'state'  => 1,
            'id'     => 0,
            'time'   => $attendance->time,
        ];

        $resp = $attendance->delete();
        if ($resp) {
            $resp_attend = $this->insert_attendance_point($attendance_history);
            return response()->json(['data' => null, 'message' => localize('data_deleted_successfully'), 'status' => 200]);
        } else {
            return response()->json(['data' => null, 'message' => localize('something_error'), 'status' => 500]);
        }
    }

    public function bulk(Request $request)
    {
        $request->validate([
            'bulk' => 'required|mimes:xlsx|max:2048',
        ], [
            'bulk.required' => 'The file is required',
            'bulk.mimes' => 'The file must be an Excel file',
            'bulk.max' => 'The file size must be less than 2MB',
        ]);

        try {
            $export = Excel::import(new AttendanceImport(), $request->file('bulk'));
            Toastr::success(localize('data_imported_successfully'));
            return redirect()->route('attendances.create');
        } catch (\Exception $e) {
            Toastr::error(localize('operation_failed' . $e->getMessage()));
            return redirect()->route('attendances.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function monthlyAttendanceBulkImport(Request $request)
    {
        $request->validate([
            'monthly_bulk' => 'required|mimes:xlsx|max:2048',
        ], [
            'monthly_bulk.required' => 'The file is required',
            'monthly_bulk.mimes' => 'The file must be an Excel file',
            'monthly_bulk.max' => 'The file size must be less than 2MB',
        ]);

        try {
            Excel::import(new AttendanceImport(), $request->file('monthly_bulk'));

            return redirect()->route('attendances.monthlyCreate')->with('success', localize('data_imported_successfully'));
        } catch (\Exception $e) {
            return $e;
            Toastr::error(localize('operation_failed'));

            return redirect()->route('attendances.monthlyCreate');
        }
    }

    public function monthlyCreate()
    {
        $employee = Employee::where('is_active', 1)->get();

        return view('humanresource::attendance.monthlycreate', compact('employee'));
    }

    public function monthlyStore(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $in_time = Carbon::parse($request->in_time)->format('H:i:s');
        $out_time = Carbon::parse($request->out_time)->format('H:i:s');
        $manualAttendancesIn = [];
        $manualAttendancesOut = [];
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $weeklyHoliday = WeekHoliday::first();

        $publicHoliday = Holiday::whereMonth('start_date', $month)->whereYear('start_date', $year)->get()->toArray();
        $p_holidays = [];
        // public holiday day name add in $p_holidays array
        foreach ($publicHoliday as $key => $value) {
            if ($value['total_day'] > 1) {
                // carbon period start date and end date
                $start_date = Carbon::parse($value['start_date']);
                $end_date = Carbon::parse($value['end_date']);
                $period = \Carbon\CarbonPeriod::create($start_date, $end_date);
                foreach ($period as $date) {
                    $p_holidays[] = $date->format('d');
                }
            } else {
                $p_holidays[] = Carbon::parse($value['start_date'])->format('d');
            }
        }

        $holidays = array_map('trim', explode(',', strtoupper($weeklyHoliday->dayname)));

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $checkDay = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-' . $day)->format('l');
            if (in_array(strtoupper($checkDay), $holidays) || in_array((string) $day, $p_holidays)) {
                continue;
            }

            $inTime = Carbon::createFromFormat('Y-m-d H:i:s', $year . '-' . $month . '-' . $day . ' ' . $in_time);
            $outTime = Carbon::createFromFormat('Y-m-d H:i:s', $year . '-' . $month . '-' . $day . ' ' . $out_time);
            $manualAttendancesIn[] = [
                'employee_id' => $request->employee_id,
                'time' => $inTime,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $manualAttendancesOut[] = [
                'employee_id' => $request->employee_id,
                'time' => $outTime,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        try {
            DB::beginTransaction();
            // attendance
            Attendance::insert(array_merge($manualAttendancesIn, $manualAttendancesOut));
            DB::commit();

            return redirect()->route('attendances.monthlyCreate')->with('success', localize('data_save'));
        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->route('attendances.monthlyCreate')->with('error', localize('error'));
        }
    }

    public function missingAttendance(Request $request)
    {
        $date = $request->date;
        // if date is not set then set current date
        if (!$date) {
            $date = Carbon::now()->format('Y-m-d');
        } else {
            $date = Carbon::parse($date)->format('Y-m-d');
        }
        $missingAttendance = Employee::with(['position:id,position_name'])->doesntHave('attendances', 'and', function ($query) use ($date) {
            $query->whereDate('time', $date);
        })->where('is_active', true)->get(['id', 'first_name', 'middle_name', 'last_name', 'position_id', 'employee_id']);
        return view('humanresource::attendance.missing', compact('missingAttendance', 'date'));
    }

    public function missingAttendanceStore(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|array',
            'employee_id.*' => 'required|integer',
            'in_time' => 'required|array',
            'in_time.*' => 'required|date_format:H:i',
            'out_time' => 'required|array',
            'out_time.*' => 'required|date_format:H:i',
            'date' => 'required|date',
        ]);
        try {
            DB::beginTransaction();
            $in_time = $request->in_time;
            $out_time = $request->out_time;
            $employee_id = $request->employee_id;
            $date = Carbon::parse($request->date);

            foreach ($employee_id as $key => $value) {
                $inDateTime = $date->copy()->modify($in_time[$key]);
                $outDateTime = $date->copy()->modify($out_time[$key]);

                Attendance::create([
                    'employee_id' => $value,
                    'time' => $inDateTime,
                ]);
                Attendance::create([
                    'employee_id' => $value,
                    'time' => $outDateTime,
                ]);
            }

            DB::commit();
            return response()->json(['data' => null, 'message' => localize('attendance_save_successfully'), 'status' => 200]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['data' => null, 'message' => localize('something_went_wrong') . $th->getMessage(), 'status' => 500]);
        }
    }
}
