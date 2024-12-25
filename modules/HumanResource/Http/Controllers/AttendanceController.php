<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Imports\AttendanceImport;
use App\Imports\ManualAttendanceImport;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\HumanResource\Entities\Attendance;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\ManualAttendance;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_attendance')->only(['index']);
        $this->middleware('permission:create_attendance', ['only' => ['create', 'store']]);
        $this->middleware('permission:update_attendance', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_attendance', ['only' => ['destroy']]);
        $this->middleware('permission:create_monthly_attendance | read_monthly_attendance', ['only' => ['monthlyCreate']]);
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
        $employee = Employee::where('is_active', 1)->get();
        return view('humanresource::attendance.create', compact('employee'));
    }

    public function monthlyCreate()
    {
        $employee = Employee::where('is_active', 1)->get();
        return view('humanresource::attendance.monthlycreate', compact('employee'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "employee_id" => 'required',
            "time" => 'required',
        ]);

        $neTime = Carbon::parse($request->time)->format('Y-m-d H:i:s');
        $validated['time'] = $neTime;
        Attendance::create($validated);
        ManualAttendance::create($validated);
        return redirect()->route('attendances.create')->with('success', localize('data_save'));
    }

    public function bulk(Request $request)
    {

        Excel::import(new AttendanceImport, $request->file('bulk'));
        Excel::import(new ManualAttendanceImport, $request->file('bulk'));

        return redirect()->route('attendances.create')->with('success', localize('data_save'));
    }

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
}
