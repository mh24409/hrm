<?php

namespace Modules\Accounts\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\AccSubcode;
use Modules\Accounts\Entities\AccVoucher;
use Illuminate\Contracts\Support\Renderable;
use Modules\Accounts\Entities\FinancialYear;


class CreditVoucherController extends Controller
{
    // Apply middleware for permissions based on actions
    public function __construct()
    {
        $this->middleware('permission:read_credit_voucher')->only('index', 'show');
        $this->middleware('permission:create_credit_voucher')->only(['create', 'store', 'downloadCreditVoucherPdf']);
        $this->middleware('permission:update_credit_voucher')->only(['edit', 'update']);
        $this->middleware('permission:delete_credit_voucher')->only('destroy');
    }

    //download contra voucher pdf
    public function downloadCreditVoucherPdf($id)
    {

        $credit_voucher = AccVoucher::findOrFail($id);
        $pdf = PDF::loadView('accounts::credit-voucher.pdf.voucher', compact('credit_voucher'));
        return $pdf->download('credit_voucher.pdf');
    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $creditVouchers = [];
        AccVoucher::where('voucher_type', 2)
            ->orderBy('voucher_no', 'desc')
            ->chunkById(10, function ($vouchers) use (&$creditVouchers) {
                foreach ($vouchers as $voucher) {
                    $creditVouchers[] = $voucher;
                }
            });

        return view('accounts::credit-voucher.index', [
            'credit_vouchers' => $creditVouchers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $debit_accounts = AccCoa::where('head_level', 4)->where('is_active', true)->orderBy('account_name', 'ASC')->get();
        $accounts = AccCoa::where('head_level', 4)->where('is_active', true)->orderBy('account_name', 'ASC')->get();

        return view('accounts::credit-voucher.create', [
            'credit_accounts' => $debit_accounts,
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

        foreach ($request->debits as $key => $rq_credit) {

            $credit_voucher = new AccVoucher();

            $credit_voucher->financial_year_id = $financial_year->id;
            $credit_voucher->acc_coa_id        = $rq_credit['coa_id'];
            $credit_voucher->voucher_date      = $request->date;
            $credit_voucher->voucher_type      = 2;
            $credit_voucher->narration         = $request->remarks;

            if (isset($rq_credit['subcode_id'])) {
                $subcode = AccSubcode::where('id', $rq_credit['subcode_id'])->first();
            }

            $credit_voucher->acc_subtype_id = $subcode->acc_subtype_id ?? null;
            $credit_voucher->acc_subcode_id = $rq_credit['subcode_id'] ?? null;

            $credit_voucher->ledger_comment = $rq_credit['ledger_comment'] ?? '';
            $credit_voucher->credit          = $rq_credit['amount'] ?? 0.00;
            $credit_voucher->reverse_code   = $request->acc_coa_id;

            //if reverse coa id BankNature
            $credit_voucher->cheque_no   = $request->cheque_no;
            $credit_voucher->cheque_date = $request->cheque_date;
            if (isset($request->is_honour)) {
                $credit_voucher->is_honour = $request->is_honour;
            }

            $credit_voucher->voucher_no = 'CV-' . str_pad(($latestVoucher ? $latestVoucher->id : 0) + 1, 6, "0", STR_PAD_LEFT);
            $credit_voucher->save();
        }

        Toastr::success('Credit Voucher Created successfully :)', 'Success');
        return redirect()->route('credit-vouchers.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($uuid)
    {
        $credit_voucher = AccVoucher::where('voucher_type', 2)->where('uuid', $uuid)->firstOrFail();
        $credit_accounts = AccCoa::where('head_level', 4)->where('is_active', true)->orderBy('account_name', 'ASC')->get();
        $accounts        = AccCoa::where('head_level', 4)->where('is_active', true)->orderBy('account_name', 'ASC')->get();


        return view('accounts::credit-voucher.edit', [
            'credit_accounts' => $credit_accounts,
            'accounts'        => $accounts,
            'credit_voucher' => $credit_voucher
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
        $credit_voucher = AccVoucher::where('voucher_type', 2)->where('uuid', $uuid)->first();
        $credit_voucher_all = AccVoucher::where('voucher_type', 2)->where('uuid', $uuid)->get();
        $voucher_no = $credit_voucher->voucher_no;

        foreach ($request->credits as $key => $rq_credit) {

            $voucher['financial_year_id'] = $financial_year->id;
            $voucher['acc_coa_id']        = $rq_credit['coa_id'];
            $voucher['voucher_date']      = $request->date;
            $voucher['voucher_type']      = 2;
            $voucher['narration']         = $request->remarks;

            if (isset($rq_credit['subcode_id'])) {
                $subcode = AccSubcode::where('id', $rq_credit['subcode_id'])->first();
            }

            $voucher['acc_subtype_id'] = $subcode->acc_subtype_id ?? null;
            $voucher['acc_subcode_id'] = $rq_credit['subcode_id'] ?? null;
            $voucher['ledger_comment'] = $rq_credit['ledger_comment'];
            $voucher['credit']          = $rq_credit['amount'];
            $voucher['reverse_code']   = $request->acc_coa_id;

            //if reverse coa id BankNature
            $voucher['cheque_no']   = $request->cheque_no;
            $voucher['cheque_date'] = $request->cheque_date;
            if (isset($request->is_honour)) {
                $voucher['is_honour'] = $request->is_honour;
            }

            if (isset($rq_credit['acc_voucher_id'])) {
                $ex_voucher = AccVoucher::where('id', $rq_credit['acc_voucher_id'])->first();
                $ex_voucher->update($voucher);
            } else {
                $new_voucher = new AccVoucher();
                $voucher['voucher_no'] = $voucher_no;
                $new_voucher->create($voucher);
            }

            //delete credit voucher
            if (isset($request->delete_credit)) {
                foreach ($request->delete_credit as $key => $delete_credit) {
                    $delete_credit_voucher = AccVoucher::where('id', $delete_credit)->first();
                    if ($delete_credit_voucher) {
                        $delete_credit_voucher->delete();
                    }
                }
            }
        }
        Toastr::success('Credit Voucher Updated successfully :)', 'Success');
        return redirect()->route('credit-vouchers.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($uuid)
    {
        AccVoucher::where('uuid', $uuid)->delete();
        Toastr::success('Credit Voucher deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }
}
