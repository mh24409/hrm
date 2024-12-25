<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HumanResource\Entities\Unit;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_units')->only(['index']);
        $this->middleware('permission:create_units', ['only' => ['create','store']]);
        $this->middleware('permission:update_units', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_units', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $dbData = Unit::orderBy('id', 'desc')->get();
        return view('humanresource::procurement.units.index', compact('dbData'));
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
            'unit' => 'required'
        ]);

        if (Unit::create($request->all())) {
            return redirect()->route('units.index')->with('success', localize('unit_create_successfully'));
        }else{
            return redirect()->route('units.index')->with('error', localize('something_went_wrong'));
        }
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
    public function update(Request $request, Unit $units)
    {
        $request->validate([
            'unit' => 'required'
        ]);

        if ($units->update($request->all())) {
            return redirect()->route('units.index')->with('success', localize('unit_updated_successfully'));
        } else {
            return redirect()->back()->with('error', localize('something_went_wrong'));
        }
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Unit $units)
    {
        $units->delete();
        return response()->json(['success' => 'success']);
    }
}
