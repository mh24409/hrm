<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HumanResource\DataTables\ProcurementCommitteeDataTable;
use Modules\HumanResource\Entities\ProcurementCommittee;
use Illuminate\Support\Facades\Storage;

class ProcurementCommitteeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_committees')->only(['index']);
        $this->middleware('permission:create_committees', ['only' => ['create','store']]);
        $this->middleware('permission:update_committees', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_committees', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(ProcurementCommitteeDataTable  $dataTable)
    {
        return $dataTable->render('humanresource::procurement.committee.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('humanresource::procurement.committee.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $path = '';
        $request->validate([
            'name'      => 'required',
            'signature' => 'required',
        ]);

        if ($request->hasFile('signature')) {
            $request_file = $request->file('signature');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('procurement/signature', $filename, 'public');
        }

        $committee = ProcurementCommittee::create([
            'name'      => $request->name,
            'signature' => $path
        ]);

        if($committee){
            return response()->json(['error' => false, 'msg' => 'Committee created successfully!']);
        }else{
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
    public function edit(ProcurementCommittee  $committee)
    {
        return view('humanresource::procurement.committee.edit', compact('committee'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $committee = ProcurementCommittee::findOrFail($id);

        // If the committee already has a signature, validation rule for signature is not required
        $signatureRule = $committee->signature ? '' : 'required';

        $request->validate([
            'name'      => 'required',
            'signature' => $signatureRule,
        ]);

        $path = $committee->signature;

        if ($request->hasFile('signature')) {
            // Delete previous signature if it exists
            Storage::delete('public/' . $committee->signature);

            $request_file = $request->file('signature');
            $filename = time() . rand(10, 1000) . '.' . $request_file->extension();
            $path = $request_file->storeAs('procurement/signature', $filename, 'public');
        }

        $committee->update([
            'name'      => $request->name,
            'signature' => $path
        ]);
        
        return response()->json(['error' => false, 'msg' => 'Committee updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(ProcurementCommittee  $committee)
    {
        if ($committee->signature) {
            Storage::delete('public/' . $committee->signature);
        }

        $committee->delete();
        return response()->json(['success' => 'success']);
    }
}
