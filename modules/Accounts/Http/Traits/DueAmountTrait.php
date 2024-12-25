<?php

namespace Modules\Accounts\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Request;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\AccOpeningBalance;
use Modules\Accounts\Entities\AccPredefineAccount;
use Modules\Accounts\Entities\AccSubcode;
use Modules\Accounts\Entities\AccTransaction;
use Modules\Accounts\Entities\FinancialYear;

trait DueAmountTrait {

    /**
     * Generate subledger report.
     * @return Renderable
     */
    public function totalDueFromSubLedger($request)
    {
        $getBalanceOpening           = "";
        $getTransactionList          = [];
        $tablefooter['totalBalance'] = 0;
        $tablefooter['totalDebit']   = 0;
        $tablefooter['totalCradit']  = 0;

        $fromDate = current_date();
        $toDate   = current_date();

        $request['from_date'] = $fromDate;
        $request['to_date']   = $toDate;

        $getBalanceOpening = $this->subLedgerOpeningBalance($request);
        $getTransactionList = $this->subLedgerTransactionDetail($request, $getBalanceOpening);
        
        $tablefooter['totalDebit']   = $getTransactionList->pluck('debit')->sum();
        $tablefooter['totalCradit']  = $getTransactionList->pluck('credit')->sum();
        $tablefooter['totalBalance'] = count($getTransactionList) > 0 ? $getTransactionList[count($getTransactionList) - 1]->balance : 0;
     
        $subLedgerNameWiseData = [];
     
        $subLedgerNameWiseData = $this->subCodeIdWiseTransactionDetail($request);
        $tablefooter['totalDebit']   = $subLedgerNameWiseData->pluck('payable')->sum();
        $tablefooter['totalCradit']  = $subLedgerNameWiseData->pluck('receivable')->sum();
        $getBalanceOpening = $subLedgerNameWiseData->pluck('ach_balance')->sum();

        if( $request['subtype_id'] == 1 ||  $request['subtype_id'] == 2){
            $tablefooter['totalBalance'] = (number_format($tablefooter['totalCradit'],2, '.', '') - number_format($tablefooter['totalDebit'],2, '.', ''));
        }
        if( $request['subtype_id'] == 3){
            $tablefooter['totalBalance'] = (number_format($tablefooter['totalDebit'],2, '.', '') - number_format($tablefooter['totalCradit'],2, '.', ''));
        }

        return $tablefooter['totalBalance'];
    }

    //Get Any Type subLedger Opening Balance
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
            //get acc opening balance detail against coa id and previous year id

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
            $coaDetail    = AccCoa::findOrFail($request->acc_coa_id);

            if (($coaDetail->acc_type_id == 1) || ($coaDetail->acc_type_id == 2)) {

                foreach ($getOpeninBalance as $key => $openingvalue) {
                    $dabit  = $openingvalue->debit;
                    $cradit = $openingvalue->credit;

                    $geresult = number_format($dabit,2, '.', '') - number_format($cradit,2, '.', '');

                    array_push($balnceResult, $geresult);
                }

            }

            if (($coaDetail->acc_type_id == 3) || ($coaDetail->acc_type_id == 4) || ($coaDetail->acc_type_id == 5)) {

                foreach ($getOpeninBalance as $key => $openingvalue) {
                    $dabit  = $openingvalue->debit;
                    $cradit = $openingvalue->credit;

                    $geresult = number_format($cradit,2, '.', '') - number_format($dabit,2, '.', '');

                    array_push($balnceResult, $geresult);

                }

            }


            $openingBalnce = array_sum($balnceResult);
            return $openingBalnce;

        }

    }

    //Sub Ledger Transaction Detail
    public function subLedgerTransactionDetail($request, $getBalanceOpening)
    {
        
        $tanjectionVouture = AccTransaction::where('acc_subtype_id', $request->subtype_id)
            ->when($request->acc_subcode_id, function ($query) use ($request) {

                if ($request->acc_subcode_id != 0 ) {
                    return $query->where('acc_subcode_id', $request->acc_subcode_id);
                }

            })
            ->with('accReverseCode')
            ->whereBetween('voucher_date', [$request->from_date, $request->to_date])
            ->get();


        $dummyBal = $getBalanceOpening;

        foreach ($tanjectionVouture as $key => $tanjectionVoutureValue) {

            $coaDetail = AccCoa::findOrFail($tanjectionVoutureValue->acc_coa_id);

            if (($coaDetail->acc_type_id == 1) || ($coaDetail->acc_type_id == 2)) {
                $firstResult =  number_format($tanjectionVoutureValue->debit,2, '.', '') - number_format($tanjectionVoutureValue->credit,2, '.', '');
                $dummyBal    = number_format($dummyBal,2, '.', '') + number_format($firstResult,2, '.', '');

                $tanjectionVouture[$key]['balance'] = number_format($dummyBal,2, '.', '');

            }

            if (($coaDetail->acc_type_id == 3) || ($coaDetail->acc_type_id == 4) || ($coaDetail->acc_type_id == 5)) {
                $firstResult                        = number_format($tanjectionVoutureValue->credit,2, '.', '') - number_format($tanjectionVoutureValue->debit,2, '.', '');
                $dummyBal                           = number_format($dummyBal,2, '.', '') + number_format($firstResult,2, '.', '');
                $tanjectionVouture[$key]['balance'] = number_format($dummyBal,2, '.', '');

            }
        }

        return $tanjectionVouture;

    }
    //acc subcode id wise TransactionDetail
    public function subCodeIdWiseTransactionDetail($request)
    {
        //acc_subcodes
        $acc_subcodes = AccSubcode::where('acc_subtype_id', $request->subtype_id)->get();


        foreach($acc_subcodes as $acc_subcode){
            $request->acc_subcode_id = $acc_subcode->id;
            $getBalanceOpening = $this->subLedgerOpeningBalance($request);


            $subLedgerTransactionDetail = $this->subLedgerTransactionDetail($request, $getBalanceOpening);
            $lastBalance =  count($subLedgerTransactionDetail) > 0 ? $subLedgerTransactionDetail[count($subLedgerTransactionDetail) - 1]->balance : 0;

            $debit = $subLedgerTransactionDetail->pluck('debit')->sum();
            $credit = $subLedgerTransactionDetail->pluck('credit')->sum();
            $balance = 0;

            if($request->subtype_id == 1 || $request->subtype_id == 2){
                $balance = ($debit- $credit);
                    $acc_subcode->setAttribute('payable', 0);
                    $acc_subcode->setAttribute('receivable', $getBalanceOpening + $balance);
                    $acc_subcode->setAttribute('last_balance', $lastBalance);
                    $acc_subcode->setAttribute('ach_balance', $getBalanceOpening);
            }

            if($request->subtype_id == 3){
                $balance = (  $credit - $debit);
                $acc_subcode->setAttribute('payable', $getBalanceOpening + $balance);
                $acc_subcode->setAttribute('receivable', 0);
                $acc_subcode->setAttribute('last_balance', $lastBalance);
                $acc_subcode->setAttribute('ach_balance', $getBalanceOpening);
            }

        }

        return $acc_subcodes;

    }

    //Supplier total due
    public function supplierTotalDue($supplier_id){
        $predefine_account = AccPredefineAccount::first();
        $subtype_id = 3;
        $acc_coa_id = $predefine_account->supplier_code;
        $accSubCode_id = AccSubcode::where('reference_no', $supplier_id)->where('acc_subtype_id', $subtype_id)->first()->id ?? null;

        $getBalanceOpening           = "";
        $getTransactionList          = [];
        $tablefooter['totalBalance'] = 0;

        $getBalanceOpening = $this->subLedgerOpeningBalanceForSupplier($subtype_id, $acc_coa_id, $accSubCode_id);
        $getTransactionList = $this->subLedgerTransactionDetailForSupplier($subtype_id, $accSubCode_id, $getBalanceOpening);

        $tablefooter['totalDebit']   = $getTransactionList->pluck('debit')->sum();
        $tablefooter['totalCradit']  = $getTransactionList->pluck('credit')->sum();
        $tablefooter['totalBalance'] = count($getTransactionList) > 0 ? $getTransactionList[count($getTransactionList) - 1]->balance : 0;
        
        return $tablefooter['totalBalance'] ?? 0;
    }

    public function subLedgerOpeningBalanceForSupplier($subtype_id, $acc_coa_id, $accSubCode_id)
    {
        if ((empty($subtype_id)) || (empty($acc_coa_id)) || (empty($accSubCode_id)) || ($subtype_id == null) || ($acc_coa_id == null) || ($accSubCode_id == null)) {

            $openingBalnce = 0;
            return $openingBalnce;
        } else {

            // new code for get financial year & Previous Year
            $financial_years = FinancialYear::get();
            $getyearDetails = $financial_years->filter(function ($query) {
                return $query->whereDate('start_date', '<=', current_date())
                    ->whereDate('end_date', '>=', current_date())
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
            //get acc opening balance detail against coa id and previous year id

            $getOpeninBalance = AccOpeningBalance::where('financial_year_id', $previousFinanceYear->id)
                ->where('acc_coa_id', $acc_coa_id)
                ->where('acc_subtype_id', $subtype_id)
                ->when($accSubCode_id, function ($query) use ($accSubCode_id) {

                    if ($accSubCode_id != 0) {
                        return $query->where('acc_subcode_id', $accSubCode_id);
                    }

                })
                ->where('acc_subcode_id', $accSubCode_id)
                ->get();

            $balnceResult = [];
            $coaDetail    = AccCoa::findOrFail($acc_coa_id);

            if (($coaDetail->acc_type_id == 1) || ($coaDetail->acc_type_id == 2)) {

                foreach ($getOpeninBalance as $key => $openingvalue) {
                    $dabit  = $openingvalue->debit;
                    $cradit = $openingvalue->credit;

                    $geresult = number_format($dabit,2, '.', '') - number_format($cradit,2, '.', '');

                    array_push($balnceResult, $geresult);
                }

            }

            if (($coaDetail->acc_type_id == 3) || ($coaDetail->acc_type_id == 4) || ($coaDetail->acc_type_id == 5)) {

                foreach ($getOpeninBalance as $key => $openingvalue) {
                    $dabit  = $openingvalue->debit;
                    $cradit = $openingvalue->credit;

                    $geresult = number_format($cradit,2, '.', '') - number_format($dabit,2, '.', '');

                    array_push($balnceResult, $geresult);

                }

            }


            $openingBalnce = array_sum($balnceResult);
            return $openingBalnce;

        }
    }

    public function subLedgerTransactionDetailForSupplier($subtype_id, $accSubCode_id, $getBalanceOpening)
    {
        $transactionVoucher = AccTransaction::where('acc_subtype_id', $subtype_id)
            ->when($accSubCode_id, function ($query) use ($accSubCode_id) {

                if ($accSubCode_id != 0 ) {
                    return $query->where('acc_subcode_id', $accSubCode_id);
                }
            })
            ->with('accReverseCode')
            ->get();


        $dummyBal = $getBalanceOpening;

        foreach ($transactionVoucher as $key => $transactionVoucherValue) {

            $coaDetail = AccCoa::findOrFail($transactionVoucherValue->acc_coa_id);

            if (($coaDetail->acc_type_id == 1) || ($coaDetail->acc_type_id == 2)) {
                $firstResult = (double) $transactionVoucherValue->debit - (double) $transactionVoucherValue->credit;
                $dummyBal    = (double) $dummyBal + (double) $firstResult;

                $transactionVoucher[$key]['balance'] = $dummyBal;
            }
            if (($coaDetail->acc_type_id == 3) || ($coaDetail->acc_type_id == 4) || ($coaDetail->acc_type_id == 5)) {
                $firstResult                        = (double) $transactionVoucherValue->credit - (double) $transactionVoucherValue->debit;
                $dummyBal                           = (double) $dummyBal + (double) $firstResult;
                $transactionVoucher[$key]['balance'] = $dummyBal;

            }
        }

        return $transactionVoucher;

    }

}
