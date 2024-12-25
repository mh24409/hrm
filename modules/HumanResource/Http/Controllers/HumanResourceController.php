<?php

namespace Modules\HumanResource\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HumanResource\Entities\Attendance;
use Modules\HumanResource\Entities\Department;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\ManualAttendance;

class HumanResourceController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::where('is_active', true)->get();

        $employees = Employee::whereNotNull('id');

        if ($request->department_id) {
            $employees = $employees->where('department_id', $request->department_id);
        }

        if ($request->branch_id) {
            $employees = $employees->where('branch_id', $request->branch_id);
        }

        $total_employee = $employees->where('is_active', true)->count();

        $next60days = Carbon::now()->addDays(60)->format('Y-m-d');
        $today = Carbon::now()->format('Y-m-d');
        $contract_renew_employees = $employees->whereBetween('contract_end_date', [$today, $next60days])->count();

        $today_attendance = ManualAttendance::groupBy('employee_id')->whereDate('time', $today)->get()->count();
        $today_absence = $total_employee - $today_attendance;

        return view('humanresource::index', [
            'total_employee' => $total_employee,
            'today_attenedence' => $today_attendance,
            'today_absense' => $today_absence,
            'contract_renew_employees' => $contract_renew_employees,
            'departments' => $departments,
            'request' => $request,
        ]);
    }
}
