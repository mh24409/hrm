<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HumanResource\DataTables\ProcurementVendorDataTable;
use Modules\HumanResource\Entities\Country;
use Modules\HumanResource\Entities\ProcurementVendor;

class ProcurementVendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_vendors')->only(['index']);
        $this->middleware('permission:create_vendors', ['only' => ['create','store']]);
        $this->middleware('permission:update_vendors', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_vendors', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(ProcurementVendorDataTable  $dataTable)
    {
        return $dataTable->render('humanresource::procurement.vendor.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $countries = Country::all();
        return view('humanresource::procurement.vendor.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required',
            'mobile'            => 'required',
            'email'             => 'required',
            'address'           => 'required',
            'country_id'        => 'required',
            'city'              => 'required',
            'zip'               => 'required',
            'previous_balance'  => 'required'
        ]);

        if (ProcurementVendor::create($request->all())) {
            return response()->json(['error' => false, 'msg' => 'Vendor created successfully!']);
        } else {
            return response()->json(['error' => true, 'msg' => 'Something Went Wrong']);
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
    public function edit(ProcurementVendor $vendor)
    {
        $countries = Country::all();
        return view('humanresource::procurement.vendor.edit', compact('countries', 'vendor'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, ProcurementVendor $vendor)
    {
        $request->validate([
            'name'              => 'required',
            'mobile'            => 'required',
            'email'             => 'required',
            'address'           => 'required',
            'country_id'        => 'required',
            'city'              => 'required',
            'zip'               => 'required',
            'previous_balance'  => 'required'
        ]);

        if ($vendor->update($request->all())) {
            return response()->json(['error' => false, 'msg' => 'Vendor updated successfully!']);
        }
        else {
            return response()->json(['error' => true, 'msg' => 'Something Went Wrong']);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(ProcurementVendor $vendor)
    {
        $vendor->delete();
        return response()->json(['success' => 'success']);
    }
}
