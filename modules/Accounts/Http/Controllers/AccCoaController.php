<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\AccSubtype;
use Modules\Accounts\Http\Exports\AccCoaExport;

class AccCoaController extends Controller
{
    // Apply middleware for permissions based on actions
    public function __construct()
    {
        $this->middleware('permission:read_chart_of_accounts')->only('index', 'show');
        $this->middleware('permission:create_chart_of_accounts')->only(['create', 'store']);
        $this->middleware('permission:update_chart_of_accounts')->only(['edit', 'update']);
        $this->middleware('permission:delete_chart_of_accounts')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('accounts::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $accMainHead = AccCoa::where('is_active', 1)->where('head_level', 1)->where('parent_id', 0)->get();
        $accSecondLableHead = AccCoa::where('is_active', 1)->where('head_level', 2)->get();
        $accHeadWithoutFandS = AccCoa::where('is_active', 1)->whereNot('head_level', 2)->whereNot('head_level', 1)->get();
        $accSubType = AccSubtype::where('status', 1)->get();
        return view('accounts::coa.create', compact('accMainHead', 'accSecondLableHead', 'accHeadWithoutFandS', 'accSubType'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'account_name' => 'required',
            'head_level' => 'required',
            'parent_id' => 'required',
            'acc_type_id' => 'required',
            'is_active' => 'required',
        ]);
        if ($request->asset_type == "is_stock") {
            $validated['is_stock'] = 1;
        }
        if ($request->asset_type == "is_fixed_asset") {
            $validated['is_fixed_asset_schedule'] = 1;
            $validated['asset_code'] = $request->asset_code;
            $validated['depreciation_rate'] = $request->depreciation_rate;
        }

        if ($request->asset_type == "is_subtype") {
            $validated['is_subtype'] = 1;
            $validated['subtype_id'] = $request->subtype_id;
        }
        if ($request->asset_type == "is_cash") {
            $validated['is_cash_nature'] = 1;
        }
        if ($request->asset_type == "is_bank") {
            $validated['is_bank_nature'] = 1;
        }

        if (($request->head_level == 4) && (($request->acc_type_id == 4) || ($request->acc_type_id == 5))) {
            $validated['dep_code'] = $request->dep_code;
        }
        if ((($request->head_level == 3) || ($request->head_level == 4))) {
            $validated['note_no'] = $request->note_no;
        }
        AccCoa::create($validated);
        return redirect()->route('account.create')->with('success', localize('data_save'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(AccCoa $coa)
    {
        return response()->json([
            'coaDetail' => $coa,

        ]);
    }

    /**
     * Edit the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(AccCoa $coa)
    {

        $lablearray = array();
        for ((int) $i = 1; $i < (int) $coa->head_level; $i++) {
            array_push($lablearray, $i);
        }

        return response()->json([
            'coaDetail' => $coa,
            'coaDropDown' => AccCoa::whereIn('head_level', $lablearray)->where('acc_type_id', $coa->acc_type_id)->where('is_active', 1)->get(),
        ]);
    }

    /**
     * Update the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'account_name' => 'required',
            'parent_id' => 'required',
            'is_active' => 'required',
        ]);

        $GetParentCoa = AccCoa::findOrFail($request->parent_id);
        $head_level = (int) $GetParentCoa->head_level + 1;
        $acc_type_id = $GetParentCoa->acc_type_id;
        $validated['acc_type_id'] = $acc_type_id;
        $validated['head_level'] = $head_level;

        if (($acc_type_id == 1) && ($head_level == 3)) {

            if ($request->asset_type == "is_stock") {
                $validated['is_stock'] = 1;
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['asset_code'] = null;
                $validated['depreciation_rate'] = null;
            }
            if ($request->asset_type == "is_fixed_asset") {
                $validated['is_stock'] = 0;
                $validated['is_fixed_asset_schedule'] = 1;
                $validated['asset_code'] = null;
                $validated['depreciation_rate'] = null;
            }
        }

        if ((($acc_type_id == 4) || ($acc_type_id == 5)) && ($head_level == 3)) {

            if ($request->asset_type == "is_fixed_asset") {
                $validated['is_fixed_asset_schedule'] = 1;
            } else {
                $validated['is_fixed_asset_schedule'] = 0;
            }
            $validated['asset_code'] = null;
            $validated['depreciation_rate'] = null;
            $validated['dep_code'] = null;
        }

        if (($acc_type_id == 1) && ($head_level == 4)) {

            if ($request->asset_type == "is_cash") {

                $validated['is_cash_nature'] = 1;
                $validated['is_bank_nature'] = 0;
                $validated['is_stock'] = 0;
                $validated['is_subtype'] = 0;
                $validated['subtype_id'] = null;
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['asset_code'] = null;
                $validated['depreciation_rate'] = null;
            }
            if ($request->asset_type == "is_bank") {

                $validated['is_bank_nature'] = 1;
                $validated['is_cash_nature'] = 0;
                $validated['is_stock'] = 0;
                $validated['is_subtype'] = 0;
                $validated['subtype_id'] = null;
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['asset_code'] = null;
                $validated['depreciation_rate'] = null;
            }

            if ($request->asset_type == "is_stock") {

                $validated['is_stock'] = 1;
                $validated['is_bank_nature'] = 0;
                $validated['is_cash_nature'] = 0;
                $validated['is_subtype'] = 0;
                $validated['subtype_id'] = null;
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['asset_code'] = null;
                $validated['depreciation_rate'] = null;
            }

            if ($request->asset_type == "is_fixed_asset") {

                $validated['is_fixed_asset_schedule'] = 1;
                $validated['asset_code'] = $request->asset_code;
                $validated['depreciation_rate'] = $request->depreciation_rate;
                $validated['is_stock'] = 0;
                $validated['is_bank_nature'] = 0;
                $validated['is_cash_nature'] = 0;
                $validated['is_subtype'] = 0;
                $validated['subtype_id'] = null;
            }

            if ($request->asset_type == "is_subtype") {

                $validated['is_subtype'] = 1;
                $validated['subtype_id'] = $request->subtype_id;
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['asset_code'] = null;
                $validated['depreciation_rate'] = null;
                $validated['is_stock'] = 0;
                $validated['is_bank_nature'] = 0;
                $validated['is_cash_nature'] = 0;
            }
        }

        if ((($acc_type_id == 2) || ($acc_type_id == 3)) && ($head_level == 4)) {

            if ($request->asset_type == "is_subtype") {
                $validated['is_subtype'] = 1;
                $validated['subtype_id'] = $request->subtype_id;
            } else {
                $validated['is_subtype'] = 0;
                $validated['subtype_id'] = null;
            }
            $validated['asset_code'] = null;
            $validated['depreciation_rate'] = null;
            $validated['dep_code'] = null;

            $validated['is_fixed_asset_schedule'] = 0;
            $validated['depreciation_rate'] = null;
            $validated['is_stock'] = 0;
            $validated['is_bank_nature'] = 0;
            $validated['is_cash_nature'] = 0;
            $validated['note_no'] = $request->note_no;
        }

        if ((($acc_type_id == 4) || ($acc_type_id == 5)) && ($head_level == 4)) {
            if ($request->asset_type == "is_fixed_asset") {
                $validated['is_fixed_asset_schedule'] = 1;
                $validated['dep_code'] = $request->dep_code;

                $validated['is_subtype'] = 0;
                $validated['subtype_id'] = null;
            }
            if ($request->asset_type == "is_subtype") {
                $validated['is_subtype'] = 1;
                $validated['subtype_id'] = $request->subtype_id;
                $validated['is_fixed_asset_schedule'] = 0;
                $validated['dep_code'] = null;
            }

            $validated['asset_code'] = null;
            $validated['depreciation_rate'] = null;
            $validated['is_stock'] = 0;
            $validated['is_bank_nature'] = 0;
            $validated['is_cash_nature'] = 0;
            $validated['note_no'] = $request->note_no;
        }

        if ((($head_level == 3) || ($head_level == 4))) {
            $validated['note_no'] = $request->note_no;
        }

        AccCoa::where('id', $request->id)->update($validated);
        $latestCoaUpdate = AccCoa::where('id', $request->id)->first();
        $value = $this->updateActypeAndTreeLable($latestCoaUpdate);

        return redirect()->route('account.create')->with('update', localize('data_update'));
    }

    /**
     * Delete the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        AccCoa::destroy($request->id);
        return redirect()->route('account.create')->with('fail', localize('data_delete'));
    }

    /**
     * Update the specified resource.
     * @param $latestCoaUpdate
     */
    public function updateActypeAndTreeLable($latestCoaUpdate)
    {
        $acc_type_id = $latestCoaUpdate->acc_type_id;
        $FstChildCheck = AccCoa::where('parent_id', $latestCoaUpdate->id)->get();

        if ($FstChildCheck->isNotEmpty()) {
            foreach ($FstChildCheck as $fkey => $fvalue) {

                $fchild['acc_type_id'] = $acc_type_id;
                $fchild['head_level'] = (int) $latestCoaUpdate->head_level + 1;
                AccCoa::where('id', $fvalue->id)->update($fchild);

                $fchild['acc_type_id'] = "";
                $fchild['head_level'] = "";

                $SecondChildCheck = AccCoa::where('parent_id', $fvalue->id)->get();

                if ($SecondChildCheck->isNotEmpty()) {
                    foreach ($SecondChildCheck as $key => $svalue) {

                        $Schild['acc_type_id'] = $acc_type_id;
                        $Schild['head_level'] = (int) $fvalue->head_level + 1;
                        AccCoa::where('id', $svalue->id)->update($Schild);
                        $Schild['acc_type_id'] = "";
                        $Schild['head_level'] = "";

                        $ThirdChildCheck = AccCoa::where('parent_id', $svalue->id)->get();

                        if ($ThirdChildCheck->isNotEmpty()) {

                            foreach ($ThirdChildCheck as $key => $tvalue) {

                                $Tchild['acc_type_id'] = $acc_type_id;
                                $Tchild['head_level'] = (int) $tvalue->head_level + 1;
                                AccCoa::where('id', $tvalue->id)->update($Tchild);
                                $Tchild['acc_type_id'] = "";
                                $Tchild['head_level'] = "";
                            }
                        }
                    }
                }
            }
        } else {

            return true;
        }
    }

    /**
     * Export the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function exportAccCoaToExcel()
    {
        return Excel::download(new AccCoaExport, 'COA.xls');
    }
}
