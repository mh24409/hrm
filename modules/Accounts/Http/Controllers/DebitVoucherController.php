<?php

namespace Modules\Accounts\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\AccSubcode;
use Modules\Accounts\Entities\AccVoucher;
use Modules\Accounts\Entities\FinancialYear;
use PDF;

class DebitVoucherController extends Controller
{
    // Apply middleware for permissions based on actions
    public function __construct()
    {
        $this->middleware('permission:read_debit_voucher')->only('index', 'show');
        $this->middleware('permission:create_debit_voucher')->only(['create', 'store', 'downloadDebitVoucherPdf']);
        $this->middleware('permission:update_debit_voucher')->only(['edit', 'update']);
        $this->middleware('permission:delete_debit_voucher')->only('destroy');
    }

    //download contra voucher pdf
    public function downloadDebitVoucherPdf($id)
    {

        $debit_voucher = AccVoucher::findOrFail($id);
        $pdf = PDF::loadView('accounts::debit-voucher.pdf.voucher', compact('debit_voucher'));
        return $pdf->download('debit_voucher.pdf');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $debitVouchers = [];
        AccVoucher::where('voucher_type', 1)
            ->orderBy('voucher_no', 'desc')
            ->chunkById(10, function ($vouchers) use (&$debitVouchers) {
                // Process each chunk of records
                foreach ($vouchers as $voucher) {
                    // You can perform any processing here
                    $debitVouchers[] = $voucher;
                }
            });

        return view('accounts::debit-voucher.index', [
            'debit_vouchers' => $debitVouchers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

        $credit_accounts = AccCoa::where('head_level', 4)->where('is_active', true)->orderBy('account_name', 'ASC')->get();
        $accounts        = AccCoa::where('head_level', 4)->where('is_active', true)->orderBy('account_name', 'ASC')->get(); //all 4th level without income

        return view('accounts::debit-voucher.create', [
            'credit_accounts' => $credit_accounts,
            'accounts'        => $accounts,
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
        $latestVoucher  = AccVoucher::orderBy('created_at', 'DESC')->first();

        foreach ($request->debits as $key => $rq_debit) {

            $debit_voucher = new AccVoucher();

            $debit_voucher->financial_year_id = $financial_year->id;
            $debit_voucher->acc_coa_id        = $rq_debit['coa_id'];
            $debit_voucher->voucher_date      = $request->date;
            $debit_voucher->voucher_type      = 1;
            $debit_voucher->narration         = $request->remarks;

            if (isset($rq_debit['subcode_id'])) {
                $subcode = AccSubcode::where('id', $rq_debit['subcode_id'])->first();
            }

            $debit_voucher->acc_subtype_id = $subcode->acc_subtype_id ?? null;
            $debit_voucher->acc_subcode_id = $rq_debit['subcode_id'] ?? null;

            $debit_voucher->ledger_comment = $rq_debit['ledger_comment'] ?? '';
            $debit_voucher->debit          = $rq_debit['amount'] ?? 0.00;
            $debit_voucher->reverse_code   = $request->acc_coa_id ?? '';
            //if reverse coa id BankNature
            $debit_voucher->cheque_no   = $request->cheque_no;
            $debit_voucher->cheque_date = $request->cheque_date;
            if (isset($request->is_honour)) {
                $debit_voucher->is_honour = $request->is_honour;
            }

            $debit_voucher->voucher_no = 'DV-' . str_pad(($latestVoucher ? $latestVoucher->id : 0) + 1, 6, "0", STR_PAD_LEFT);
            $debit_voucher->save();
        }

        Toastr::success('Debit Voucher Created successfully :)', 'Success');
        return redirect()->route('debit-vouchers.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($uuid)
    {
        $debit_voucher = AccVoucher::where('voucher_type', 1)->where('uuid', $uuid)->firstOrFail();
        $credit_accounts = AccCoa::where('head_level', 4)->where('is_active', true)->where(function ($query) {
            $query->where('is_cash_nature', 1)->orWhere('is_bank_nature', 1);
        })->orderBy('account_name', 'ASC')->get();
        $accounts        = AccCoa::where('head_level', 4)->where('is_active', true)->orderBy('account_name', 'ASC')->get(); //all 4th level without income

        return view('accounts::debit-voucher.edit', [
            'credit_accounts' => $credit_accounts,
            'accounts'        => $accounts,
            'debit_voucher'   => $debit_voucher,
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

        $financial_year = FinancialYear::where('status', true)->where('is_close', false)->first();
        $debit_voucher  = AccVoucher::where('voucher_type', 1)->where('uuid', $uuid)->first();
        $voucher_no     = $debit_voucher->voucher_no;

        foreach ($request->debits as $key => $rq_debit) {

            $voucher['financial_year_id'] = $financial_year->id;
            $voucher['acc_coa_id']        = $rq_debit['coa_id'];
            $voucher['voucher_date']      = $request->date;
            $voucher['voucher_type']      = 1;
            $voucher['narration']         = $request->remarks;

            if (isset($rq_debit['subcode_id'])) {
                $subcode = AccSubcode::where('id', $rq_debit['subcode_id'])->first();
            }

            $voucher['acc_subtype_id'] = $subcode->acc_subtype_id ?? null;
            $voucher['acc_subcode_id'] = $rq_debit['subcode_id'] ?? null;
            $voucher['ledger_comment'] = $rq_debit['ledger_comment'];
            $voucher['debit']          = $rq_debit['amount'];
            $voucher['reverse_code']   = $request->acc_coa_id;

            //if reverse coa id BankNature
            $voucher['cheque_no']   = $request->cheque_no;
            $voucher['cheque_date'] = $request->cheque_date;
            if (isset($request->is_honour)) {
                $voucher['is_honour'] = $request->is_honour;
            }

            if (isset($rq_debit['acc_voucher_id'])) {
                $ex_voucher = AccVoucher::where('id', $rq_debit['acc_voucher_id'])->first();
                $ex_voucher->update($voucher);
            } else {
                $new_voucher           = new AccVoucher();
                $voucher['voucher_no'] = $voucher_no;
                $new_voucher->create($voucher);
            }

            if (isset($request->delete_credit)) {
                foreach ($request->delete_credit as $key => $delete_credit) {
                    $delete_credit_voucher = AccVoucher::where('id', $delete_credit)->first();
                    if ($delete_credit_voucher) {
                        $delete_credit_voucher->delete();
                    }
                }
            }
        }

        Toastr::success('Debit Voucher Updated successfully :)', 'Success');
        return redirect()->route('debit-vouchers.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($uuid)
    {
        AccVoucher::where('uuid', $uuid)->delete();
        Toastr::success('Debit Voucher deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }
}
