<?php

namespace Modules\Accounts\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\AccVoucher;
use Illuminate\Contracts\Support\Renderable;
use Modules\Accounts\Entities\FinancialYear;

class ContraVoucherController extends Controller
{
    // Apply middleware for permissions based on actions
    public function __construct()
    {
        $this->middleware('permission:read_contra_voucher')->only('index', 'show');
        $this->middleware('permission:create_contra_voucher')->only(['create', 'store', 'downloadContraVoucherPdf']);
        $this->middleware('permission:update_contra_voucher')->only(['edit', 'update']);
        $this->middleware('permission:delete_contra_voucher')->only('destroy');
    }

    //download contra voucher pdf
    public function downloadContraVoucherPdf($id)
    {
        $contra_voucher = AccVoucher::findOrFail($id);
        $pdf = PDF::loadView('accounts::contra-voucher.pdf.voucher', compact('contra_voucher'));
        return $pdf->download('contra_voucher.pdf');
    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $contraVouchers = [];
        AccVoucher::where('voucher_type', 3)
            ->orderBy('voucher_no', 'desc')
            ->chunkById(10, function ($vouchers) use (&$contraVouchers) {
                // Process each chunk of records
                foreach ($vouchers as $voucher) {
                    $contraVouchers[] = $voucher;
                }
            });

        return view('accounts::contra-voucher.index', [
            'contra_vouchers' => $contraVouchers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        // all 4th level without Expenses
        $accounts        = AccCoa::where('head_level', 4)->where('is_active', true)->orderBy('account_name', 'ASC')->get();
        return view('accounts::contra-voucher.create', [
            'accounts' => $accounts,
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
            'acc_coa_id' => 'required',
            'date'       => 'required',
        ]);

        $financial_year = FinancialYear::where('status', true)->where('is_close', false)->first();

        $contra_voucher                    = new AccVoucher();
        $contra_voucher->financial_year_id = $financial_year->id;
        $contra_voucher->acc_coa_id        = $request->coa_id;
        $contra_voucher->voucher_date      = $request->date;
        $contra_voucher->voucher_type      = 3;
        $contra_voucher->narration         = $request->remarks;

        $contra_voucher->ledger_comment = $request->ledger_comment;
        $contra_voucher->debit          = $request->debit;
        $contra_voucher->credit         = $request->credit;
        $contra_voucher->reverse_code   = $request->acc_coa_id;

        //if reverse coa id BankNature
        $contra_voucher->cheque_no   = $request->cheque_no;
        $contra_voucher->cheque_date = $request->cheque_date;

        if (isset($request->is_honour)) {
            $contra_voucher->is_honour = $request->is_honour;
        }

        $contra_voucher->save();
        $contra_voucher->voucher_no = 'CT-' . str_pad($contra_voucher->id, 6, "0", STR_PAD_LEFT);
        $contra_voucher->update();

        Toastr::success('Contra Voucher Created successfully :)', 'Success');
        return redirect()->route('contra-vouchers.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($uuid)
    {
        $contra_voucher = AccVoucher::where('voucher_type', 3)->where('uuid', $uuid)->firstOrFail();
        $accounts        = AccCoa::where('head_level', 4)->where('is_active', true)->orderBy('account_name', 'ASC')->get(); //all 4th level without Expences
        return view('accounts::contra-voucher.edit', [
            'accounts'       => $accounts,
            'contra_voucher' => $contra_voucher,
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
            'acc_coa_id' => 'required',
            'date'       => 'required',
        ]);

        $contra_voucher                 = AccVoucher::where('voucher_type', 3)->where('uuid', $uuid)->firstOrFail();
        $contra_voucher->acc_coa_id     = $request->coa_id;
        $contra_voucher->voucher_date   = $request->date;
        $contra_voucher->narration      = $request->remarks;
        $contra_voucher->ledger_comment = $request->ledger_comment;
        $contra_voucher->debit          = $request->debit;
        $contra_voucher->credit         = $request->credit;
        $contra_voucher->reverse_code   = $request->acc_coa_id;

        //if reverse coa id BankNature
        $contra_voucher->cheque_no   = $request->cheque_no;
        $contra_voucher->cheque_date = $request->cheque_date;

        if (isset($request->is_honour)) {
            $contra_voucher->is_honour = $request->is_honour;
        }

        $contra_voucher->update();

        Toastr::success('Contra Voucher Updated successfully :)', 'Success');
        return redirect()->route('contra-vouchers.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($uuid)
    {
        AccVoucher::where('uuid', $uuid)->delete();
        Toastr::success('Contra Voucher deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }
}
