<?php

namespace Modules\HumanResource\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HumanResource\Entities\Holiday;

class HolidayController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_holiday')->only('index', 'show');
        $this->middleware('permission:create_holiday')->only(['create', 'store']);
        $this->middleware('permission:update_holiday')->only(['edit', 'update']);
        $this->middleware('permission:delete_holiday')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $dbData = Holiday::paginate(30);
        return view('humanresource::holiday.index', compact('dbData'));

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
            'holiday_name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'total_day' => 'required',
        ]);

        // Check start date or end date already falls under another holiday
        $duplicate_start_date = Holiday::select('*')
            ->where('start_date', '>=', $request->input('start_date'))
            ->where('start_date', '<=', $request->input('end_date'))
            ->first();
        $duplicate_end_date = Holiday::select('*')
            ->where('end_date', '>=', $request->input('start_date'))
            ->where('end_date', '<=', $request->input('end_date'))
            ->first();
        if($duplicate_start_date != null || $duplicate_end_date != null){
            return redirect()->route('holiday.index')->with('fail', localize('please_check_selected_date_is_used_for_other_holiday'));
        }
        // End

        Holiday::create($validated);
        return redirect()->route('holiday.index')->with('success', localize('data_save'));
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
    public function update(Request $request, Holiday $holiday)
    {
        $validated = $request->validate([
            'holiday_name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'total_day' => 'required',
        ]);

        $holiday->update($validated);
        return redirect()->route('holiday.index')->with('update', localize('data_update'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        Toastr::success('Holiday Deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }
}
