<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\AccSubcode;
use Illuminate\Contracts\Support\Renderable;
use Modules\Accounts\Entities\FinancialYear;
use Modules\Accounts\Entities\AccOpeningBalance;
use Modules\Accounts\Http\DataTables\OpeningBalanceDataTable;
use Modules\Accounts\Http\Imports\AccOpeningBalanceImport;

class AccOpeningBalanceController extends Controller
{
    // Apply middleware for permissions based on actions
    public function __construct()
    {
        $this->middleware('permission:read_opening_balance')->only('index', 'show');
        $this->middleware('permission:create_opening_balance')->only(['create', 'store', 'importOpeningBalance']);
        $this->middleware('permission:update_opening_balance')->only(['edit', 'update']);
        $this->middleware('permission:delete_opening_balance')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(OpeningBalanceDataTable $dataTable)
    {
        return $dataTable->render('accounts::opening-balance.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $financial_years = FinancialYear::where('status', false)->get();
        $accounts        = AccCoa::where('head_level', 4)->whereIn('acc_type_id', [1, 4, 5])->where('is_active', true)->get();

        return view('accounts::opening-balance.create', [
            'accounts'        => $accounts,
            'financial_years' => $financial_years,
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
            'financial_year_id' => 'required',
            'date'              => 'required',
        ]);

        foreach ($request->opening_balances as $key => $rq_balance) {

            $opening_balance                    = new AccOpeningBalance();
            $opening_balance->financial_year_id = $request->financial_year_id;
            $opening_balance->open_date         = $request->date;
            $opening_balance->acc_coa_id        = $rq_balance['coa_id'];

            if (isset($rq_balance['subcode_id'])) {
                $subcode = AccSubcode::where('id', $rq_balance['subcode_id'])->first();
            }

            $opening_balance->acc_subtype_id = $subcode->acc_subtype_id ?? null;
            $opening_balance->acc_subcode_id = $rq_balance['subcode_id'] ?? null;
            $opening_balance->debit          = $rq_balance['debit'] ?? 0.00;
            $opening_balance->credit         = $rq_balance['credit'] ?? 0.00;

            $opening_balance->save();
        }

        Toastr::success('Opening Balance added successfully :)', 'Success');
        return redirect()->route('opening-balances.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $uuid
     * @return Renderable
     */
    public function edit($uuid)
    {

        $opening_balance = AccOpeningBalance::where('uuid', $uuid)->first();

        $financial_years = FinancialYear::where('status', false)->get();
        $accounts        = AccCoa::where('head_level', 4)->whereIn('acc_type_id', [1, 4, 5])->where('is_active', true)->get();

        //here should be add whereIn('acc_type_id' [1, 4]) and order by head type id Assets and liabilities
        $subcodes = AccSubcode::where('acc_subtype_id', $opening_balance->acc_subtype_id)->get();

        return view('accounts::opening-balance.edit', [
            'accounts'        => $accounts,
            'financial_years' => $financial_years,
            'opening_balance' => $opening_balance,
            'subcodes'        => $subcodes,
        ]);
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
            'financial_year_id' => 'required',
            'date'              => 'required',
        ]);

        $opening_balance = AccOpeningBalance::where('uuid', $uuid)->first();

        $opening_balance->financial_year_id = $request->financial_year_id;
        $opening_balance->open_date         = $request->date;
        $opening_balance->acc_coa_id        = $request->coa_id;

        if (isset($request->subcode_id)) {
            $subcode = AccSubcode::where('id', $request->subcode_id)->first();
        }

        $opening_balance->acc_subtype_id = $subcode->acc_subtype_id ?? null;
        $opening_balance->acc_subcode_id = $request->subcode_id ?? null;
        $opening_balance->debit          = $request->debit ?? 0.00;
        $opening_balance->credit         = $request->credit ?? 0.00;

        $opening_balance->update();

        Toastr::success('Opening Balance Updated Successfully :)', 'Success');
        return redirect()->route('opening-balances.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($uuid)
    {
        AccOpeningBalance::where('uuid', $uuid)->delete();
        Toastr::success('Account Opening balance deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }

    //import opening balance use laravel excel
    public function importOpeningBalance(Request $request)
    {

        $request->validate([
            'upload_csv_file' => 'required|mimes:xlsx,xls,csv',
        ]);
        try {
            Excel::import(new AccOpeningBalanceImport, $request->file('upload_csv_file'));
            Toastr::success('Opening Balance imported successfully :)', 'Success');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }
            Toastr::error('Opening Balance import failed :)', 'Error');
        }
        return redirect()->route('opening-balances.index');
    }
}
