<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Accounts\Entities\AccQuarter;
use Illuminate\Contracts\Support\Renderable;
use Modules\Accounts\Entities\FinancialYear;

class AccQuarterController extends Controller
{
    // Apply middleware for permissions based on actions
    public function __construct()
    {
        $this->middleware('permission:read_quarter')->only('index', 'show');
        $this->middleware('permission:create_quarter')->only(['create', 'store']);
        $this->middleware('permission:update_quarter')->only(['edit', 'update']);
        $this->middleware('permission:delete_quarter')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('accounts::quarter.index', [
            'acc_quarters' => AccQuarter::paginate(10),
            'financial_years' => FinancialYear::get(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'quarter'           => 'required',
            'financial_year_id' => 'required',
            'start_date'        => 'required',
            'end_date'          => 'required|after:start_date',
        ]);

        $quarter = new AccQuarter();
        $quarter->fill($request->all());
        $quarter->save();
        Toastr::success('Quarter added successfully :)', 'Success');
        return redirect()->route('quarters.index');
    }



    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('accounts::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $uuid)
    {
        $request->validate([
            'quarter'           => 'required',
            'financial_year_id' => 'required',
        ]);

        $quarter = AccQuarter::where('uuid', $uuid)->firstOrFail();
        $quarter->fill($request->all());
        $quarter->update();
        Toastr::success('Quarter Updated successfully :)', 'Success');
        return redirect()->route('quarters.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($uuid) {
        AccQuarter::where('uuid', $uuid)->delete();
        Toastr::success('Quarter deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }
}
