<?php

namespace Modules\HumanResource\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Accounts\Entities\AccCoa;
use Modules\HumanResource\Entities\Bank;

class BankController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_bank')->only(['index']);
        $this->middleware('permission:create_bank', ['only' => ['store']]);
        $this->middleware('permission:update_bank', ['only' => ['update']]);
        $this->middleware('permission:delete_bank', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $dbData = Bank::paginate(30);
        return view('humanresource::bank.index', compact('dbData'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required',
            'branch_name' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $coaBankAdd = [
                'account_name' => $request->bank_name,
                'head_level' => 4,
                'parent_id' => 17,
                'acc_type_id' => 1,
                'is_bank_nature' => 1,
                'is_active' => 1,
            ];

            Bank::create($validated);
            AccCoa::create($coaBankAdd);
        });

        return redirect()->route('bank.index')->with('success', localize('data_save'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Bank $bank)
    {
        $validated = $request->validate([
            'bank_name' => 'required',
            'branch_name' => 'required',
            'account_name' => 'required',
            'account_number' => 'required',
        ]);

        $bank->update($validated);
        return redirect()->route('bank.index')->with('update', localize('data_update'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Bank $bank)
    {
        $bank->delete();
        Toastr::success('Bank Deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }
}
