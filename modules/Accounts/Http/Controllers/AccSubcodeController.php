<?php

namespace Modules\Accounts\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccSubcode;
use Modules\Accounts\Entities\AccSubtype;
use Modules\Accounts\Http\DataTables\SubAccountDataTable;

class AccSubcodeController extends Controller
{
    // Apply middleware for permissions based on actions
    public function __construct()
    {
        $this->middleware('permission:read_sub_account')->only('index', 'show');
        $this->middleware('permission:create_sub_account')->only(['create', 'store']);
        $this->middleware('permission:update_sub_account')->only(['edit', 'update']);
        $this->middleware('permission:delete_sub_account')->only('destroy');
    }

    public function index(SubAccountDataTable $datatable)
    {
        $subtypes = AccSubtype::all();
        return $datatable->render('accounts::subcode.index', compact('subtypes'));
    }

    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'name'           => 'required|unique:acc_subcodes|max:255',
            'acc_subtype_id' => 'required',
        ]);

        $subcode = new AccSubcode();
        $subcode->fill($request->all());
        $subcode->save();
        Toastr::success('Sub Account Added Successfully :)', 'Success');
        return redirect()->route('subcodes.index');
    }

    public function update(Request $request, $uuid)
    {
        $subcode = AccSubcode::where('uuid', $uuid)->firstOrFail();

        // Validate the incoming data
        $request->validate([
            'name'           => 'required|unique:acc_subcodes,name,' . $subcode->id,
            'acc_subtype_id' => 'required',
        ]);

        // Update the subcode with the new data
        $subcode->fill($request->all());
        $subcode->save();
        Toastr::success('Sub Account Updated Successfully :)', 'Success');
        return redirect()->route('subcodes.index');
    }

    // Find the subcode by ID and fetch subtypes for dropdown
    public function edit($id)
    {
        $subcode = AccSubcode::where('id', $id)->firstOrFail();

        return response()->view('accounts::subcode.modal.edit', [
            'code' => $subcode,
            'subtypes' => AccSubtype::all(),
        ]);
    }

    // Delete the subcode by UUID and provide success response
    public function destroy($uuid)
    {
        AccSubcode::where('uuid', $uuid)->delete();
        Toastr::success('Sub Account Deleted Successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }
}
