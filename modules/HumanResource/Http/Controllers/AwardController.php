<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HumanResource\DataTables\AwardDataTable;
use Modules\HumanResource\Entities\Award;
use Modules\HumanResource\Entities\Employee;

class AwardController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('permission:read_award', ['only' => ['index', 'show']]);
        $this->middleware('permission:create_award', ['only' => ['create', 'store']]);
        $this->middleware('permission:update_award', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_award', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AwardDataTable $dataTable)
    {
        $dbData = Award::all();
        $employees = Employee::where('is_active', 1)
            ->get(['id', 'first_name', 'last_name', 'middle_name']);

        return $dataTable->render('humanresource::award.index', compact('dbData', 'employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::where('is_active', 1)
            ->get(['id', 'first_name', 'last_name', 'middle_name']);
        return view('humanresource::award.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'gift' => 'required',
            'date' => 'required',
            'employee_id' => 'required',
            'awarded_by' => 'required',
        ]);

        if (Award::create($request->all())) {
            return response()->json(['error' => false, 'msg' => 'Award created successfully!']);
        } else {
            return response()->json(['error' => true, 'msg' => 'Something Went Wrong']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Award  $award
     * @return \Illuminate\Http\Response
     */
    public function edit(Award $award)
    {
        $employees = Employee::where('is_active', 1)
            ->get(['id', 'first_name', 'last_name', 'middle_name']);
        return view('humanresource::award.edit', compact('award', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Award  $award
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Award $award)
    {
        $request->validate([
            'name'          => 'required',
            'gift'          => 'required',
            'date'          => 'required',
            'employee_id'   => 'required',
            'awarded_by'    => 'required',
        ]);

        if ($award->update($request->all())) {
            return response()->json(['error' => false, 'msg' => 'Award updated successfully!']);
        } else {
            return response()->json(['error' => true, 'msg' => 'Something Went Wrong']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Award  $award
     * @return \Illuminate\Http\Response
     */
    public function destroy(Award $award)
    {
        $award->delete();
        return response()->json(['success' => 'success']);
    }
}
