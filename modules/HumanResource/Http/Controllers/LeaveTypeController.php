<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Modules\HumanResource\Entities\LeaveType;

class LeaveTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_leave_type')->only('index', 'show');
        $this->middleware('permission:create_leave_type')->only(['create', 'store']);
        $this->middleware('permission:update_leave_type')->only(['edit', 'update']);
        $this->middleware('permission:delete_leave_type')->only('destroy');
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
        $request->validate([
            'leave_type' => 'required',
            'leave_code' => 'required',
            'leave_days' => 'required'
        ]);

        LeaveType::create($request->all());
        Toastr::success('leave Type added successfully :)','Success');
        return redirect()->route('leave.leaveTypeindex');  
    }

    public function update(Request $request, $uuid)
    {
        $request->validate([
            'leave_type' => 'required',
            'leave_code' => 'required',
            'leave_days' => 'required'
        ]);
        $leave_type = LeaveType::where('uuid', $uuid)->firstOrFail();
        $leave_type->update($request->all());
        Toastr::success('leave Type updated successfully :)','Success');
        return redirect()->route('leave.leaveTypeindex'); 
    }

 
    public function destroy($uuid)
    {
        LeaveType::where('uuid' , $uuid)->delete();
        Toastr::success('Leave Type deleted successfully :)','Success');
        return response()->json(['success' => 'success']);
    }
}
