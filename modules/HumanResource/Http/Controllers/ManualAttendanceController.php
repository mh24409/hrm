<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Exports\MonthAttendanceExport;
use App\Imports\AttendanceImport;
use App\Imports\ManualAttendanceImport;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Modules\HumanResource\Entities\Attendance;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\Holiday;
use Modules\HumanResource\Entities\ManualAttendance;
use Modules\HumanResource\Entities\WeekHoliday;
use Modules\HumanResource\Entities\PointSettings;
use Modules\HumanResource\Entities\PointAttendance;
use Modules\HumanResource\Entities\RewardPoint;
use Rats\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Str;

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
    public function index(Request $request)
    {
        $date = $request->date ?? Carbon::now()->toDateString();

        $employees = Employee::with(['position:id,position_name', 'attendances' => function ($query) use ($date) {
            $query->whereDate('time', $date);
        }])
            ->where('is_active', true)
            ->get(['id', 'first_name', 'middle_name', 'last_name', 'position_id', 'employee_id']);

        // Process attendance times for check-in and check-out
        $employees = $employees->map(function ($employee) {
            $attendances = $employee->attendances->sortBy('time'); // Sort by time ascending

            $employee->check_in = $attendances->first()?->time ? Carbon::parse($attendances->first()->time)->format('H:i:s') : null;
            $employee->check_out = $attendances->last()?->time ? Carbon::parse($attendances->last()->time)->format('H:i:s') : null;

            unset($employee->attendances); // Remove raw attendances if not needed

            return $employee;
        });

        // return response()->json($employees);
        return view('humanresource::attendance.index', compact('employees', 'date'));
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
            return $e;
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

        $holidays = array_map('trim', explode(',', strtoupper(isset($weeklyHoliday->dayname) ? $weeklyHoliday->dayname : '')));
        $weekendholidays = ['friday', 'saturday'];
        $weekendholidays = array_map('ucfirst', $weekendholidays);
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $checkDay = Carbon::createFromFormat('Y-m-d', $year . '-' . $month . '-' . $day)->format('l');
            if (in_array(strtoupper($checkDay), $holidays) || in_array((string) $day, $p_holidays) || in_array((string) $checkDay, $weekendholidays)) {
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
    public function editAttendance(Request $request)
    {
        $date = $request->date;
        if (!$date) {
            $date = Carbon::now()->format('Y-m-d');
        } else {
            $date = Carbon::parse($date)->format('Y-m-d');
        }
         $attendance = Employee::with(['position:id,position_name'])->with('attendances', function ($query) use ($date) {
            $query->whereDate('time', $date);
        })->whereHas('attendances', function ($query) use ($date) {
            $query->whereDate('time', $date);
        })->where('is_active', true)->get(['id', 'first_name', 'middle_name', 'last_name', 'position_id', 'employee_id']);
        return view('humanresource::attendance.editAttendance', compact('attendance', 'date'));
    }
    public function editAttendanceStore(Request $request)
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
                 $checkin = Attendance::where('employee_id', $value)->whereDate('time', $date)->get();
                if ($checkin[0]) {
                    $checkin[0]->update([
                        'time' => $inDateTime
                    ]) ;
                } else {
                    Attendance::create([
                       'employee_id' => $value,
                       'time' => $inDateTime,
                   ]);
                }
                if ($checkin[1]) {
                    $checkin[1]->update([
                        'time' => $outDateTime
                    ]) ;
                } else {
                       Attendance::create([
                        'employee_id' => $value,
                        'time' => $outDateTime,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['data' => null, 'message' => localize('attendance_save_successfully'), 'status' => 200]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['data' => null, 'message' => localize('something_went_wrong') . $th->getMessage(), 'status' => 500]);
        }
    }
    public function checkIn(Request $request)
    {
        $date = $request->date;
        if (!$date) {
            $date = Carbon::now()->format('Y-m-d');
        } else {
            $date = Carbon::parse($date)->format('Y-m-d');
        }
        $missingAttendance = Employee::with(['position:id,position_name'])->doesntHave('attendances', 'and', function ($query) use ($date) {
            $query->whereDate('time', $date);
        })->where('is_active', true)->get(['id', 'first_name', 'middle_name', 'last_name', 'position_id', 'employee_id']);
        return view('humanresource::attendance.checkin', compact('missingAttendance', 'date'));
    }
    private function getDummyAttendance()
    {
        $attendance_data = '[
            {"uid":1,"id":"1","state":15,"timestamp":"2025-01-08 09:00:00","type":255},
            {"uid":2,"id":"1","state":15,"timestamp":"2025-01-08 17:30:00","type":255},
            {"uid":1,"id":"2","state":15,"timestamp":"2025-01-08 09:00:00","type":255},
            {"uid":2,"id":"2","state":15,"timestamp":"2025-01-08 17:30:00","type":255},
            {"uid":1,"id":"3","state":15,"timestamp":"2025-01-08 09:00:00","type":255},
            {"uid":2,"id":"3","state":15,"timestamp":"2025-01-08 17:30:00","type":255},
            {"uid":1,"id":"4","state":15,"timestamp":"2025-01-08 09:00:00","type":255},
            {"uid":2,"id":"4","state":15,"timestamp":"2025-01-08 17:30:00","type":255}
        ]';

        return  json_decode($attendance_data, true);
    }
    public function checkInStore(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|array',
            'employee_id.*' => 'required|integer',
            'in_time' => 'required|array',
            'in_time.*' => 'required|date_format:H:i',
            'date' => 'required|date',
        ]);
        try {
            DB::beginTransaction();
            $in_time = $request->in_time;
            $employee_id = $request->employee_id;
            $date = Carbon::parse($request->date);
            foreach ($employee_id as $key => $value) {
                $inDateTime = $date->copy()->modify($in_time[$key]);
                Attendance::create([
                    'employee_id' => $value,
                    'time' => $inDateTime,
                ]);
            }
            DB::commit();
            return response()->json(['data' => null, 'message' => localize('attendance_save_successfully'), 'status' => 200]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['data' => null, 'message' => localize('something_went_wrong') . $th->getMessage(), 'status' => 500]);
        }
    }
    private function isDeviceReachable($ip, $port)
{
    $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
    if (!$sock) {
        return false;
    }

    socket_set_option($sock, SOL_SOCKET, SO_RCVTIMEO, ["sec" => 2, "usec" => 0]); // Timeout: 2 seconds
    $result = @socket_connect($sock, $ip, $port);
    socket_close($sock);

    return $result;
}
    public function ZkAttendance()
    {
        $devices = explode(',', env('DEVICES', ''));

        if (empty($devices)) {
            return response()->json([
                'data' => null,
                'message' => 'No devices found in environment configuration.',
                'status' => 500
            ]);
        }

        $attendance_data = [];

        // Fetch attendance from both devices
        foreach ($devices as $deviceIp) {
            $deviceIp = trim($deviceIp);
                if (!$this->isDeviceReachable($deviceIp, 4370)) {
                    // Log unreachable device but continue with others
                    Log::warning("Device at $deviceIp is unreachable.");
                    continue;
                }
            // return $deviceIp ;
            try {
                $zk = new ZKTeco($deviceIp);
                $zk->connect();
                $zk->enableDevice();
                $device_data = $zk->getAttendance();
                // $device_data = $this->getDummyAttendance();
                // Append device IP to each record for unique identification
                foreach ($device_data as &$record) {
                    $record['device_ip'] = $deviceIp;
                }

                $attendance_data = array_merge($attendance_data, $device_data);

                $zk->disableDevice();
                $zk->disconnect();
            } catch (\Exception $e) {
                return response()->json([
                    'data' => null,
                    'message' => 'Failed to fetch data from device ' . $deviceIp . ': ' . $e->getMessage(),
                    'status' => 500
                ]);
            }
        }

        $today = Carbon::today()->toDateString();

        // Filter logs for today
        $todayLogs = collect($attendance_data)->filter(function ($log) use ($today) {
            return Carbon::parse($log['timestamp'])->toDateString() === $today;
        });

        // Group logs by (device_ip + zk_id) + date
        $groupedLogs = $todayLogs->groupBy(function ($log) {
            return $log['device_ip'] . '_' . $log['id'] . '_' . Carbon::parse($log['timestamp'])->toDateString();
        });

        try {
            DB::beginTransaction();

            foreach ($groupedLogs as $key => $records) {
                $records = collect($records)->sortBy('timestamp')->values();

                list($deviceIp, $zk_userId, $date) = explode('_', $key);

                $checkIn = $records->first();
                $checkOut = $records->last();

                // Find employee using both zk_id and device_ip
                $user = DB::table('employees')
                    ->where('zk_id', $zk_userId)
                    ->where('device_ip', $deviceIp)
                    ->first();

                if ($user) {
                    $attendance_exist = Attendance::where('employee_id', $user->id)
                        ->whereDate('time', $date)
                        ->count();

                    if ($attendance_exist == 0 || $attendance_exist == 1) {
                        if ($attendance_exist == 1) {
                            Attendance::where('employee_id', $user->id)->whereDate('time', $date)->forceDelete();
                        }

                        Attendance::create([
                            'employee_id' => $user->id,
                            'time' => $checkIn['timestamp'],
                        ]);
                        Attendance::create([
                            'employee_id' => $user->id,
                            'time' => $checkOut['timestamp'],
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('attendances.create')->with('success', localize('attendance_save_successfully'));
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'data' => null,
                'message' => localize('something_went_wrong') . ' ' . $th->getMessage(),
                'status' => 500
            ]);
        }
    }
    public function ZkAttendanceByDay(Request $request)
    {
        $requestDate = $request->date;
        $date = Carbon::parse($requestDate)->toDateString();
        $year = Carbon::now()->format('Y');
        $enterMonth = Carbon::parse($requestDate)->format('m');
        $currentMonth = Carbon::now()->format('m');

        $devices = explode(',', env('DEVICES', ''));

        if (empty($devices)) {
            return response()->json([
                'data' => null,
                'message' => 'No devices found in environment configuration.',
                'status' => 500
            ]);
        }

        $attendance_data = [];

        if ($enterMonth == $currentMonth) {
            // Fetch attendance from all devices
            foreach ($devices as  $deviceIp) {
                try {
                    $zk = new ZKTeco($deviceIp);
                    $zk->connect();
                    $zk->enableDevice();
                    $device_data = $zk->getAttendance();
                    // $device_data = $this->getDummyAttendance();

                    // Append device IP to each record for unique identification
                    foreach ($device_data as &$record) {
                        $record['device_ip'] = $deviceIp;
                    }

                    $attendance_data = array_merge($attendance_data, $device_data);

                    $zk->disableDevice();
                    $zk->disconnect();
                } catch (\Exception $e) {
                    return response()->json([
                        'data' => null,
                        'message' => 'Failed to fetch data from device ' . $deviceIp . ': ' . $e->getMessage(),
                        'status' => 500
                    ]);
                }
            }

            // Filter logs for the requested date
            $filteredLogs = collect($attendance_data)->filter(function ($log) use ($date) {
                return Carbon::parse($log['timestamp'])->toDateString() === $date;
            });

            // Group logs by (device_ip + zk_id) + date
            $groupedLogs = $filteredLogs->groupBy(function ($log) {
                return $log['device_ip'] . '_' . $log['id'] . '_' . Carbon::parse($log['timestamp'])->toDateString();
            });

            try {
                DB::beginTransaction();
                Attendance::whereDate('time', $date)->forceDelete();

                foreach ($groupedLogs as $key => $records) {
                    $records = collect($records)->sortBy('timestamp')->values();
                    list($deviceIp, $zk_userId, $date) = explode('_', $key);

                    $checkIn = $records->first();
                    $checkOut = $records->last();

                    // Find employee using both zk_id and device_ip
                    $user = DB::table('employees')
                        ->where('zk_id', $zk_userId)
                        ->where('device_ip', $deviceIp)
                        ->first();

                    if ($user) {
                        Attendance::create([
                            'employee_id' => $user->id,
                            'time' => $checkIn['timestamp'],
                        ]);
                        Attendance::create([
                            'employee_id' => $user->id,
                            'time' => $checkOut['timestamp'],
                        ]);
                    }
                }

                DB::commit();
                return redirect()->route('attendances.create')->with('success', localize('attendance_save_successfully'));
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json([
                    'data' => null,
                    'message' => localize('something_went_wrong') . ' ' . $th->getMessage(),
                    'status' => 500
                ]);
            }
        } else {
            // Handle past month attendance from an Excel file
            $lastMonthName = Carbon::now()->subMonth()->format('F');
            $fileName = Str::slug($lastMonthName) . '_' . $year . '.xlsx';
            $filePath = storage_path('zk_attendance/' . $fileName);

            if (file_exists($filePath)) {
                $attendance_data = Excel::toArray(new \App\Imports\AttendanceImport, $filePath);
                $filteredLogs = collect($attendance_data[0])->filter(function ($log) use ($date) {
                    return Carbon::parse($log['timestamp'])->toDateString() === $date;
                });

                $groupedLogs = $filteredLogs->groupBy(function ($log) {
                    return $log['device_ip'] . '_' . $log['zk_id'] . '_' . Carbon::parse($log['timestamp'])->toDateString();
                });

                try {
                    DB::beginTransaction();
                    Attendance::whereDate('time', $date)->forceDelete();

                    foreach ($groupedLogs as $key => $records) {
                        $records = collect($records)->sortBy('timestamp')->values();
                        list($deviceIp, $zk_userId, $date) = explode('_', $key);

                        $checkIn = $records->first();
                        $checkOut = $records->last();

                        // Find employee using both zk_id and device_ip
                        $user = DB::table('employees')
                            ->where('zk_id', $zk_userId)
                            ->where('device_ip', $checkIn['device_ip'])
                            ->first();

                        if ($user) {
                            Attendance::create([
                                'employee_id' => $user->id,
                                'time' => $checkIn['timestamp'],
                            ]);
                            Attendance::create([
                                'employee_id' => $user->id,
                                'time' => $checkOut['timestamp'],
                            ]);
                        }
                    }

                    DB::commit();
                    return redirect()->route('attendances.create')->with('success', localize('attendance_save_successfully'));
                } catch (\Throwable $th) {
                    DB::rollback();
                    return response()->json([
                        'data' => null,
                        'message' => localize('something_went_wrong') . ' ' . $th->getMessage(),
                        'status' => 500
                    ]);
                }
            } else {
                return response()->json([
                    'data' => null,
                    'message' => 'Attendance file not found for the previous month',
                    'status' => 404
                ]);
            }
        }
    }


    // run every first day of month after 12 am before start work
    public function ZkMonthlyAttendance()
    {
        // Define ZKTeco device IPs
        $devices = explode(',', env('DEVICES', ''));

    if (empty($devices)) {
        return response()->json([
            'data' => null,
            'message' => 'No devices found in environment configuration.',
            'status' => 500
        ]);
    }

        $attendance_data = [];

        // Fetch attendance from all devices
        foreach ($devices as $deviceIp) {
            try {
                $zk = new ZKTeco($deviceIp);
                $zk->connect();
                $zk->enableDevice();
                $device_data = $zk->getAttendance();
                // $device_data = $this->getDummyAttendance(); // Dummy data for testing

                // Append device IP to each record
                foreach ($device_data as &$record) {
                    $record['device_ip'] = $deviceIp;
                }

                $attendance_data = array_merge($attendance_data, $device_data);

                // Clear attendance after fetching
                $zk->clearAttendance();
                $zk->disableDevice();
                $zk->disconnect();
            } catch (\Exception $e) {
                return response()->json([
                    'data' => null,
                    'message' => 'Failed to fetch data from device ' . $deviceIp . ': ' . $e->getMessage(),
                    'status' => 500
                ]);
            }
        }

        // Get last month details
        $year = Carbon::now()->subMonth()->format('Y');
        $lastMonth = Carbon::now()->subMonth()->format('m');
        $lastMonthName = Carbon::now()->subMonth()->format('F');

        // Filter records from last month
        $lastMonthLogs = collect($attendance_data)->filter(function ($log) use ($lastMonth) {
            return Carbon::parse($log['timestamp'])->format('m') === $lastMonth;
        });

        try {
            $formattedLogs = [];

            foreach ($lastMonthLogs as $record) {
                $zk_userId = $record['id'];
                $timestamp = $record['timestamp'];
                $deviceIp = $record['device_ip'];

                // Get user details using zk_id
                $user = DB::table('employees')->where('zk_id', $zk_userId)->first();

                if ($user) {
                    // return $user ;
                    $formattedLogs[] = [
                        'id' => $user->id,
                        'uuid' => $user->uuid,
                        'name' => $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name,
                        'zk_id' => $user->zk_id,
                        'device_ip' => $deviceIp, // ✅ Ensure device IP is included
                        'timestamp' => $timestamp,
                    ];
                }
            }

            // ✅ Save ONE Excel file with all data
            $fileName = Str::slug($lastMonthName) . '_' . $year . '.xlsx';
            Excel::store(new MonthAttendanceExport($formattedLogs), $fileName, 'zk_attendance');

            return redirect()->route('attendances.create')->with('success', localize('data_save'));
        } catch (\Throwable $th) {
            DB::rollback(); // Rollback if there's an error
            return response()->json([
                'data' => null,
                'message' => localize('something_went_wrong') . ' ' . $th->getMessage(),
                'status' => 500
            ]);
        }
    }


    // public function ZkAttendanceByDate(Request $request)
    // {
    //     $zk = new ZKTeco('192.168.1.201');
    //     $zk->connect();
    //     $zk->enableDevice();

    //     $attendance_data = $zk->getAttendance();

    //     $today = Carbon::parse($request->date)->toDateString();

    //     $todayLogs = collect($attendance_data)->filter(function ($log) use ($today) {
    //         return Carbon::parse($log['timestamp'])->toDateString() === $today;
    //     });

    //     $groupedLogs = $todayLogs->groupBy(function ($log) {
    //         return $log['id'] . '_' . Carbon::parse($log['timestamp'])->toDateString();
    //     });

    //     try {
    //         DB::beginTransaction();

    //         foreach ($groupedLogs as $key => $records) {
    //             $records = collect($records)->sortBy('timestamp')->values();

    //             $zk_userId = explode('_', $key)[0];
    //             $date = explode('_', $key)[1];

    //             $checkIn = $records->first();
    //             $checkOut = $records->last();

    //             $user = DB::table('employees')->where('zk_id', $zk_userId)->first();

    //             if ($user) {
    //                 Attendance::create([
    //                     'employee_id' => $user->id,
    //                     'time' => $checkIn['timestamp'],
    //                 ]);
    //                 Attendance::create([
    //                     'employee_id' => $user->id,
    //                     'time' => $checkOut['timestamp'],
    //                 ]);
    //             }
    //         }

    //         DB::commit();

    //         return response()->json([
    //             'data' => null,
    //             'message' => localize('attendance_save_successfully'),
    //             'status' => 200
    //         ]);
    //     } catch (\Throwable $th) {
    //         DB::rollback(); // Rollback if there's an error
    //         return response()->json([
    //             'data' => null,
    //             'message' => localize('something_went_wrong') . ' ' . $th->getMessage(),
    //             'status' => 500
    //         ]);
    //     }
    // }
    public function checkOut(Request $request)
    {
        $date = $request->date;
        if (!$date) {
            $date = Carbon::now()->format('Y-m-d');
        } else {
            $date = Carbon::parse($date)->format('Y-m-d');
        }
        $missingAttendance = Employee::with(['position:id,position_name'])
            ->whereHas('attendances', function ($query) use ($date) {
                $query->whereDate('time', $date);
            }, '=', 1)
            ->where('is_active', true)->get(['id', 'first_name', 'middle_name', 'last_name', 'position_id', 'employee_id']);
        return view('humanresource::attendance.checkout', compact('missingAttendance', 'date'));
    }
    public function checkOutStore(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|array',
            'employee_id.*' => 'required|integer',
            'out_time' => 'required|array',
            'out_time.*' => 'required|date_format:H:i',
            'date' => 'required|date',
        ]);
        try {
            DB::beginTransaction();
            $out_time = $request->out_time;
            $employee_id = $request->employee_id;
            $date = Carbon::parse($request->date);

            foreach ($employee_id as $key => $value) {
                $outDateTime = $date->copy()->modify($out_time[$key]);
                Attendance::create([
                    'employee_id' => $value,
                    'time' => $outDateTime,
                ]);
                $attendance_history = [
                    'uid'    => $value,
                    'state'  => 1,
                    'id'     => 0,
                    'time'   => $outDateTime,
                ];
                $resp_attend = $this->insert_attendance_point($attendance_history);
            }

            DB::commit();
            return response()->json(['data' => null, 'message' => localize('attendance_save_successfully'), 'status' => 200]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['data' => null, 'message' => localize('something_went_wrong') . $th->getMessage(), 'status' => 500]);
        }
    }
}
