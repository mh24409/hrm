<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Accounts\Http\DataTables\SubtypeDataTable;
use Modules\Accounts\Entities\AccSubtype;

class SubTypeController extends Controller
{
    // Apply middleware for permissions based on actions
    public function __construct()
    {
        $this->middleware('permission:read_subtype')->only('index', 'show');
        $this->middleware('permission:create_subtype')->only(['create', 'store']);
        $this->middleware('permission:update_subtype')->only(['edit', 'update']);
        $this->middleware('permission:delete_subtype')->only('destroy');
    }

    //index for datatable
    public function index(SubtypeDataTable $dataTable)
    {
        $subtypes = AccSubtype::all();
        return $dataTable->render('accounts::subtype.index', compact('subtypes'));
    }

    //store data
    public function store(Request $request)
    {
        $request->validate([
            'subtype_name'           => 'required',
        ]);

        $subtype = new AccSubtype();
        $subtype->fill($request->all());
        $subtype->save();
        Toastr::success('Subtype Added Successfully', 'Success');
        return redirect()->route('subtypes.index');
    }

    //update data using uuid
    public function update(Request $request, $uuid)
    {
        $request->validate([
            'subtype_name'           => 'required',
        ]);

        $subtype = AccSubtype::where('uuid', $uuid)->firstOrFail();
        $subtype->fill($request->all());
        $subtype->save();
        Toastr::success('Subtype Updated Successfully :)', 'Success');
        return redirect()->route('subtypes.index');
    }

    //modal data show
    public function edit($id)
    {
        $type = AccSubtype::where('id', $id)->firstOrFail();
        return response()->view('accounts::subtype.modal.edit', compact('type'));
    }

    //delete data using uuid
    public function destroy($uuid)
    {
        AccSubtype::where('uuid', $uuid)->delete();
        Toastr::success('Subtype Deleted Successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }
}
