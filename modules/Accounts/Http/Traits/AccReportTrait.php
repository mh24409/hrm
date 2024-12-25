<?php

namespace Modules\Accounts\Http\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\FinancialYear;
use Modules\Accounts\Entities\AccTransaction;
use Modules\Accounts\Entities\AccOpeningBalance;

trait AccReportTrait
{

    //get all coa
    public function getCoa($acc_type_id, $head_level)
    {
        return AccCoa::where('acc_type_id', $acc_type_id)->where('head_level', $head_level)->where('is_active', true)->get();
    }

    //Get Debit Balance
    public function getDebitBalance($from_date, $to_date, $coa_id, $sub_code = null)
    {
        //all sum of debit balance from transaction table
        $fromDate = Carbon::parse($from_date)->startOfDay();
        $todate   = Carbon::parse($to_date)->endOfDay();

        $debitTransaction = AccTransaction::where('acc_coa_id', $coa_id)
            ->whereBetween('voucher_date', [$fromDate, $todate]);

        if ((!empty($sub_code)) || ($sub_code != null)) {
            return $debitTransaction->where('acc_subcode_id', $sub_code)->sum('debit');
        }

        return $debitTransaction->sum('debit');
    }

    //Get Credit Balance
    public function getCreditBalance($from_date, $to_date, $coa_id, $sub_code = null)
    {
        //all sum of Credit balance from transaction table

        $fromDate = Carbon::parse($from_date)->startOfDay();
        $todate   = Carbon::parse($to_date)->endOfDay();

        $creditTransaction = AccTransaction::where('acc_coa_id', $coa_id)
            ->whereBetween('voucher_date', [$fromDate, $todate]);

        if ((!empty($sub_code)) || ($sub_code != null)) {
            return $creditTransaction->where('acc_subcode_id', $sub_code)->sum('credit');
        }

        return $creditTransaction->sum('credit');
    }

    //Get Closing Balance
    public function getClosingBalance($from_date, $to_date, $coa_id, $sub_code = null)
    {

        $openingBalnce = $this->openingBalance($from_date, $to_date, $coa_id, $sub_code);
        $debitBalance  = $this->getDebitBalance($from_date, $to_date, $coa_id, $sub_code);
        $creditBalance = $this->getCreditBalance($from_date, $to_date, $coa_id, $sub_code);
        $closingBlance = 0;

        $coaDetail = Cache::remember($coa_id, 3600, function () use ($coa_id) {
            return AccCoa::findOrFail($coa_id);
        });

        if (($coaDetail->acc_type_id == 1) || ($coaDetail->acc_type_id == 2)) {
            $closingBlance = (float) $openingBalnce + (float) $debitBalance - (float) $creditBalance;
        }

        if (($coaDetail->acc_type_id == 3) || ($coaDetail->acc_type_id == 4) || ($coaDetail->acc_type_id == 5)) {
            $closingBlance = (float) $openingBalnce + (float) $creditBalance - (float) $debitBalance;
        }

        return $closingBlance;
    }

    //Get Periodic Closing Balance
    public function getPeriodicClosingBalance($from_date, $to_date, $coa_id, $sub_code = null)
    {

        $debitBalance  = $this->getDebitBalance($from_date, $to_date, $coa_id, $sub_code);
        $creditBalance = $this->getCreditBalance($from_date, $to_date, $coa_id, $sub_code);
        $closingBlance = 0;

        $coaDetail = Cache::remember($coa_id, 3600, function () use ($coa_id) {
            return AccCoa::findOrFail($coa_id);
        });

        if (($coaDetail->acc_type_id == 1) || ($coaDetail->acc_type_id == 2)) {
            $closingBlance = (float) $debitBalance - (float) $creditBalance;
        }

        if (($coaDetail->acc_type_id == 3) || ($coaDetail->acc_type_id == 4) || ($coaDetail->acc_type_id == 5)) {
            $closingBlance = (float) $creditBalance - (float) $debitBalance;
        }

        return $closingBlance;
    }

    //Opening Balance
    private function openingBalance($from_date, $to_date, $coa_id, $sub_code = null)
    {

        $fromDate        = date('Y', strtotime($from_date));
        $getFinacialYear = Cache::remember("financial_year" . $fromDate, 3600, function () use ($fromDate) {
            return FinancialYear::where('financial_year', $fromDate)->first();
        });

        if ($getFinacialYear == null) {
            $openingBalnce = 0;
            return $openingBalnce;
        }

        // new code for get financ year & Previous Year
        $getyearDetails = Cache::remember("financial_year" . Carbon::parse($from_date)->format('Y-m-d'), 3600, function () use ($from_date) {
            return FinancialYear::whereDate('start_date', '<=', $from_date)
                ->whereDate('end_date', '>=', $from_date)
                ->first();
        });

        if ($getyearDetails == null) {
            $openingBalnce = 0;
            return $openingBalnce;
        }

        $previousFinanceYear = Cache::remember("previousFinanceYear" . Carbon::parse($getyearDetails->start_date)->format('Y-m-d'), 3600, function () use ($getyearDetails) {
            return FinancialYear::whereDate('end_date', '<=', $getyearDetails->start_date)->orderByDesc('end_date')->first();
        });

        // new code for get financ year & Previous Year

        if ($previousFinanceYear == null) {
            $openingBalnce = 0;
            return $openingBalnce;
        }

        if ((!empty($sub_code)) || ($sub_code != null)) {
            $getOpeninBalance = Cache::remember("gt_op_bl_" . $previousFinanceYear->id . "_" . $coa_id . "_" . $sub_code, 60, function () use ($previousFinanceYear, $coa_id, $sub_code) {
                return AccOpeningBalance::where('financial_year_id', $previousFinanceYear->id)->where('acc_subcode_id', $sub_code)->where('acc_coa_id', $coa_id)->get();
            });
        } else {
            $getOpeninBalance = Cache::remember("gt_op_bl_" . $previousFinanceYear->id . "_" . $coa_id, 60, function () use ($previousFinanceYear, $coa_id) {
                return AccOpeningBalance::where('financial_year_id', $previousFinanceYear->id)->where('acc_coa_id', $coa_id)->get();
            });
        }

        $balnceResult = [];
        $coaDetail    = Cache::remember($coa_id, 3600, function () use ($coa_id) {
            return AccCoa::findOrFail($coa_id);
        });

        if (($coaDetail->acc_type_id == 1) || ($coaDetail->acc_type_id == 2)) {

            foreach ($getOpeninBalance as $key => $openingvalue) {
                $dabit  = $openingvalue->debit;
                $cradit = $openingvalue->credit;

                (float) $geresult = (float) $dabit - (float) $cradit;

                array_push($balnceResult, $geresult);
            }
        }

        if (($coaDetail->acc_type_id == 3) || ($coaDetail->acc_type_id == 4) || ($coaDetail->acc_type_id == 5)) {

            foreach ($getOpeninBalance as $key => $openingvalue) {
                $dabit  = $openingvalue->debit;
                $cradit = $openingvalue->credit;

                (float) $geresult = (float) $cradit - (float) $dabit;

                array_push($balnceResult, $geresult);
            }
        }

        $openingBalnce = array_sum($balnceResult);
        return $openingBalnce;
    }
}
