<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Modules\HumanResource\Entities\Position;

class PositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_positions', ['only' => ['index']]);
        $this->middleware('permission:create_positions', ['only' => ['create', 'store']]);
        $this->middleware('permission:update_positions', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_positions', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('humanresource::employee.position.index', [
            'positions' => Position::all()
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
            'position_name' => 'required',
            'position_details' => 'required',
            'is_active' => 'required'
        ]);

        Position::create($request->all());
        Toastr::success('Position added successfully :)','Success');
        return redirect()->route('positions.index');
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
            'position_name' => 'required',
            'position_details' => 'required',
            'is_active' => 'required'
        ]);

        $position = Position::where('uuid', $uuid)->firstOrFail();
        $position->update($request->all());
        Toastr::success('Position updated successfully :)','Success');
        return redirect()->route('positions.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($uuid)
    {
        Position::where('uuid' , $uuid)->delete();
        Toastr::success('Position deleted successfully :)','Success');
        return response()->json(['success' => 'success']);
    }
}
