<?php

namespace Modules\Accounts\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Support\Renderable;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\AccSubcode;
use Modules\Accounts\Entities\AccVoucher;
use Modules\Accounts\Entities\FinancialYear;

class JournalVoucherController extends Controller
{
    // Apply middleware for permissions based on actions
    public function __construct()
    {
        $this->middleware('permission:read_journal_voucher')->only('index', 'show');
        $this->middleware('permission:create_journal_voucher')->only(['create', 'store', 'downloadJournalVoucherPdf']);
        $this->middleware('permission:update_journal_voucher')->only(['edit', 'update']);
        $this->middleware('permission:delete_journal_voucher')->only('destroy');
    }

    //download contra voucher pdf
    public function downloadJournalVoucherPdf($id)
    {
        $journal_voucher = AccVoucher::findOrFail($id);
        $pdf = PDF::loadView('accounts::journal-voucher.pdf.voucher', compact('journal_voucher'));
        return $pdf->download('journal_voucher.pdf');
    }


    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $journalVouchers = [];
        AccVoucher::where('voucher_type', 4)
            ->orderBy('voucher_no', 'desc')
            ->chunkById(10, function ($vouchers) use (&$journalVouchers) {
                // Process each chunk of records
                foreach ($vouchers as $voucher) {
                    // You can perform any processing here
                    $journalVouchers[] = $voucher;
                }
            });

        return view('accounts::journal-voucher.index', [
            'journal_vouchers' => $journalVouchers,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $accounts        = AccCoa::where('head_level', 4)->where('is_active', true)->orderBy('account_name', 'ASC')->get(); //all 4th level without Expences
        return view('accounts::journal-voucher.create', [
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
            'date' => 'required',
        ]);

        $financial_year = FinancialYear::where('status', true)->where('is_close', false)->first();
        $latestVoucher  = AccVoucher::orderBy('created_at', 'DESC')->first();

        foreach ($request->debits as $key => $jv) {

            if ($jv['coa_id'] != $request->rev_code) {

                $journal_voucher = new AccVoucher();

                $journal_voucher->financial_year_id = $financial_year->id;
                $journal_voucher->voucher_date      = $request->date;
                $journal_voucher->voucher_type      = 4;
                $journal_voucher->acc_coa_id        = $jv['coa_id'];
                $journal_voucher->narration         = $request->remarks;

                if (isset($jv['subcode_id'])) {
                    $subcode = AccSubcode::where('id', $jv['subcode_id'])->first();
                }

                $journal_voucher->acc_subtype_id = $subcode->acc_subtype_id ?? null;
                $journal_voucher->acc_subcode_id = $jv['subcode_id'] ?? null;

                $journal_voucher->ledger_comment = $jv['ledger_comment'] ?? '';
                $journal_voucher->debit          = $jv['debit'] ?? 0.00;
                $journal_voucher->credit         = $jv['credit'] ?? 0.00;
                $journal_voucher->reverse_code   = $request->rev_code ?? null;

                $journal_voucher->voucher_no = 'JV-' . str_pad(($latestVoucher ? $latestVoucher->id : 0) + 1, 6, "0", STR_PAD_LEFT);
                $journal_voucher->save();
            }
        }

        Toastr::success('Journal Voucher Created successfully :)', 'Success');
        return redirect()->route('journal-vouchers.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($uuid)
    {
        $journal_voucher = AccVoucher::where('voucher_type', 4)->where('uuid', $uuid)->firstOrFail();
        $accounts        = AccCoa::where('head_level', 4)->where('is_active', true)->orderBy('account_name', 'ASC')->get(); //all 4th level without Expences
        return view('accounts::journal-voucher.edit', [
            'accounts'        => $accounts,
            'journal_voucher' => $journal_voucher,
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
            'date' => 'required',
        ]);

        $financial_year  = FinancialYear::where('status', true)->where('is_close', false)->first();
        $journal_voucher = AccVoucher::where('voucher_type', 4)->where('uuid', $uuid)->first();
        $voucher_no      = $journal_voucher->voucher_no;

        foreach ($request->debits as $key => $rq_journals) {
            if ($rq_journals['coa_id'] != $request->rev_code) {

                $voucher['financial_year_id'] = $financial_year->id;
                $voucher['acc_coa_id']        = $rq_journals['coa_id'];
                $voucher['voucher_date']      = $request->date;
                $voucher['voucher_type']      = 4;
                $voucher['narration']         = $request->remarks;

                if (isset($rq_journals['subcode_id'])) {
                    $subcode = AccSubcode::where('id', $rq_journals['subcode_id'])->first();
                }

                $voucher['acc_subtype_id'] = $subcode->acc_subtype_id ?? null;
                $voucher['acc_subcode_id'] = $rq_journals['subcode_id'] ?? null;
                $voucher['ledger_comment'] = $rq_journals['ledger_comment'];
                $voucher['debit']          = $rq_journals['debit'];
                $voucher['credit']         = $rq_journals['credit'];
                $voucher['reverse_code']   = $request->rev_code;

                if (isset($rq_journals['acc_voucher_id'])) {
                    $ex_voucher = AccVoucher::where('id', $rq_journals['acc_voucher_id'])->first();
                    $ex_voucher->update($voucher);
                } else {
                    $new_voucher           = new AccVoucher();
                    $voucher['voucher_no'] = $voucher_no;
                    $new_voucher->create($voucher);
                }
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

        Toastr::success('Journal Voucher Updated successfully :)', 'Success');
        return redirect()->route('journal-vouchers.index');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($uuid)
    {
        AccVoucher::where('uuid', $uuid)->delete();
        Toastr::success('Journal Voucher deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }
}
