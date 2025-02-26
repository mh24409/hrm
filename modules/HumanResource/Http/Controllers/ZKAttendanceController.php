<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Exports\MonthAttendanceExport;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\HumanResource\Entities\Attendance;
use Modules\HumanResource\Entities\Employee;
use Rats\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Str;

class ZKAttendanceController extends Controller
{
    public function ZkAttendance(Request $request)
    {
        try {
            $groupedLogs = $request->data;
            $deviceIp = $request->deviceIp;
            DB::beginTransaction();

            foreach ($groupedLogs as $key => $records) {
                $records = collect($records)->sortBy('timestamp')->values();

                list($deviceIp, $zk_userId, $date) = explode('_', $key);

                $checkIn = $records->first();
                $checkOut = $records->last();

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
            return response()->json([
                'data' => null,
                'message' => localize('attendance_save_successfully'),
                'status' => 200
            ]);
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
        if ($enterMonth == $currentMonth) {
            try {
                $deviceIp = $request->deviceIp;
                $groupedLogs = $request->data;
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
                return response()->json([
                    'data' => null,
                    'message' => localize('attendance_save_successfully'),
                    'status' => 200
                ]);
            } catch (\Throwable $th) {
                DB::rollback();
                return response()->json([
                    'data' => null,
                    'message' => localize('something_went_wrong') . ' ' . $th->getMessage(),
                    'status' => 500
                ]);
            }
        } else {
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
                    return response()->json([
                        'data' => null,
                        'message' => localize('attendance_save_successfully'),
                        'status' => 200
                    ]);
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
    public function ZkMonthlyAttendance(Request $request)
    {
        try {
            $formattedLogs = [];
            $lastMonthLogs = $request->data;
            foreach ($lastMonthLogs as $record) {
                $zk_userId = $record['id'];
                $timestamp = $record['timestamp'];
                $deviceIp = $record['device_ip'];
                $user = DB::table('employees')->where('zk_id', $zk_userId)->first();
                if ($user) {
                    $formattedLogs[] = [
                        'id' => $user->id,
                        'uuid' => $user->uuid,
                        'name' => $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name,
                        'zk_id' => $user->zk_id,
                        'device_ip' => $deviceIp,
                        'timestamp' => $timestamp,
                    ];
                }
            }
            $year = Carbon::now()->subMonth()->format('Y');
            $lastMonthName = Carbon::now()->subMonth()->format('F');
            $fileName = Str::slug($lastMonthName) . '_' . $year . '.xlsx';
            Excel::store(new MonthAttendanceExport($formattedLogs), $fileName, 'zk_attendance');
            return response()->json([
                'data' => null,
                'message' => localize('attendance_save_successfully'),
                'status' => 200
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'data' => null,
                'message' => localize('something_went_wrong') . ' ' . $th->getMessage(),
                'status' => 500
            ]);
        }
    }
}
