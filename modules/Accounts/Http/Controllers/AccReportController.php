<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\AccOpeningBalance;
use Modules\Accounts\Entities\AccPredefineAccount;
use Modules\Accounts\Entities\AccSubcode;
use Modules\Accounts\Entities\AccSubtype;
use Modules\Accounts\Entities\AccTransaction;
use Modules\Accounts\Entities\AccVoucher;
use Modules\Accounts\Entities\AccVoucherType;
use Modules\Accounts\Entities\FinancialYear;
use Modules\Accounts\Http\Traits\AccReportTrait;
use PDF;
use \Illuminate\Database\Eloquent\Collection;

class AccReportController extends Controller
{

    use AccReportTrait;

    // Apply middleware for permissions based on actions
    public function __construct()
    {
        $this->middleware('permission:read_cash_book')->only(['cashbook', 'cashbookGenerate']);
        $this->middleware('permission:read_bank_book')->only(['bankbook', 'bankbookGenerate']);
        $this->middleware('permission:read_day_book')->only(['daybook', 'daybookGenerate']);
        $this->middleware('permission:read_general_ledger')->only(['ledgergeneral', 'ledgergeneralGenerate']);
        $this->middleware('permission:read_sub_ledger')->only(['subledger', 'subledgerGenerate']);
        $this->middleware('permission:read_control_ledger')->only('controlLedger');
        $this->middleware('permission:read_note_ledger')->only('noteLedger');
        $this->middleware('permission:read_receipt_payment')->only(['receiptpayment', 'receiptpaymentGenerate']);
        $this->middleware('permission:read_trail_balance')->only(['trilbalance', 'trilbalancelGenerate']);
        $this->middleware('permission:read_profit_loss')->only('genarateProfitLoss');
        $this->middleware('permission:read_balance_sheet')->only('genarateBalanceSheet');
    }

    /**
     * Display cashbook page.
     */
    public function cashbook()
    {
        $accDropdown = AccCoa::where('head_level', 4)->where('is_active', 1)->where('is_cash_nature', 1)->get();
        $fromDate = Carbon::now()->subDay(30)->format('d/m/Y');
        $toDate = date('d/m/Y');
        $date = $fromDate . ' - ' . $toDate;
        $acc_coa_id = $accDropdown?->first()->id ?? '';
        $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');

        $request = new Request();
        $request['from_date'] = $fromDate;
        $request['to_date'] = $toDate;
        $request['acc_coa_id'] = $acc_coa_id;
        $accDropdown = AccCoa::where('head_level', 4)->where('is_active', 1)->where('is_cash_nature', 1)->get();
        $getBalanceOpening = $this->getOpeningBalance($request);
        $getTransactionList = $this->getTransactionDetail($request, $getBalanceOpening);

        $tablefooter['totalDebit'] = $getTransactionList->pluck('debit')->sum();
        $tablefooter['totalCradit'] = $getTransactionList->pluck('credit')->sum();

        $acc_coa_id = $request->acc_coa_id;
        $ledger_name = $request->ledger_name;

        $acc_type = Cache::remember($request->acc_coa_id, 3600, function () use ($request) {
            return AccCoa::findOrFail($request->acc_coa_id);
        });

        if (($acc_type->acc_type_id == 1) || ($acc_type->acc_type_id == 2)) {
            $tablefooter['totalBalance'] = (number_format($getBalanceOpening, 2, '.', '') + number_format($tablefooter['totalDebit'], 2, '.', '')) - number_format($tablefooter['totalCradit'], 2, '.', '');
        }

        if (($acc_type->acc_type_id == 3) || ($acc_type->acc_type_id == 4) || ($acc_type->acc_type_id == 5)) {
            $tablefooter['totalBalance'] = (number_format($getBalanceOpening, 2, '.', '') + number_format($tablefooter['totalCradit'], 2, '.', '')) - number_format($tablefooter['totalDebit'], 2, '.', '');
        }

        $date = Carbon::createFromFormat('Y-m-d', $fromDate)->format('d/m/Y') . ' - ' . Carbon::createFromFormat('Y-m-d', $toDate)->format('d/m/Y');

        if ($request->pdf == 1) {
            $pdf = PDF::loadView('accounts::reports.pdf.cashBook', compact('accDropdown', 'date', 'fromDate', 'toDate', 'getBalanceOpening', 'getTransactionList', 'acc_coa_id', 'tablefooter', 'ledger_name'));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download('cashBook.pdf');
        }

        return view('accounts::reports.cashbook.create', compact('accDropdown', 'date', 'fromDate', 'toDate', 'getBalanceOpening', 'getTransactionList', 'acc_coa_id', 'tablefooter', 'ledger_name'));
    }

    /**
     * Generate cashbook report.
     */
    public function cashbookGenerate(Request $request)
    {

        set_time_limit(0);

        if ($request->acc_coa_id == null && $request->pdf == 1) {
            toastr()->error('Please filter Date & Ledger Name');
            return redirect()->back();
        }

        $string = explode(' - ', $request->date);
        $fromDate = $string[0];
        $toDate = $string[1];
        $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');

        $request['from_date'] = $fromDate;
        $request['to_date'] = $toDate;

        $accDropdown = AccCoa::where('head_level', 4)->where('is_active', 1)->where('is_cash_nature', 1)->get();
        $getBalanceOpening = $this->getOpeningBalance($request);
        $getTransactionList = $this->getTransactionDetail($request, $getBalanceOpening);

        $tablefooter['totalDebit'] = $getTransactionList->pluck('debit')->sum();
        $tablefooter['totalCradit'] = $getTransactionList->pluck('credit')->sum();

        $acc_coa_id = $request->acc_coa_id;
        $ledger_name = $request->ledger_name;

        $acc_type = Cache::remember($request->acc_coa_id, 3600, function () use ($request) {
            return AccCoa::findOrFail($request->acc_coa_id);
        });

        if (($acc_type->acc_type_id == 1) || ($acc_type->acc_type_id == 2)) {
            $tablefooter['totalBalance'] = (number_format($getBalanceOpening, 2, '.', '') + number_format($tablefooter['totalDebit'], 2, '.', '')) - number_format($tablefooter['totalCradit'], 2, '.', '');
        }

        if (($acc_type->acc_type_id == 3) || ($acc_type->acc_type_id == 4) || ($acc_type->acc_type_id == 5)) {
            $tablefooter['totalBalance'] = (number_format($getBalanceOpening, 2, '.', '') + number_format($tablefooter['totalCradit'], 2, '.', '')) - number_format($tablefooter['totalDebit'], 2, '.', '');
        }

        $fromDate = $string[0];
        $toDate = $string[1];
        $date = $fromDate . ' - ' . $toDate;

        if ($request->pdf == 1) {
            $pdf = PDF::loadView('accounts::reports.pdf.cashBook', compact('accDropdown', 'date', 'fromDate', 'toDate', 'getBalanceOpening', 'getTransactionList', 'acc_coa_id', 'tablefooter', 'ledger_name'));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download('cashBook.pdf');
        }

        return view('accounts::reports.cashbook.create', compact('accDropdown', 'date', 'fromDate', 'toDate', 'getBalanceOpening', 'getTransactionList', 'acc_coa_id', 'tablefooter', 'ledger_name'));
    }

    /**
     * Display bankbook page.
     */
    public function bankbook()
    {

        $accDropdown = AccCoa::where('head_level', 4)->where('is_active', 1)->where('is_bank_nature', 1)->get();
        $fromDate = Carbon::now()->subDay(30)->format('d/m/Y');
        $toDate = date('d/m/Y');
        $date = $fromDate . ' - ' . $toDate;
        $getBalanceOpening = "";
        $getTransactionList = [];
        $tablefooter['totalBalance'] = 0;
        $tablefooter['totalDebit'] = 0;
        $tablefooter['totalCradit'] = 0;
        $acc_coa_id = '';
        return view('accounts::reports.bankbook.create', compact('accDropdown', 'date', 'fromDate', 'toDate', 'getBalanceOpening', 'getTransactionList', 'acc_coa_id', 'tablefooter'));
    }

    /**
     * Generate bankbook report.
     */
    public function bankbookGenerate(Request $request)
    {
        set_time_limit(0);
        if ($request->acc_coa_id == null && $request->pdf == 1) {
            toastr()->error('Please filter Date & Ledger Name');
            return redirect()->back();
        }

        $string = explode(' - ', $request->date);
        $fromDate = $string[0];
        $toDate = $string[1];
        $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');

        $request['from_date'] = $fromDate;
        $request['to_date'] = $toDate;

        $accDropdown = AccCoa::where('head_level', 4)->where('is_active', 1)->where('is_bank_nature', 1)->get();
        $getBalanceOpening = $this->getOpeningBalance($request);
        $getTransactionList = $this->getTransactionDetail($request, $getBalanceOpening);

        $tablefooter['totalDebit'] = $getTransactionList->pluck('debit')->sum();
        $tablefooter['totalCradit'] = $getTransactionList->pluck('credit')->sum();

        $acc_coa_id = $request->acc_coa_id;
        $ledger_name = $request->ledger_name;

        $acc_type = AccCoa::find($request->acc_coa_id);

        if (($acc_type->acc_type_id == 1) || ($acc_type->acc_type_id == 2)) {
            $tablefooter['totalBalance'] = (number_format($getBalanceOpening, 2, '.', '') + number_format($tablefooter['totalDebit'], 2, '.', '')) - number_format($tablefooter['totalCradit'], 2, '.', '');
        }

        if (($acc_type->acc_type_id == 3) || ($acc_type->acc_type_id == 4) || ($acc_type->acc_type_id == 5)) {
            $tablefooter['totalBalance'] = (number_format($getBalanceOpening, 2, '.', '') + number_format($tablefooter['totalCradit'], 2, '.', '')) - number_format($tablefooter['totalDebit'], 2, '.', '');
        }

        $fromDate = $string[0];
        $toDate = $string[1];

        $date = $fromDate . ' - ' . $toDate;

        if ($request->pdf == 1) {
            $pdf = PDF::loadView('accounts::reports.pdf.cashBook', compact('accDropdown', 'date', 'fromDate', 'toDate', 'getBalanceOpening', 'getTransactionList', 'acc_coa_id', 'tablefooter', 'ledger_name'));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download('bankBook.pdf');
        }

        return view('accounts::reports.bankbook.create', compact('accDropdown', 'date', 'fromDate', 'toDate', 'getBalanceOpening', 'getTransactionList', 'acc_coa_id', 'tablefooter', 'ledger_name'));
    }

    /**
     * Display ledgergeneral page.
     */
    public function ledgergeneral()
    {

        $accDropdown = AccCoa::where('head_level', 4)->where('is_active', 1)->where('is_bank_nature', 0)->where('is_cash_nature', 0)->get();
        $fromDate = Carbon::now()->subDay(30)->format('d/m/Y');
        $toDate = date('d/m/Y');
        $date = $fromDate . ' - ' . $toDate;
        $getBalanceOpening = "";
        $getTransactionList = [];
        $tablefooter['totalBalance'] = 0;
        $tablefooter['totalDebit'] = 0;
        $tablefooter['totalCradit'] = 0;
        $acc_coa_id = '';
        $ledger_name = '';
        return view('accounts::reports.generalledger.create', compact('accDropdown', 'date', 'fromDate', 'toDate', 'getBalanceOpening', 'getTransactionList', 'acc_coa_id', 'tablefooter', 'ledger_name'));
    }

    /**
     * Generate ledgerGeneral report.
     */
    public function ledgergeneralGenerate(Request $request)
    {

        set_time_limit(0);
        if ($request->acc_coa_id == null && $request->pdf == 1) {
            toastr()->error('Please filter Date & Ledger Name');
            return redirect()->back();
        }

        $string = explode(' - ', $request->date);
        $fromDate = $string[0];
        $toDate = $string[1];
        $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');
        $acc_coa_id = $request->acc_coa_id;
        $ledger_name = $request->ledger_name;

        $request['from_date'] = $fromDate;
        $request['to_date'] = $toDate;

        $accDropdown = AccCoa::where('head_level', 4)->where('is_active', 1)->where('is_bank_nature', 0)->where('is_cash_nature', 0)->get();
        $getBalanceOpening = $this->getOpeningBalance($request);
        $getTransactionList = $this->getTransactionDetail($request, $getBalanceOpening);
        $tablefooter['totalDebit'] = $getTransactionList->pluck('debit')->sum();
        $tablefooter['totalCradit'] = $getTransactionList->pluck('credit')->sum();
        $tablefooter['totalBalance'] = count($getTransactionList) > 0 ? $getTransactionList[count($getTransactionList) - 1]->balance : $getBalanceOpening;

        $fromDate = $string[0];
        $toDate = $string[1];
        $date = $fromDate . ' - ' . $toDate;

        if ($request->pdf == 1) {
            $pdf = PDF::loadView('accounts::reports.pdf.generalLedger', compact('accDropdown', 'date', 'fromDate', 'toDate', 'getBalanceOpening', 'getTransactionList', 'acc_coa_id', 'tablefooter', 'ledger_name'));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download('generalLedger.pdf');
        }

        return view('accounts::reports.generalledger.create', compact('accDropdown', 'date', 'fromDate', 'toDate', 'getBalanceOpening', 'getTransactionList', 'acc_coa_id', 'tablefooter', 'ledger_name'));
    }

    /**
     * Display subLedger page.
     */
    public function subledger()
    {
        set_time_limit(0);

        $accSubType = AccSubtype::where('status', 1)->get();

        $fromDate = Carbon::now()->subDay(30)->format('d/m/Y');
        $toDate = date('d/m/Y');
        $date = $fromDate . ' - ' . $toDate;
        $getBalanceOpening = "";
        $getTransactionList = [];
        $tablefooter['totalBalance'] = 0;
        $tablefooter['totalDebit'] = 0;
        $tablefooter['totalCradit'] = 0;
        $subtype_id = "";
        $acc_coa_id = "";
        $acc_subcode_id = "";
        $accDropdown = [];
        $subcodeDropdown = [];

        return view('accounts::reports.subledger.create', compact('accSubType', 'acc_subcode_id', 'date', 'fromDate', 'toDate', 'getBalanceOpening', 'getTransactionList', 'acc_coa_id', 'tablefooter', 'subtype_id', 'subcodeDropdown', 'accDropdown'));
    }

    /**
     * Generate subLedger report.
     */
    public function subledgerGenerate(Request $request)
    {
        set_time_limit(0);
        if (empty($request->all())) {
            return $this->subledger();
        }

        if ($request->subtype_id == null && $request->pdf == 1) {
            toastr()->error('Please filter Date & SubType');
            return redirect()->back();
        }

        $accSubType = AccSubtype::where('status', 1)->get();

        $getBalanceOpening = "";
        $getTransactionList = [];
        $tablefooter['totalBalance'] = 0;
        $tablefooter['totalDebit'] = 0;
        $tablefooter['totalCradit'] = 0;

        $string = explode(' - ', $request->date);
        $fromDate = $string[0];
        $toDate = $string[1];
        $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');

        $request['from_date'] = $fromDate;
        $request['to_date'] = $toDate;

        $subtype_id = $request->subtype_id;
        $accDropdown = AccCoa::where('head_level', 4)->where('is_active', 1)->where('subtype_id', $subtype_id)->where('is_subtype', 1)->get();
        $acc_subcode_id = (int) $request->acc_subcode_id;
        $acc_coa_id = $request->acc_coa_id;
        $ledger_name = $request->ledger_name;
        $subcodeDropdown = AccSubcode::where('acc_subtype_id', $subtype_id)->where('status', 1)->get();

        $getBalanceOpening = $this->subLedgerOpeningBalance($request);

        $getTransactionList = $this->subLedgerTransactionDetail($request, $getBalanceOpening);
        $tablefooter['totalDebit'] = $getTransactionList->pluck('debit')->sum();
        $tablefooter['totalCradit'] = $getTransactionList->pluck('credit')->sum();
        $tablefooter['totalBalance'] = count($getTransactionList) > 0 ? $getTransactionList[count($getTransactionList) - 1]->balance : $getBalanceOpening;

        $subLedgerNameWiseData = [];
        if ($acc_subcode_id == 0) {
            $subLedgerNameWiseData = $this->subCodeIdWiseTransactionDetail($request);
            $tablefooter['totalDebit'] = $subLedgerNameWiseData->pluck('payable')->sum();
            $tablefooter['totalCradit'] = $subLedgerNameWiseData->pluck('receivable')->sum();
            $getBalanceOpening = $subLedgerNameWiseData->pluck('ach_balance')->sum();

            if ($request->subtype_id == 1 || $request->subtype_id == 2) {
                $tablefooter['totalBalance'] = (number_format($tablefooter['totalCradit'], 2, '.', '') - number_format($tablefooter['totalDebit'], 2, '.', ''));
            }
            if ($request->subtype_id == 3) {
                $tablefooter['totalBalance'] = (number_format($tablefooter['totalDebit'], 2, '.', '') - number_format($tablefooter['totalCradit'], 2, '.', ''));
            }
        }

        // total balance
        if ($request->has('previous_due') && $request->previous_due == 1) {
            $newCount = count($getTransactionList) - 1;
            $newBalance = 0;
            if ($newCount >= 0) {
                $newBalance = $getTransactionList[$newCount]->balance;
            }
            return $newBalance;
        }

        // all due balance
        if ($request->has('all_due')) {
            return $tablefooter['totalBalance'];
        }

        $fromDate = $string[0];
        $toDate = $string[1];

        $date = $fromDate . ' - ' . $toDate;

        if ($request->pdf == 1) {
            $pdf = PDF::loadView('accounts::reports.pdf.subLedger', compact('accSubType', 'date', 'fromDate', 'toDate', 'accDropdown', 'acc_coa_id', 'subtype_id', 'acc_subcode_id', 'getBalanceOpening', 'getTransactionList', 'subLedgerNameWiseData', 'acc_coa_id', 'tablefooter', 'subcodeDropdown', 'ledger_name'));
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download('subLedger.pdf');
        }

        return view('accounts::reports.subledger.create', compact('accSubType', 'date', 'fromDate', 'toDate', 'accDropdown', 'acc_coa_id', 'subtype_id', 'acc_subcode_id', 'getBalanceOpening', 'getTransactionList', 'subLedgerNameWiseData', 'acc_coa_id', 'tablefooter', 'subcodeDropdown', 'ledger_name'));
    }

    public function redirectSubLedgerGenerate(Request $request)
    {
        set_time_limit(0);
        $acc_subcode_id = AccSubcode::where('reference_no', $request->acc_subcode_id)->where('status', 1)->value('id');
        $predefine_account = AccPredefineAccount::first();
        $request['date'] = current_date_for_account();
        $request['acc_subcode_id'] = $acc_subcode_id;
        $request['acc_coa_id'] = '';
        $request['subtype_id'] = '';

        if ($request->type == "supplier") {
            $request['subtype_id'] .= 3;
            $request['acc_coa_id'] .= $predefine_account->supplier_code;
        } elseif ($request->type == "customer") {
            $request['subtype_id'] .= 2;
            $request['acc_coa_id'] .= $predefine_account->customer_code;
        }

        return $this->subledgerGenerate($request);
    }

    // Get Any Type Opening Balance
    public function getOpeningBalance($request)
    {
        // new code for get financial year & Previous Year
        $financial_years = FinancialYear::get();
        $getyearDetails = $financial_years->filter(function ($query) use ($request) {
            return $query->whereDate('start_date', '<=', $request->from_date)
                ->whereDate('end_date', '>=', $request->from_date)
                ->count() > 0;
        })->first();

        if ($getyearDetails == null) {
            $openingBalnce = 0;
            return $openingBalnce;
        }

        $previousFinanceYear = $financial_years->where('end_date', '<=', $getyearDetails->start_date)
            ->sortByDesc('end_date')
            ->first();

        if ($previousFinanceYear == null) {
            $openingBalnce = 0;
            return $openingBalnce;
        }

        // new code for get financial year & Previous Year
        $getOpeninBalance = AccOpeningBalance::where('financial_year_id', $previousFinanceYear->id)->where('acc_coa_id', $request->acc_coa_id)->get();

        $balnceResult = [];
        $coaDetail = AccCoa::findOrFail($request->acc_coa_id);

        if (($coaDetail->acc_type_id == 1) || ($coaDetail->acc_type_id == 2)) {

            foreach ($getOpeninBalance as $key => $openingvalue) {
                $dabit = $openingvalue->debit;
                $cradit = $openingvalue->credit;

                $geresult = number_format($dabit, 2, '.', '') - number_format($cradit, 2, '.', '');

                array_push($balnceResult, $geresult);
            }
        }

        if (($coaDetail->acc_type_id == 3) || ($coaDetail->acc_type_id == 4) || ($coaDetail->acc_type_id == 5)) {

            foreach ($getOpeninBalance as $key => $openingvalue) {
                $dabit = $openingvalue->debit;
                $cradit = $openingvalue->credit;

                $geresult = number_format($cradit, 2, '.', '') - number_format($dabit, 2, '.', '');

                array_push($balnceResult, $geresult);
            }
        }

        $openingBalnce = array_sum($balnceResult);
        //to_date - 1 day
        $from_date = Carbon::parse($request->from_date)->subDay(1)->format('Y-m-d') . ' 23:59:59';
        $tanjectionVouture = AccTransaction::with('voucherType', 'accCoa', 'accSubcode', 'accReverseCode')->where('acc_coa_id', $request->acc_coa_id)
            ->whereBetween('voucher_date', [$getyearDetails->start_date, $from_date])->get();

        //calculate opening balance
        $dummyBal = number_format($openingBalnce, 2, '.', '');
        foreach ($tanjectionVouture as $key => $tanjectionVoutureValue) {
            $coaDetail = Cache::remember($tanjectionVoutureValue->acc_coa_id, 60, function () use ($tanjectionVoutureValue) {
                return AccCoa::findOrFail($tanjectionVoutureValue->acc_coa_id);
            });

            if (($coaDetail->acc_type_id == 1) || ($coaDetail->acc_type_id == 2)) {
                $firstResult = number_format($tanjectionVoutureValue->debit, 2, '.', '') - number_format($tanjectionVoutureValue->credit, 2, '.', '');
                $dummyBal = number_format($dummyBal, 2, '.', '') + number_format($firstResult, 2, '.', '');
            }

            if (($coaDetail->acc_type_id == 3) || ($coaDetail->acc_type_id == 4) || ($coaDetail->acc_type_id == 5)) {
                $firstResult = number_format($tanjectionVoutureValue->credit, 2, '.', '') - number_format($tanjectionVoutureValue->debit, 2, '.', '');
                $dummyBal = number_format($dummyBal, 2, '.', '') + number_format($firstResult, 2, '.', '');
            }
        }

        return $dummyBal;
    }

    // Get Any Type subLedger Opening Balance
    public function subLedgerOpeningBalance($request)
    {

        if ((empty($request->subtype_id)) || (empty($request->acc_coa_id)) || (empty($request->acc_subcode_id)) || ($request->subtype_id == null) || ($request->acc_coa_id == null) || ($request->acc_subcode_id == null)) {

            $openingBalnce = 0;
            return $openingBalnce;
        } else {
            // new code for get financial year & Previous Year
            $financial_years = FinancialYear::get();
            $getyearDetails = $financial_years->filter(function ($query) use ($request) {
                return $query->whereDate('start_date', '<=', $request->from_date)
                    ->whereDate('end_date', '>=', $request->from_date)
                    ->count() > 0;
            })->first();

            if ($getyearDetails == null) {
                $openingBalnce = 0;
                return $openingBalnce;
            }

            $previousFinanceYear = $financial_years->where('end_date', '<=', $getyearDetails->start_date)
                ->sortByDesc('end_date')
                ->first();

            if ($previousFinanceYear == null) {
                $openingBalnce = 0;
                return $openingBalnce;
            }

            // new code for get financial year & Previous Year
            // get acc opening balance detail against coa id and previous year id

            $getOpeninBalance = AccOpeningBalance::where('financial_year_id', $previousFinanceYear->id)
                ->where('acc_coa_id', $request->acc_coa_id)
                ->where('acc_subtype_id', $request->subtype_id)
                ->when($request->acc_subcode_id, function ($query) use ($request) {

                    if ($request->acc_subcode_id != 0) {
                        return $query->where('acc_subcode_id', $request->acc_subcode_id);
                    }
                })
                ->where('acc_subcode_id', $request->acc_subcode_id)
                ->get();

            $balnceResult = [];
            $coaDetail = AccCoa::findOrFail($request->acc_coa_id);

            if (($coaDetail->acc_type_id == 1) || ($coaDetail->acc_type_id == 2)) {

                foreach ($getOpeninBalance as $key => $openingvalue) {
                    $dabit = $openingvalue->debit;
                    $cradit = $openingvalue->credit;

                    $geresult = number_format($dabit, 2, '.', '') - number_format($cradit, 2, '.', '');

                    array_push($balnceResult, $geresult);
                }
            }

            if (($coaDetail->acc_type_id == 3) || ($coaDetail->acc_type_id == 4) || ($coaDetail->acc_type_id == 5)) {

                foreach ($getOpeninBalance as $key => $openingvalue) {
                    $dabit = $openingvalue->debit;
                    $cradit = $openingvalue->credit;

                    $geresult = number_format($cradit, 2, '.', '') - number_format($dabit, 2, '.', '');

                    array_push($balnceResult, $geresult);
                }
            }

            $openingBalnce = array_sum($balnceResult);

            $from_date = Carbon::parse($request->from_date)->subDay(1)->format('Y-m-d') . ' 23:59:59';
            $tanjectionVouture = AccTransaction::where('acc_subtype_id', $request->subtype_id)
                ->when($request->acc_subcode_id !== 0, function ($query) use ($request) {
                    return $query->where('acc_subcode_id', $request->acc_subcode_id);
                })
                ->where('auto_create', false)
                ->with('accReverseCode')
                ->whereBetween('voucher_date', [$getyearDetails->start_date, $from_date])
                ->orderBy('voucher_date', 'asc')
                ->get();

            $dummyBal = $openingBalnce;
            foreach ($tanjectionVouture as $key => $tanjectionVoutureValue) {
                $coaDetail = Cache::remember($tanjectionVoutureValue->acc_coa_id, 60, function () use ($tanjectionVoutureValue) {
                    return AccCoa::findOrFail($tanjectionVoutureValue->acc_coa_id);
                });

                $debit = $tanjectionVoutureValue->debit;
                $credit = $tanjectionVoutureValue->credit;
                $balanceChange = 0;

                if ($coaDetail->acc_type_id === 1 || $coaDetail->acc_type_id === 2) {
                    $balanceChange = $debit - $credit;
                } elseif ($coaDetail->acc_type_id === 3 || $coaDetail->acc_type_id === 4 || $coaDetail->acc_type_id === 5) {
                    $balanceChange = $credit - $debit;
                }

                // Update the balance in the original array
                $dummyBal += $balanceChange;
            }

            return $dummyBal;
        }
    }

    // Get Any Transaction List against Opening Balance
    public function getTransactionDetail($request, $getBalanceOpening)
    {
        $fromDate = Carbon::parse($request->from_date)->startOfDay();
        $todate = Carbon::parse($request->to_date)->endOfDay();

        $tanjectionVouture = AccTransaction::with('voucherType', 'accCoa', 'accSubcode', 'accReverseCode')->where('acc_coa_id', $request->acc_coa_id)
            ->whereBetween('voucher_date', [$fromDate, $todate])->get();

        $dummyBal = number_format($getBalanceOpening, 2, '.', '');

        foreach ($tanjectionVouture as $key => $tanjectionVoutureValue) {

            $coaDetail = Cache::remember($tanjectionVoutureValue->acc_coa_id, 3600, function () use ($tanjectionVoutureValue) {
                return AccCoa::findOrFail($tanjectionVoutureValue->acc_coa_id);
            });

            // all cache data remove

            if (($coaDetail->acc_type_id == 1) || ($coaDetail->acc_type_id == 2)) {
                $firstResult = number_format($tanjectionVoutureValue->debit, 2, '.', '') - number_format($tanjectionVoutureValue->credit, 2, '.', '');
                $dummyBal = number_format($dummyBal, 2, '.', '') + number_format($firstResult, 2, '.', '');

                $tanjectionVouture[$key]['balance'] = number_format($dummyBal, 2, '.', '');
            }

            if (($coaDetail->acc_type_id == 3) || ($coaDetail->acc_type_id == 4) || ($coaDetail->acc_type_id == 5)) {
                $firstResult = number_format($tanjectionVoutureValue->credit, 2, '.', '') - number_format($tanjectionVoutureValue->debit, 2, '.', '');
                $dummyBal = number_format($dummyBal, 2, '.', '') + number_format($firstResult, 2, '.', '');
                $tanjectionVouture[$key]['balance'] = number_format($dummyBal, 2, '.', '');
            }
        }

        return $tanjectionVouture;
    }

    // Generate Profit Loss Report
    public function genarateProfitLoss(Request $request)
    {
        set_time_limit(0);
        if ($request->date == null && $request->pdf == 1) {
            toastr()->error('Please filter Date first');
            return redirect()->back();
        }

        $fiscalYear = FinancialYear::where('status', true)->where('is_close', false)->first();

        if ($request->date) {
            $string = explode(' - ', $request->date);
            $fromDate = $string[0];
            $toDate = $string[1];

            $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');
            $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');

            $request['start_date'] = $fromDate ?? $fiscalYear->start_date;
            $request['end_date'] = $toDate ?? $fiscalYear->end_date;
        } else {
            $request['start_date'] = $fiscalYear->start_date;
            $request['end_date'] = $fiscalYear->end_date;
        }

        //calculation for income
        $level_two_incomes = AccCoa::where('parent_id', 3)->where('is_active', true)->get();
        $level_three_incomes = $this->getCoa(3, 3);
        $level_four_incomes = $this->getCoa(3, 4);

        // $stockValuation = 0 - $this->stockValuation();
        $stockValuation = 0;
        $add_one_array_data_to_level_two = [
            "id" => 100000000000000000000000000,
            "uuid" => "af684dd7-1c7c-4013-8c8a-4a1e1694fe27",
            "account_code" => "0001",
            "account_name" => "Stock Valuation",
            "head_level" => 2,
            "parent_id" => 3,
            "acc_type_id" => 3,
            "is_cash_nature" => 0,
            "is_bank_nature" => 0,
            "is_budget" => 0,
            "is_depreciation" => 0,
            "depreciation_rate" => null,
            "is_subtype" => 0,
            "subtype_id" => null,
            "is_stock" => 0,
            "is_fixed_asset_schedule" => 0,
            "note_no" => null,
            "asset_code" => null,
            "dep_code" => null,
            "is_active" => 1,
            "created_by" => 39,
            "updated_by" => 39,
            "deleted_at" => null,
            "balance" => $stockValuation,
        ];

        $add_one_array_data_to_level_two = (object) $add_one_array_data_to_level_two;
        $level_two_incomes->push($add_one_array_data_to_level_two);

        foreach ($level_four_incomes as $income) {

            if ($request->profit_loss_type == 0) {
                $balance = $this->getClosingBalance($request->start_date, $request->end_date, $income->id);
            } else {
                $balance = $this->getPeriodicClosingBalance($request->start_date, $request->end_date, $income->id);
            }

            $income->setAttribute('balance', $balance);
        }

        $incomeBalance = 0;

        foreach ($level_two_incomes as $income2) {
            $level2IncomeBalance = 0;

            foreach ($level_three_incomes as $income3) {

                if ($income3->parent_id == $income2->id) {
                    $levelThreeBalance = $level_four_incomes->where('parent_id', $income3->id)->sum('balance');
                    $income3->setAttribute('balance', $levelThreeBalance);

                    if ($income2->parent_id == 3) {
                        $level2IncomeBalance += $levelThreeBalance;
                        $income2->setAttribute('balance', $level2IncomeBalance);
                    }
                }
            }

            $incomeBalance += $level2IncomeBalance;
        }

        // calculation for Expenses
        $level_two_expences = AccCoa::where('parent_id', 2)->where('is_active', true)->get();
        $level_three_expences = $this->getCoa(2, 3);
        $level_four_expences = $this->getCoa(2, 4);

        foreach ($level_four_expences as $expence) {

            if ($request->profit_loss_type == 0) {
                $balance = $this->getClosingBalance($request->start_date, $request->end_date, $expence->id);
            } else {
                $balance = $this->getPeriodicClosingBalance($request->start_date, $request->end_date, $expence->id);
            }

            $expence->setAttribute('balance', $balance);
        }

        $expenceBalance = 0;

        foreach ($level_two_expences as $expence2) {
            $level2IncomeBalance = 0;

            foreach ($level_three_expences as $expence3) {

                if ($expence3->parent_id == $expence2->id) {
                    $levelThreeBalance = $level_four_expences->where('parent_id', $expence3->id)->sum('balance');
                    $expence3->setAttribute('balance', $levelThreeBalance);

                    if ($expence2->parent_id == 2) {
                        $level2IncomeBalance += $levelThreeBalance;
                        $expence2->setAttribute('balance', $level2IncomeBalance);
                    }
                }
            }

            $expenceBalance += $level2IncomeBalance;
        }

        if ($request->has('total_expense')) {
            return $expenceBalance;
        }

        if ($request->date) {
            $string = explode(' - ', $request->date);
            $fromDate = $string[0];
            $toDate = $string[1];
        } else {
            $fromDate = Carbon::createFromFormat('Y-m-d', $fiscalYear->start_date)->format('d/m/Y');
            $toDate = Carbon::createFromFormat('Y-m-d', $fiscalYear->end_date)->format('d/m/Y');
        }

        if ($request->pdf == 1) {
            $pdf = PDF::loadView('accounts::reports.pdf.profitLoss', [
                'level_two_incomes' => $level_two_incomes,
                'level_three_incomes' => $level_three_incomes,
                'level_four_incomes' => $level_four_incomes,
                'incomeBalance' => ($incomeBalance + $stockValuation),
                'level_two_expences' => $level_two_expences,
                'level_three_expences' => $level_three_expences,
                'level_four_expences' => $level_four_expences,
                'expenceBalance' => $expenceBalance,
                'netLoss' => $expenceBalance - ($incomeBalance + $stockValuation),
                'netProfit' => ($incomeBalance + $stockValuation) - $expenceBalance,
                'request' => $request,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
                'date' => $fromDate . ' - ' . $toDate,
            ]);
            return $pdf->download('profit-loss.pdf');
        }

        return view('accounts::reports.profit-loss.index', [
            'level_two_incomes' => $level_two_incomes,
            'level_three_incomes' => $level_three_incomes,
            'level_four_incomes' => $level_four_incomes,
            'incomeBalance' => ($incomeBalance + $stockValuation),
            'level_two_expences' => $level_two_expences,
            'level_three_expences' => $level_three_expences,
            'level_four_expences' => $level_four_expences,
            'expenceBalance' => $expenceBalance,
            'netLoss' => $expenceBalance - ($incomeBalance - $stockValuation),
            'netProfit' => ($incomeBalance + $stockValuation) - $expenceBalance,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'request' => $request,
            'date' => $fromDate . ' - ' . $toDate,

        ]);
    }

    // Generate Balance Sheet Report
    public function genarateBalanceSheet(Request $request)
    {
        set_time_limit(0);
        if ($request->date == null && $request->pdf == 1) {
            toastr()->error('Please filter Date first');
            return redirect()->back();
        }

        $financial_years = FinancialYear::all();
        // Get the current fiscal year
        $fiscalYear = $financial_years->where('status', true)
            ->where('is_close', false)
            ->first();

        if ($request->date) {
            $string = explode(' - ', $request->date);
            $fromDate = $string[0];
            $toDate = $string[1];
            $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
            $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');

            $request['start_date'] = $fromDate ?? $fiscalYear->start_date;
            $request['end_date'] = $toDate ?? $fiscalYear->end_date;
        } else {
            $request['start_date'] = $fiscalYear->start_date;
            $request['end_date'] = $fiscalYear->end_date;
        }

        // If you need to get the current fiscal year separately, you can do it like this
        $current_year = $financial_years->where('id', $fiscalYear->id)->first();
        // Get the last three closed financial years
        $last_three_years = $financial_years->where('status', false)
            ->where('is_close', true)
            ->take(3);

        $level_two_assets = AccCoa::where('parent_id', 1)->where('is_active', true)->get();
        $level_three_assets = $this->getCoa(1, 3);
        $level_four_assets = $this->getCoa(1, 4);

        foreach ($level_four_assets as $asset) {
            $balance = $this->getClosingBalance($request->start_date, $request->end_date, $asset->id);
            $asset->setAttribute('balance', number_format($balance, 2, '.', ''));

            foreach ($last_three_years as $key => $year) {
                $year_balance = $this->getOpeningBalanceByYear($year->id, $asset->id);
                $asset->setAttribute('yearbalance' . $key, number_format($year_balance, 2, '.', ''));
            }
        }

        //set sum balance on level2 and level 3 for showing blade
        foreach ($level_two_assets as $asset2) {
            $level2AssetBalance = 0;
            foreach ($level_three_assets as $asset3) {
                if ($asset3->parent_id == $asset2->id) {
                    $levelThreeBalance = number_format($level_four_assets->where('parent_id', $asset3->id)->sum('balance'), 2, '.', '');
                    $asset3->setAttribute('balance', $levelThreeBalance);

                    if ($asset2->parent_id == 1) {
                        $level2AssetBalance += $levelThreeBalance;
                        $asset2->setAttribute('balance', $level2AssetBalance);
                    }
                }
            }
        }

        //set sum of previous three years balance on level2 and level 3 for showing blade
        foreach ($last_three_years as $key => $year) {
            foreach ($level_two_assets as $asset2) {
                $level2YearBalance = 0;
                foreach ($level_three_assets as $asset3) {
                    if ($asset3->parent_id == $asset2->id) {
                        $levelThreeBalance = number_format($level_four_assets->where('parent_id', $asset3->id)->sum('yearbalance' . $key), 2, '.', '');
                        $asset3->setAttribute('year_balance' . $key, $levelThreeBalance);

                        if ($asset2->parent_id == 1) {
                            $level2YearBalance += $levelThreeBalance;
                            $asset2->setAttribute('year_balance' . $key, $level2YearBalance);
                        }
                    }
                }
            }
        }

        $stockValuation = number_format(0, 2, '.', '');

        //calculation start for Liability
        $level_two_liabilities = AccCoa::where('parent_id', 4)->where('is_active', true)->get();

        $level_three_liabilities = $this->getCoa(4, 3);
        $level_four_liabilities = $this->getCoa(4, 4);

        foreach ($level_four_liabilities as $liability) {
            $balance = $this->getClosingBalance($request->start_date, $request->end_date, $liability->id);
            $liability->setAttribute('balance', number_format($balance, 2, '.', ''));

            foreach ($last_three_years as $key => $year) {
                $year_balance = number_format($this->getOpeningBalanceByYear($year->id, $liability->id), 2, '.', '');
                $liability->setAttribute('yearbalance' . $key, $year_balance);
            }
        }

        //set sum of liability on balance for level2 and level 3 to showing blade template
        foreach ($level_two_liabilities as $liability2) {
            $leveltwoBalance = 0;
            foreach ($level_three_liabilities as $liability3) {

                if ($liability3->parent_id == $liability2->id) {
                    $levelThreeBalance = number_format($level_four_liabilities->where('parent_id', $liability3->id)->sum('balance'), 2, '.', '');
                    $liability3->setAttribute('balance', $levelThreeBalance);

                    if ($liability2->parent_id == 4) {
                        $leveltwoBalance += $levelThreeBalance;
                        $liability2->setAttribute('balance', $leveltwoBalance);
                    }
                }
            }
        }

        $predefined_acc = AccPredefineAccount::first();
        $lastYearProfitLoss = AccCoa::find($predefined_acc->last_year_profit_loss_code);
        $currentYearProfitLoss = AccCoa::find($predefined_acc->current_year_profit_loss_code);

        $current_year_profit_loss = number_format($this->currentYearProfitLoss(), 2, '.', '');
        foreach ($last_three_years as $key => $year) {
            foreach ($level_two_liabilities as $liability2) {

                $level2YearBalance = 0;
                foreach ($level_three_liabilities as $liability3) {

                    if ($liability3->parent_id == $liability2->id) {
                        $levelThreeBalance = number_format($level_four_liabilities->where('parent_id', $liability3->id)->sum('yearbalance' . $key), 2, '.', '');
                        $liability3->setAttribute('year_balance' . $key, $levelThreeBalance);

                        if ($liability2->parent_id == 4) {
                            $level2YearBalance += $levelThreeBalance;
                            $liability2->setAttribute('year_balance' . $key, $level2YearBalance);
                        }
                    }
                }
            }
        }

        // for asset
        $stock_valuation = AccCoa::find($predefined_acc->inventory_code);
        $stockValuation = number_format(0, 2, '.', '');
        $asset_two_id = null;
        foreach ($level_three_assets as $asset3) {
            if ($asset3->id == @$stock_valuation->parent_id) {
                $asset3->balance = $stockValuation;
                $asset_two_id = $asset3->parent_id;
            }
        }

        foreach ($level_two_assets as $asset2) {
            if ($asset2->id == $asset_two_id) {
                $asset2->balance += $stockValuation;
            }
        }

        $level_four_assets->map(function ($item) use ($stock_valuation, $stockValuation) {
            if ($item->id == @$stock_valuation->id) {
                $item->balance = $stockValuation;
            }
        });

        $liability_two_id = null;
        foreach ($level_three_liabilities as $liability3) {
            if ($liability3->id == @$currentYearProfitLoss->parent_id) {
                $liability3->balance = $current_year_profit_loss;
                $liability_two_id = $liability3->parent_id;
            }
        }

        foreach ($level_two_liabilities as $liability2) {
            if ($liability2->id == $liability_two_id) {
                $liability2->balance += $current_year_profit_loss;
            }
        }

        $level_four_liabilities->map(function ($item) use ($currentYearProfitLoss, $current_year_profit_loss) {
            if ($item->id == @$currentYearProfitLoss->id) {
                $item->balance = $current_year_profit_loss;
            }
        });

        // calculation start for ShareHolder Equity
        $level_two_equities = AccCoa::where('parent_id', 5)->where('is_active', true)->get();
        $level_three_equities = $this->getCoa(5, 3);
        $level_four_equities = $this->getCoa(5, 4);

        foreach ($level_four_equities as $equity) {

            if ($predefined_acc->current_year_profit_loss_code == $equity->id) {
                $balance = $this->getCurrentYearProfitLoss();
            } else

            if ($predefined_acc->last_year_profit_loss_code == $equity->id) {
                $balance = number_format($this->getLastYearProfitLoss($predefined_acc->last_year_profit_loss_code), 2, '.', '');
            } else {
                $balance = number_format($this->getClosingBalance($request->start_date, $request->end_date, $equity->id), 2, '.', '');
            }

            $equity->setAttribute('balance', $balance);

            foreach ($last_three_years as $key => $year) {
                $year_balance = number_format($this->getOpeningBalanceByYear($year->id, $equity->id), 2, '.', '');
                $equity->setAttribute('yearbalance' . $key, $year_balance);
            }
        }

        foreach ($level_two_equities as $equity2) {
            $leveltwoBalance = 0;

            foreach ($level_three_equities as $equity3) {

                if ($equity3->parent_id == $equity2->id) {
                    $levelThreeBalance = number_format($level_four_equities->where('parent_id', $equity3->id)->sum('balance'), 2, '.', '');
                    $equity3->setAttribute('balance', $levelThreeBalance);

                    if ($equity2->parent_id == 5) {
                        $leveltwoBalance += $levelThreeBalance;
                        $equity2->setAttribute('balance', $leveltwoBalance);
                    }
                }
            }
        }

        // set sum of previous three years balance on level2 and level 3 for showing blade
        foreach ($last_three_years as $key => $year) {
            foreach ($level_two_equities as $equity2) {
                $level2YearBalance = 0;
                foreach ($level_three_equities as $equity3) {
                    if ($equity3->parent_id == $equity2->id) {
                        $levelThreeBalance = number_format($level_four_equities->where('parent_id', $equity3->id)->sum('yearbalance' . $key), 2, '.', '');
                        $equity3->setAttribute('year_balance' . $key, $levelThreeBalance);

                        if ($equity2->parent_id == 5) {
                            $level2YearBalance += $levelThreeBalance;
                            $equity2->setAttribute('year_balance' . $key, $level2YearBalance);
                        }
                    }
                }
            }
        }

        if ($request->date) {
            $string = explode(' - ', $request->date);
            $fromDate = $string[0];
            $toDate = $string[1];
        } else {
            $fromDate = Carbon::createFromFormat('Y-m-d', $fiscalYear->start_date)->format('d/m/Y');
            $toDate = Carbon::createFromFormat('Y-m-d', $fiscalYear->end_date)->format('d/m/Y');
        }

        if ($request->pdf == 1) {

            $pdf = PDF::loadView('accounts::reports.pdf.balanceSheet', [
                'level_two_assets' => $level_two_assets,
                'level_three_assets' => $level_three_assets,
                'level_four_assets' => $level_four_assets,
                'level_two_liabilities' => $level_two_liabilities,
                'level_three_liabilities' => $level_three_liabilities,
                'level_four_liabilities' => $level_four_liabilities,
                'level_two_equities' => $level_two_equities,
                'level_three_equities' => $level_three_equities,
                'level_four_equities' => $level_four_equities,
                'lastYearProfitLoss' => $lastYearProfitLoss,
                'current_year' => $current_year,
                'last_three_years' => $last_three_years,
                'request' => $request,
                'date' => $fromDate . ' - ' . $toDate,
                'fromDate' => $fromDate,
                'toDate' => $toDate,
            ]);
            return $pdf->download('balance-sheet.pdf');
        }

        return view('accounts::reports.balance-sheet.index', [
            'level_two_assets' => $level_two_assets,
            'level_three_assets' => $level_three_assets,
            'level_four_assets' => $level_four_assets,
            'level_two_liabilities' => $level_two_liabilities,
            'level_three_liabilities' => $level_three_liabilities,
            'level_four_liabilities' => $level_four_liabilities,
            'level_two_equities' => $level_two_equities,
            'level_three_equities' => $level_three_equities,
            'level_four_equities' => $level_four_equities,
            'lastYearProfitLoss' => $lastYearProfitLoss,
            'current_year' => $current_year,
            'last_three_years' => $last_three_years,
            'request' => $request,
            'date' => $fromDate . ' - ' . $toDate,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }

    // Get Opening Balance By Year
    private function getOpeningBalanceByYear($year_id, $coa_id)
    {
        $getOpeninBalance = AccOpeningBalance::where('financial_year_id', $year_id)->where('acc_coa_id', $coa_id)->get();
        $coaDetail = AccCoa::findOrFail($coa_id);
        $balnceResult = [];

        if (($coaDetail->acc_type_id == 1) || ($coaDetail->acc_type_id == 2)) {

            foreach ($getOpeninBalance as $key => $openingvalue) {
                $dabit = $openingvalue->debit;
                $cradit = $openingvalue->credit;
                $geresult = number_format($dabit, 2, '.', '') - number_format($cradit, 2, '.', '');
                array_push($balnceResult, $geresult);
            }
        }

        if (($coaDetail->acc_type_id == 3) || ($coaDetail->acc_type_id == 4) || ($coaDetail->acc_type_id == 5)) {

            foreach ($getOpeninBalance as $key => $openingvalue) {
                $dabit = $openingvalue->debit;
                $cradit = $openingvalue->credit;
                $geresult = number_format($cradit, 2, '.', '') - number_format($dabit, 2, '.', '');
                array_push($balnceResult, $geresult);
            }
        }

        $openingBalnce = array_sum($balnceResult);
        return $openingBalnce;
    }

    // Get Current Year Profit Loss
    private function getCurrentYearProfitLoss()
    {
        $current_year = FinancialYear::where('status', true)->where('is_close', false)->first();
        $level_four_incomes = $this->getCoa(3, 4);

        foreach ($level_four_incomes as $income) {
            $balance = $this->getPeriodicClosingBalance($current_year->start_date, $current_year->end_date, $income->id);
            $income->setAttribute('balance', $balance);
        }

        $total_income = $level_four_incomes->sum('balance');

        $level_four_expences = $this->getCoa(2, 4);

        foreach ($level_four_expences as $expences) {
            $balance = $this->getPeriodicClosingBalance($current_year->start_date, $current_year->end_date, $expences->id);
            $expences->setAttribute('balance', $balance);
        }

        $total_expences = $level_four_expences->sum('balance');
        $profit_loss = $total_income - $total_expences;
        return $profit_loss;
    }

    // Get Last Year Profit Loss
    private function getLastYearProfitAndLoss()
    {
        $last_year = FinancialYear::where('status', false)->where('is_close', true)->orderBy('id', 'desc')->first();

        $level_four_incomes = $this->getCoa(3, 4);

        foreach ($level_four_incomes as $income) {
            $balance = $this->getPeriodicClosingBalance($last_year->start_date, $last_year->end_date, $income->id);
            $income->setAttribute('balance', $balance);
        }

        $total_income = $level_four_incomes->sum('balance');

        $level_four_expences = $this->getCoa(2, 4);

        foreach ($level_four_expences as $expences) {
            $balance = $this->getPeriodicClosingBalance($last_year->start_date, $last_year->end_date, $expences->id);
            $expences->setAttribute('balance', $balance);
        }

        $total_expences = $level_four_expences->sum('balance');
        $profit_loss = $total_income - $total_expences;
        return $profit_loss;
    }
    // Get Last Year Profit Loss
    private function getLastYearProfitLoss($coa_id)
    {

        $openingBalance = AccOpeningBalance::where('acc_coa_id', $coa_id)->get();

        $coaDetail = Cache::remember($coa_id, 3600, function () use ($coa_id) {
            return AccCoa::findOrFail($coa_id);
        });

        if (($coaDetail->acc_type_id == 1) || ($coaDetail->acc_type_id == 2)) {
            return $openingBalance->sum('debit') - $openingBalance->sum('credit');
        }

        if (($coaDetail->acc_type_id == 3) || ($coaDetail->acc_type_id == 4) || ($coaDetail->acc_type_id == 5)) {
            return $openingBalance->sum('credit') - $openingBalance->sum('debit');
        }
    }

    // acc subcode id wise TransactionDetail
    public function subCodeIdWiseTransactionDetail($request)
    {
        // acc_subcodes
        $acc_subcodes = AccSubcode::with('supplier:id,address', 'customer:id,customer_address')->where('acc_subtype_id', $request->subtype_id)->get();
        foreach ($acc_subcodes as $acc_subcode) {
            $request->acc_subcode_id = $acc_subcode->id;
            $getBalanceOpening = $this->subLedgerOpeningBalance($request);

            $subLedgerTransactionDetail = $this->subLedgerTransactionDetail($request, $getBalanceOpening);
            $lastBalance = count($subLedgerTransactionDetail) > 0 ? $subLedgerTransactionDetail[count($subLedgerTransactionDetail) - 1]->balance : 0;

            $debit = $subLedgerTransactionDetail->pluck('debit')->sum();
            $credit = $subLedgerTransactionDetail->pluck('credit')->sum();
            $balance = 0;

            if ($request->subtype_id == 1 || $request->subtype_id == 2) {
                $balance = ($debit - $credit);
                $acc_subcode->setAttribute('payable', 0);
                $acc_subcode->setAttribute('receivable', $getBalanceOpening + $balance);
                $acc_subcode->setAttribute('last_balance', $lastBalance);
                $acc_subcode->setAttribute('ach_balance', $getBalanceOpening);
            }

            if ($request->subtype_id == 3) {
                $balance = ($credit - $debit);
                $acc_subcode->setAttribute('payable', $getBalanceOpening + $balance);
                $acc_subcode->setAttribute('receivable', 0);
                $acc_subcode->setAttribute('last_balance', $lastBalance);
                $acc_subcode->setAttribute('ach_balance', $getBalanceOpening);
            }
        }

        return $acc_subcodes;
    }

    // Sub Ledger Transaction Detail
    public function subLedgerTransactionDetail($request, $getBalanceOpening)
    {
        $fromDate = Carbon::parse($request->from_date)->startOfDay();
        $todate = Carbon::parse($request->to_date)->endOfDay();

        $tanjectionVouture = AccTransaction::where('acc_subtype_id', $request->subtype_id)
            ->when($request->acc_subcode_id !== 0, function ($query) use ($request) {
                return $query->where('acc_subcode_id', $request->acc_subcode_id);
            })
            ->where('auto_create', false)
            ->with('accReverseCode')
            ->whereBetween('voucher_date', [$fromDate, $todate])
            ->orderBy('voucher_date', 'asc')
            ->get();

        $dummyBal = $getBalanceOpening;

        foreach ($tanjectionVouture as $key => $tanjectionVoutureValue) {
            $coaDetail = Cache::remember($tanjectionVoutureValue->acc_coa_id, 60, function () use ($tanjectionVoutureValue) {
                return AccCoa::findOrFail($tanjectionVoutureValue->acc_coa_id);
            });

            $debit = $tanjectionVoutureValue->debit;
            $credit = $tanjectionVoutureValue->credit;
            $balanceChange = 0;

            if ($coaDetail->acc_type_id === 1 || $coaDetail->acc_type_id === 2) {
                $balanceChange = $debit - $credit;
            } elseif ($coaDetail->acc_type_id === 3 || $coaDetail->acc_type_id === 4 || $coaDetail->acc_type_id === 5) {
                $balanceChange = $credit - $debit;
            }

            // Update the balance in the original array
            $dummyBal += $balanceChange;
            $tanjectionVouture[$key]['balance'] = number_format($dummyBal, 2, '.', '');
        }

        return $tanjectionVouture;
    }

    // Trial Balance
    public function trilbalance()
    {
        $fiscalYear = FinancialYear::where('status', true)->where('is_close', false)->first();

        $fromDate = Carbon::parse($fiscalYear->start_date)->format('d-m-Y');
        $toDate = Carbon::parse($fiscalYear->end_date)->format('d-m-Y');
        $date = $fromDate . ' - ' . $toDate;

        $tablefooter['totalBalance'] = 0;
        $tablefooter['totalDebit'] = 0;
        $tablefooter['totalCradit'] = 0;
        $trailBalnce = [];
        $trail_balnce_type = 2;
        $trilbalCollection = new Collection();

        return view('accounts::reports.trailbalance.create', compact('trailBalnce', 'trail_balnce_type', 'date', 'fromDate', 'toDate', 'tablefooter', 'trilbalCollection'));
    }

    // Trial Balance Generate
    public function trilbalancelGenerate(Request $request)
    {
        if ($request->date == null && $request->pdf == 1) {
            toastr()->error('Please filter Date first');
            return redirect()->back();
        }

        $accDropdown = AccCoa::with('secondChild', 'secondChild.thirdChild', 'secondChild.thirdChild.fourthChild')->parent()->active()->get();

        $string = explode(' - ', $request->date);
        $fromDate = $string[0];
        $toDate = $string[1];
        $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');

        $tablefooter['totalBalance'] = 0;
        $tablefooter['totalDebit'] = 0;
        $tablefooter['totalCradit'] = 0;
        $trailBalnce = $accDropdown;
        $trailBalanc = [];

        $trilbalCollection = new Collection;

        $sub_code = null;

        $newkey = 0;

        foreach ($accDropdown as $key => $value) {
            $newkey += 1;

            $trailBalanc[$newkey]['id'] = $value->id;
            $trailBalanc[$newkey]['account_code'] = $value->account_code;
            $trailBalanc[$newkey]['account_name'] = $value->account_name;
            $trailBalanc[$newkey]['acc_type_id'] = $value->acc_type_id;
            $trailBalanc[$newkey]['parent_id'] = $value->parent_id;
            $trailBalanc[$newkey]['head_level'] = $value->head_level;

            if (($value->acc_type_id == 1) || ($value->acc_type_id == 2)) {
                $trailBalanc[$newkey]['opening_balance_debit'] = $this->openingBalance($fromDate, $toDate, $value->id, $sub_code); // opening balance //  1, 2 => first coloumn 3.4.5 second co
                $trailBalanc[$newkey]['opening_balance_credit'] = 0;
                $trailBalanc[$newkey]['closing_balance_debit'] = $this->getClosingBalance($fromDate, $toDate, $value->id, $sub_code); // 1, 2 => 4th coloumn 3.4.5 5th co
                $trailBalanc[$newkey]['closing_balance_credit'] = 0;
            }

            if (($value->acc_type_id == 3) || ($value->acc_type_id == 4) || ($value->acc_type_id == 5)) {
                $trailBalanc[$newkey]['opening_balance_debit'] = 0;
                $trailBalanc[$newkey]['opening_balance_credit'] = $this->openingBalance($fromDate, $toDate, $value->id, $sub_code); // opening balance //  1, 2 => first coloumn 3.4.5 second co
                $trailBalanc[$newkey]['closing_balance_debit'] = 0;
                $trailBalanc[$newkey]['closing_balance_credit'] = $this->getClosingBalance($fromDate, $toDate, $value->id, $sub_code);
            }

            $trailBalanc[$newkey]['tran_blance_debit'] = $this->getDebitBalance($fromDate, $toDate, $value->id, $sub_code); // getDebitBalance sum debit balance
            $trailBalanc[$newkey]['tran_blance_credit'] = $this->getCreditBalance($fromDate, $toDate, $value->id, $sub_code);

            // getCreditBalance sum debit balance

            // second child
            if ($value->secondChild->count() > 0) {
                foreach ($value->secondChild as $key1 => $value1) {
                    $newkey += 1;

                    $trailBalanc[$newkey]['id'] = $value1->id;
                    $trailBalanc[$newkey]['account_code'] = $value1->account_code;
                    $trailBalanc[$newkey]['account_name'] = $value1->account_name;
                    $trailBalanc[$newkey]['acc_type_id'] = $value1->acc_type_id;
                    $trailBalanc[$newkey]['parent_id'] = $value1->parent_id;
                    $trailBalanc[$newkey]['head_level'] = $value1->head_level;

                    if (($value1->acc_type_id == 1) || ($value1->acc_type_id == 2)) {
                        $trailBalanc[$newkey]['opening_balance_debit'] = $this->openingBalance($fromDate, $toDate, $value1->id, $sub_code); // opening balance //  1, 2 => first coloumn 3.4.5 second co
                        $trailBalanc[$newkey]['opening_balance_credit'] = 0;
                        $trailBalanc[$newkey]['closing_balance_debit'] = $this->getClosingBalance($fromDate, $toDate, $value1->id, $sub_code); // 1, 2 => 4th coloumn 3.4.5 5th co
                        $trailBalanc[$newkey]['closing_balance_credit'] = 0;
                    }

                    if (($value1->acc_type_id == 3) || ($value1->acc_type_id == 4) || ($value1->acc_type_id == 5)) {
                        $trailBalanc[$newkey]['opening_balance_debit'] = 0;
                        $trailBalanc[$newkey]['opening_balance_credit'] = $this->openingBalance($fromDate, $toDate, $value1->id, $sub_code); // opening balance //  1, 2 => first coloumn 3.4.5 second co
                        $trailBalanc[$newkey]['closing_balance_debit'] = 0;
                        $trailBalanc[$newkey]['closing_balance_credit'] = $this->getClosingBalance($fromDate, $toDate, $value1->id, $sub_code);
                    }

                    $trailBalanc[$newkey]['tran_blance_debit'] = $this->getDebitBalance($fromDate, $toDate, $value1->id, $sub_code); // getDebitBalance sum debit balance
                    $trailBalanc[$newkey]['tran_blance_credit'] = $this->getCreditBalance($fromDate, $toDate, $value1->id, $sub_code);

                    // getCreditBalance sum debit balance

                    //third child
                    if ($value1->thirdChild->count() > 0) {
                        foreach ($value1->thirdChild as $key2 => $value2) {
                            $newkey += 1;

                            $trailBalanc[$newkey]['id'] = $value2->id;
                            $trailBalanc[$newkey]['account_code'] = $value2->account_code;
                            $trailBalanc[$newkey]['account_name'] = $value2->account_name;
                            $trailBalanc[$newkey]['acc_type_id'] = $value2->acc_type_id;
                            $trailBalanc[$newkey]['parent_id'] = $value2->parent_id;
                            $trailBalanc[$newkey]['head_level'] = $value2->head_level;

                            if (($value2->acc_type_id == 1) || ($value2->acc_type_id == 2)) {
                                $trailBalanc[$newkey]['opening_balance_debit'] = $this->openingBalance($fromDate, $toDate, $value2->id, $sub_code); // opening balance //  1, 2 => first coloumn 3.4.5 second co
                                $trailBalanc[$newkey]['opening_balance_credit'] = 0;
                                $trailBalanc[$newkey]['closing_balance_debit'] = $this->getClosingBalance($fromDate, $toDate, $value2->id, $sub_code); // 1, 2 => 4th coloumn 3.4.5 5th co
                                $trailBalanc[$newkey]['closing_balance_credit'] = 0;
                            }

                            if (($value2->acc_type_id == 3) || ($value2->acc_type_id == 4) || ($value2->acc_type_id == 5)) {
                                $trailBalanc[$newkey]['opening_balance_debit'] = 0;
                                $trailBalanc[$newkey]['opening_balance_credit'] = $this->openingBalance($fromDate, $toDate, $value2->id, $sub_code); // opening balance //  1, 2 => first coloumn 3.4.5 second co
                                $trailBalanc[$newkey]['closing_balance_debit'] = 0;
                                $trailBalanc[$newkey]['closing_balance_credit'] = $this->getClosingBalance($fromDate, $toDate, $value2->id, $sub_code);
                            }

                            $trailBalanc[$newkey]['tran_blance_debit'] = $this->getDebitBalance($fromDate, $toDate, $value2->id, $sub_code); // getDebitBalance sum debit balance
                            $trailBalanc[$newkey]['tran_blance_credit'] = $this->getCreditBalance($fromDate, $toDate, $value2->id, $sub_code);

                            // getCreditBalance sum debit balance

                            //fourth child
                            if ($value2->fourthChild->count() > 0) {
                                foreach ($value2->fourthChild as $key3 => $value3) {
                                    $newkey = $newkey + 1;
                                    $trailBalanc[$newkey]['id'] = $value3->id;
                                    $trailBalanc[$newkey]['account_code'] = $value3->account_code;
                                    $trailBalanc[$newkey]['account_name'] = $value3->account_name;
                                    $trailBalanc[$newkey]['acc_type_id'] = $value3->acc_type_id;
                                    $trailBalanc[$newkey]['parent_id'] = $value3->parent_id;
                                    $trailBalanc[$newkey]['head_level'] = $value3->head_level;

                                    if (($value3->acc_type_id == 1) || ($value3->acc_type_id == 2)) {
                                        $trailBalanc[$newkey]['opening_balance_debit'] = $this->openingBalance($fromDate, $toDate, $value3->id, $sub_code); // opening balance //  1, 2 => first coloumn 3.4.5 second co
                                        $trailBalanc[$newkey]['opening_balance_credit'] = 0;
                                        $trailBalanc[$newkey]['closing_balance_debit'] = $this->getClosingBalance($fromDate, $toDate, $value3->id, $sub_code); // 1, 2 => 4th coloumn 3.4.5 5th co
                                        $trailBalanc[$newkey]['closing_balance_credit'] = 0;
                                    }

                                    if (($value3->acc_type_id == 3) || ($value3->acc_type_id == 4) || ($value3->acc_type_id == 5)) {
                                        $trailBalanc[$newkey]['opening_balance_debit'] = 0;
                                        $trailBalanc[$newkey]['opening_balance_credit'] = $this->openingBalance($fromDate, $toDate, $value3->id, $sub_code); // opening balance //  1, 2 => first coloumn 3.4.5 second co
                                        $trailBalanc[$newkey]['closing_balance_debit'] = 0;
                                        $trailBalanc[$newkey]['closing_balance_credit'] = $this->getClosingBalance($fromDate, $toDate, $value3->id, $sub_code);
                                    }

                                    $trailBalanc[$newkey]['tran_blance_debit'] = $this->getDebitBalance($fromDate, $toDate, $value3->id, $sub_code); // getDebitBalance sum debit balance
                                    $trailBalanc[$newkey]['tran_blance_credit'] = $this->getCreditBalance($fromDate, $toDate, $value3->id, $sub_code); // getCreditBalance sum debit balance

                                }
                            }
                        }
                    }
                }
            }
        }

        $trailBalnce = $trailBalanc;

        $trilbalCollection = new Collection($trailBalanc);

        $fromDate = $string[0];
        $toDate = $string[1];

        $date = $fromDate . ' - ' . $toDate;

        $trail_balnce_type = $request->trail_balnce_type;

        if ($request->pdf == 1) {
            $pdf = PDF::loadView('accounts::reports.pdf.trailBalance', compact('trailBalnce', 'trail_balnce_type', 'date', 'fromDate', 'toDate', 'tablefooter', 'trilbalCollection'));
            return $pdf->download('trailbalance.pdf');
        }

        return view('accounts::reports.trailbalance.create', compact('trailBalnce', 'trail_balnce_type', 'date', 'fromDate', 'toDate', 'tablefooter', 'trilbalCollection'));
    }

    // Day Book
    public function daybook()
    {
        $accVoucherDropdown = AccVoucherType::where('is_active', 1)->get();
        $fromDate = Carbon::now()->format('d/m/Y');
        $toDate = Carbon::now()->format('d/m/Y');
        $date = $fromDate . ' - ' . $toDate;
        $vature = [];
        $voucher_type_id = "";
        $ledger_name = "";

        $getBalanceOpening = "";
        $getTransactionList = [];
        $tablefooter['totalBalance'] = 0;

        return view('accounts::reports.daybook.create', compact('accVoucherDropdown', 'date', 'vature', 'fromDate', 'toDate', 'voucher_type_id', 'ledger_name', 'getBalanceOpening', 'getTransactionList', 'tablefooter'));
    }

    // Day Book Generate
    public function daybookGenerate(Request $request)
    {
        if ($request->voucher_type_id == null && $request->pdf == 1) {
            toastr()->error('Please filter Date & Voucher Name');
            return redirect()->back();
        }

        $string = explode(' - ', $request->date);
        $fromDate = $string[0];
        $toDate = $string[1];
        $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');

        $accVoucherDropdown = AccVoucherType::where('is_active', 1)->get();
        if ($request->voucher_type_id == "all") {
            $vature = AccVoucher::whereBetween('voucher_date', [$fromDate, $toDate])->get();
        } else {
            $vature = AccVoucher::whereBetween('voucher_date', [$fromDate, $toDate])->where('voucher_type', $request->voucher_type_id)->get();
        }

        $voucher_type_id = $request->voucher_type_id;
        $ledger_name = $request->ledger_name;
        $fromDate = $string[0];
        $toDate = $string[1];

        $date = $fromDate . ' - ' . $toDate;
        if ($request->pdf == 1) {
            $pdf = PDF::loadView('accounts::reports.pdf.dayBook', compact('accVoucherDropdown', 'date', 'vature', 'fromDate', 'toDate', 'voucher_type_id', 'ledger_name'));
            return $pdf->download('daybook.pdf');
        }

        return view('accounts::reports.daybook.create', compact('accVoucherDropdown', 'vature', 'date', 'fromDate', 'toDate', 'voucher_type_id', 'ledger_name'));
    }

    //Receipt Payment
    public function receiptpayment()
    {
        $fromDate = Carbon::now()->subDay(30)->format('d/m/Y');
        $toDate = date('d/m/Y');
        $date = $fromDate . ' - ' . $toDate;
        $vature = [];
        $cash_bank_nature_id = "";

        return view('accounts::reports.paymentreceipt.load', compact('vature', 'date', 'fromDate', 'toDate', 'cash_bank_nature_id'));
    }

    //Receipt Payment Generate
    public function receiptpaymentGenerate(Request $request)
    {

        if ($request->date == null && $request->pdf == 1) {
            toastr()->error('Please filter Date & Ledger Type');
            return redirect()->back();
        }

        $string = explode(' - ', $request->date);
        $fromDate = $string[0];
        $toDate = $string[1];
        $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');

        $cashNatureParent = "";
        $bankNatureParent = "";
        $sumCashNature = 0;
        $sumBankNature = 0;
        $closingsumCashNature = 0;
        $closingsumBankNature = 0;
        $sub_code = null;
        $cashNature = AccCoa::where('is_active', 1)
            ->where('head_level', 4)
            ->where('acc_type_id', 1)
            ->where('is_cash_nature', 1)
            ->get();
        $bankNature = AccCoa::where('is_active', 1)
            ->where('head_level', 4)
            ->where('acc_type_id', 1)
            ->where('is_bank_nature', 1)
            ->get();

        foreach ($cashNature as $key => $cashvalue) {

            $openingBalance = $this->openingBalance($fromDate, $toDate, $cashvalue->id, $sub_code);
            $sumCashNature = number_format($sumCashNature, 2, '.', '') + number_format($openingBalance, 2, '.', '');
            $cashNatureParent = $cashvalue->parent_id;
            $closingBalance = $this->getClosingBalance($fromDate, $toDate, $cashvalue->id, $sub_code);
            $closingsumCashNature = number_format($closingsumCashNature, 2, '.', '') + number_format($closingBalance, 2, '.', '');
        }

        foreach ($bankNature as $key => $bankvalue) {

            $bnkopeningBalance = $this->openingBalance($fromDate, $toDate, $bankvalue->id, $sub_code);
            $sumBankNature = number_format($sumBankNature, 2, '.', '') + number_format($bnkopeningBalance, 2, '.', '');
            $bankNatureParent = $bankvalue->parent_id;
            $bankClosingBalance = $this->getClosingBalance($fromDate, $toDate, $bankvalue->id, $sub_code);
            $closingsumBankNature = number_format($closingsumBankNature, 2, '.', '') + number_format($bankClosingBalance, 2, '.', '');
        }

        $cashParent = AccCoa::findOrFail($cashNatureParent);
        $cashParent->totalOpening = $sumCashNature;
        $cashParent->totalClosing = $closingsumCashNature;

        $bankParent = AccCoa::findOrFail($bankNatureParent);
        $bankParent->totalOpening = $sumBankNature;
        $bankParent->totalClosing = $closingsumBankNature;
        $cash_bank_nature_id = $request->cash_bank_nature_id;

        $preDefine = AccPredefineAccount::first();

        $advanceHead = AccCoa::where('is_active', 1)
            ->where('head_level', 4)
            ->where('parent_id', $preDefine->advance)
            ->get();

        $adVanceLedger = AccCoa::findOrFail($preDefine->advance);
        $advanceSum = 0;
        $closingadvanceSum = 0;

        foreach ($advanceHead as $key => $advancevalue) {
            $openingadvBalancea = $this->openingBalance($fromDate, $toDate, $advancevalue->id, $sub_code);
            $advanceSum = number_format($advanceSum, 2, '.', '') + number_format($openingadvBalancea, 2, '.', '');
            $closingadvBalancea = $this->getClosingBalance($fromDate, $toDate, $advancevalue->id, $sub_code);

            $closingadvanceSum = number_format($closingadvBalancea, 2, '.', '');
        }

        $adVanceLedger->totalOpening = $advanceSum;
        $adVanceLedger->totalClosing = $closingadvanceSum;

        $voutureType = 2;
        $recieptThirdLableCoaFullDetail = $this->getThirdLableCoaDetail($fromDate, $toDate, $voutureType);

        $receiptThirdLevelDetail = $recieptThirdLableCoaFullDetail['thirdLableFullCoaDetail'];
        $receiptfourthLavelFinal = $recieptThirdLableCoaFullDetail['childofFourthLableFinal'];

        $voutureType = 1;
        $paymentThirdLableCoaFullDetail = $this->getThirdLableCoaDetail($fromDate, $toDate, $voutureType);
        // dd($paymentThirdLableCoaFullDetail);

        $paymentThirdLevelDetail = $paymentThirdLableCoaFullDetail['thirdLableFullCoaDetail'];
        $paymentfourthLavelFinal = $paymentThirdLableCoaFullDetail['childofFourthLableFinal'];

        $fromDate = $string[0];
        $toDate = $string[1];
        $date = $fromDate . ' - ' . $toDate;

        if ($request->pdf == 1) {
            $pdf = PDF::loadView('accounts::reports.pdf.paymentReceipt', compact('paymentThirdLevelDetail', 'paymentfourthLavelFinal', 'receiptThirdLevelDetail', 'receiptfourthLavelFinal', 'cashParent', 'bankParent', 'adVanceLedger', 'date', 'fromDate', 'toDate', 'cash_bank_nature_id'));
            return $pdf->download('paymentreceipt.pdf');
        }

        return view('accounts::reports.paymentreceipt.create', compact('paymentThirdLevelDetail', 'paymentfourthLavelFinal', 'receiptThirdLevelDetail', 'receiptfourthLavelFinal', 'cashParent', 'bankParent', 'adVanceLedger', 'date', 'fromDate', 'toDate', 'cash_bank_nature_id'));
    }

    //Get Third Lable Coa Detail
    public function getThirdLableCoaDetail($fromDate, $todate, $voutureType)
    {
        $allVouture = AccVoucher::where('voucher_type', $voutureType)
            ->where('is_approved', 1)
            ->whereBetween('voucher_date', [$fromDate, $todate])
            ->get();

        $coaWithGroupBy = $allVouture->groupBy('acc_coa_id');

        $allCoaDetail = AccCoa::where('is_active', 1)->get();

        $fourthLableCoaId = $coaWithGroupBy->keys();

        $fourLableCoaDetail = $allCoaDetail->whereIn('id', $fourthLableCoaId);

        $thirdLableCoaId = $fourLableCoaDetail->pluck('parent_id');

        $thirdLableFullCoaDetail = $allCoaDetail->whereIn('id', $thirdLableCoaId);

        $childofThirdLable = AccCoa::whereIn('parent_id', $thirdLableCoaId)->get();

        $rciept['thirdLableFullCoaDetail'] = $thirdLableFullCoaDetail;

        $fourthLableCoaWithSum = collect();

        foreach ($coaWithGroupBy as $coakey => $coaval) {

            $pushArray = [
                "id" => $coakey,
                "credit" => $voutureType == 2 ? $coaval->sum('credit') : $coaval->sum('debit'),
            ];
            $fourthLableCoaWithSum->push($pushArray);
        }

        $finalcollection = collect();
        $comparecollec = $fourthLableCoaWithSum->pluck('id');

        foreach ($childofThirdLable as $fourkey => $fourthvalue) {

            if ($comparecollec->contains($fourthvalue->id)) {

                $getForthval = $fourthLableCoaWithSum->firstWhere('id', $fourthvalue->id);

                $pushArray = [
                    "id" => $fourthvalue->id,
                    "credit" => $getForthval['credit'],
                    "parent_id" => $fourthvalue->parent_id,
                    "account_name" => $fourthvalue->account_name,
                ];
            } else {
                $pushArray = [
                    "id" => $fourthvalue->id,
                    "credit" => 0.00,
                    "parent_id" => $fourthvalue->parent_id,
                    "account_name" => $fourthvalue->account_name,
                ];
            }

            $finalcollection->push($pushArray);
        }

        $rciept['childofFourthLableFinal'] = $finalcollection;
        return $rciept;
    }

    // Control Ledger
    public function controlLedger(Request $request)
    {

        if ($request->date == null && $request->pdf == 1) {
            toastr()->error('Please filter Date & Ledger Type');
            return redirect()->back();
        }

        if ($request->date == null) {
            $fromDate = Carbon::now()->subDays(30)->format('Y-m-d');
            $toDate = Carbon::now()->format('Y-m-d');
        } else {
            $string = explode(' - ', $request->date);
            $fromDate = $string[0];
            $toDate = $string[1];
            $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
            $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');
        }

        $accLevelThreeDropdown = AccCoa::where('is_active', 1)
            ->where('head_level', 3)
            ->get();
        $acc_coa_id = $request->acc_coa_id;

        // get head level 3 coa id
        if ($acc_coa_id == null) {
            $accForthHead = [];
            $accForthHeadWithAmount = [];
            $ledger_name = null;
        } else {
            $ledger_name = $request->ledger_name;
            $accForthHead = AccCoa::where('parent_id', $acc_coa_id)->get();
            // calculate amount of head level 4
            $sub_code = null;
            $accForthHeadWithAmount = [];

            foreach ($accForthHead as $key => $value) {
                $accForthHeadWithAmount[$key]['id'] = $value->id;
                $accForthHeadWithAmount[$key]['account_name'] = $value->account_name;

                if ($value->acc_type_id == 1 || $value->acc_type_id == 2) {
                    $accForthHeadWithAmount[$key]['debit'] = $this->getClosingBalance($fromDate, $toDate, $value->id, $sub_code);
                } else {
                    $accForthHeadWithAmount[$key]['debit'] = 0.00;
                }

                if ($value->acc_type_id == 3 || $value->acc_type_id == 4) {
                    $accForthHeadWithAmount[$key]['credit'] = $this->getClosingBalance($fromDate, $toDate, $value->id, $sub_code);
                } else {
                    $accForthHeadWithAmount[$key]['credit'] = 0.00;
                }
            }
        }

        if ($request->date == null) {
            $fromDate = Carbon::now()->subDays(30)->format('d/m/Y');
            $toDate = Carbon::now()->format('d/m/Y');
            $date = $fromDate . ' - ' . $toDate;
        } else {
            $string = explode(' - ', $request->date);
            $fromDate = $string[0];
            $toDate = $string[1];
            $date = $fromDate . ' - ' . $toDate;
        }

        if ($request->pdf == 1) {
            $pdf = PDF::loadView('accounts::reports.pdf.controlLedger', compact('accLevelThreeDropdown', 'acc_coa_id', 'accForthHead', 'accForthHeadWithAmount', 'date', 'fromDate', 'toDate', 'ledger_name'));
            return $pdf->download('control-ledger.pdf');
        }

        return view('accounts::reports.control-ledger.index', compact('accLevelThreeDropdown', 'acc_coa_id', 'accForthHead', 'accForthHeadWithAmount', 'date', 'fromDate', 'toDate', 'ledger_name'));
    }

    // Note Ledger
    public function noteLedger(Request $request)
    {
        if ($request->date == null && $request->pdf == 1) {
            toastr()->error('Please filter Date & Ledger Type');
            return redirect()->back();
        }

        if ($request->date == null) {
            $fromDate = Carbon::now()->subDays(30)->format('Y-m-d');
            $toDate = Carbon::now()->format('Y-m-d');
        } else {
            $string = explode(' - ', $request->date);
            $fromDate = $string[0];
            $toDate = $string[1];
            $fromDate = Carbon::createFromFormat('d/m/Y', $fromDate)->format('Y-m-d');
            $toDate = Carbon::createFromFormat('d/m/Y', $toDate)->format('Y-m-d');
        }

        $accLevelThreeDropdown = AccCoa::where('is_active', 1)
            ->where('head_level', 3)
            ->whereNotNull('note_no')
            ->get();
        $acc_coa_id = $request->acc_coa_id;

        // get head level 3 coa id
        if ($acc_coa_id == null) {
            $accForthHead = [];
            $accForthHeadWithAmount = [];
            $ledger_name = null;
        } else {
            $ledger_name = $request->ledger_name;
            $accForthHead = AccCoa::where('parent_id', $acc_coa_id)->get();
            // calculate amount of head level 4
            $sub_code = null;
            $accForthHeadWithAmount = [];

            foreach ($accForthHead as $key => $value) {
                $accForthHeadWithAmount[$key]['id'] = $value->id;
                $accForthHeadWithAmount[$key]['account_name'] = $value->account_name;

                if ($value->acc_type_id == 1 || $value->acc_type_id == 2) {
                    $accForthHeadWithAmount[$key]['debit'] = $this->getClosingBalance($fromDate, $toDate, $value->id, $sub_code);
                } else {
                    $accForthHeadWithAmount[$key]['debit'] = 0.00;
                }

                if ($value->acc_type_id == 3 || $value->acc_type_id == 4) {
                    $accForthHeadWithAmount[$key]['credit'] = $this->getClosingBalance($fromDate, $toDate, $value->id, $sub_code);
                } else {
                    $accForthHeadWithAmount[$key]['credit'] = 0.00;
                }
            }
        }

        if ($request->date == null) {
            $fromDate = Carbon::now()->subDays(30)->format('d/m/Y');
            $toDate = Carbon::now()->format('d/m/Y');
            $date = $fromDate . ' - ' . $toDate;
        } else {
            $string = explode(' - ', $request->date);
            $fromDate = $string[0];
            $toDate = $string[1];
            $date = $fromDate . ' - ' . $toDate;
        }

        if ($request->pdf == 1) {
            $pdf = PDF::loadView('accounts::reports.pdf.noteLedger', compact('accLevelThreeDropdown', 'acc_coa_id', 'accForthHead', 'accForthHeadWithAmount', 'date', 'fromDate', 'toDate', 'ledger_name'));
            return $pdf->download('note-ledger.pdf');
        }

        return view('accounts::reports.note-ledger.index', compact('accLevelThreeDropdown', 'acc_coa_id', 'accForthHead', 'accForthHeadWithAmount', 'date', 'fromDate', 'toDate', 'ledger_name'));
    }

    // calculate current year profit and loss
    public function currentYearProfitLoss()
    {
        $fiscalYear = FinancialYear::where('status', true)->where('is_close', false)->first();
        $start_date = $fiscalYear->start_date;
        $end_date = $fiscalYear->end_date;

        //calculation for income
        $level_two_incomes = AccCoa::where('parent_id', 3)->where('is_active', true)->get();
        $level_three_incomes = $this->getCoa(3, 3);
        $level_four_incomes = $this->getCoa(3, 4);

        // $stockValuation = 0 - $this->stockValuation();
        $stockValuation = 0;
        $add_one_array_data_to_level_two = [
            "id" => 100000000000000000000000000,
            "uuid" => "af684dd7-1c7c-4013-8c8a-4a1e1694fe27",
            "account_code" => "0001",
            "account_name" => "Stock Valuation",
            "head_level" => 2,
            "parent_id" => 3,
            "acc_type_id" => 3,
            "is_cash_nature" => 0,
            "is_bank_nature" => 0,
            "is_budget" => 0,
            "is_depreciation" => 0,
            "depreciation_rate" => null,
            "is_subtype" => 0,
            "subtype_id" => null,
            "is_stock" => 0,
            "is_fixed_asset_schedule" => 0,
            "note_no" => null,
            "asset_code" => null,
            "dep_code" => null,
            "is_active" => 1,
            "created_by" => 39,
            "updated_by" => 39,
            "deleted_at" => null,
            "balance" => $stockValuation,
        ];

        $add_one_array_data_to_level_two = (object) $add_one_array_data_to_level_two;
        $level_two_incomes->push($add_one_array_data_to_level_two);

        foreach ($level_four_incomes as $income) {
            $balance = $this->getPeriodicClosingBalance($start_date, $end_date, $income->id);
            $income->setAttribute('balance', $balance);
        }

        $incomeBalance = 0;

        foreach ($level_two_incomes as $income2) {
            $level2IncomeBalance = 0;

            foreach ($level_three_incomes as $income3) {

                if ($income3->parent_id == $income2->id) {
                    $levelThreeBalance = $level_four_incomes->where('parent_id', $income3->id)->sum('balance');
                    $income3->setAttribute('balance', $levelThreeBalance);

                    if ($income2->parent_id == 3) {
                        $level2IncomeBalance += $levelThreeBalance;
                        $income2->setAttribute('balance', $level2IncomeBalance);
                    }
                }
            }

            $incomeBalance += $level2IncomeBalance;
        }

        // calculation for Expencess
        $level_two_expences = AccCoa::where('parent_id', 2)->where('is_active', true)->get();
        $level_three_expences = $this->getCoa(2, 3);
        $level_four_expences = $this->getCoa(2, 4);

        foreach ($level_four_expences as $expence) {

            $balance = $this->getClosingBalance($start_date, $end_date, $expence->id);

            $expence->setAttribute('balance', $balance);
        }

        $expenceBalance = 0;

        foreach ($level_two_expences as $expence2) {
            $level2IncomeBalance = 0;

            foreach ($level_three_expences as $expence3) {

                if ($expence3->parent_id == $expence2->id) {
                    $levelThreeBalance = $level_four_expences->where('parent_id', $expence3->id)->sum('balance');
                    $expence3->setAttribute('balance', $levelThreeBalance);

                    if ($expence2->parent_id == 2) {
                        $level2IncomeBalance += $levelThreeBalance;
                        $expence2->setAttribute('balance', $level2IncomeBalance);
                    }
                }
            }

            $expenceBalance += $level2IncomeBalance;
        }
        $netLoss = $expenceBalance - ($incomeBalance - $stockValuation);
        $netProfit = ($incomeBalance + $stockValuation) - $expenceBalance;
        $incomeBalance = ($incomeBalance + $stockValuation);
        $totalExpenceBalance = $netProfit > 0 ? $netProfit + $expenceBalance : $expenceBalance;

        if ($netLoss >= 0) {
            return $netLoss;
        } else if ($netProfit >= 0) {
            if (($netProfit > 0 ? $netProfit + $expenceBalance : $expenceBalance) > ($netLoss > 0 ? $netLoss + $incomeBalance : $incomeBalance)) {
                $netProfit = $totalExpenceBalance + $incomeBalance;
            }
            return $netProfit;
        } else {
            return ($netLoss > 0 ? $netLoss + $incomeBalance : $incomeBalance);
        }
    }

    // showVoucher
    public function showVoucher($voucher_no)
    {
        $data = AccVoucher::where('voucher_no', $voucher_no)->first();
        return response()->view('accounts::reports.voucher.show', compact('data'));
    }
}
