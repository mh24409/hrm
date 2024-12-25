<?php

namespace Modules\Accounts\Http\Traits;

use Carbon\Carbon;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\AccSubcode;
use Modules\Accounts\Entities\AccVoucher;
use Modules\Accounts\Entities\FinancialYear;

trait AccVoucherAutoTrait {

    /**
     * $predefine_Account_left  = Account Id
     * $predefine_Account_right = Account Id
     * $is_debit_or_credit = 1 for debit then credit, 2 for credit then debit
     * $amount = Total Amount
     * $type = Voucher Type, 1 for debit, 2 for credit, 3 for contra, 4 for jourlnal
     * $reference = Reference form voucher create (e.g. invoice no)
     * $customerId = Customer Id
     * $remarks = Reference form voucher create
     * $date = Voucher Date for description
     * $ledger_comment = Ledger Comment
     * $voucher_date = Voucher Date
     */

    public function createVoucherAuto(int $predefine_Account_left, int $predefine_Account_right, int $is_debit_or_credit,  float $amount, int $type, string $reference, int $customerId=null, string $remarks = null, string $date = null, string $ledger_comment=null,$voucher_date=null) : void
    {

        $financial_year = FinancialYear::where('status', true)->where('is_close', false)->first();


        $debit  =  $predefine_Account_left;
        $credit =  $predefine_Account_right;
        $voucher = new AccVoucher();

        $voucher->financial_year_id = $financial_year->id;
        $voucher->voucher_date      = $date ?? Carbon::today()->toDateString();
        $voucher->voucher_type      = $type;
        $voucher->acc_coa_id        = $debit;
        $voucher->narration         = $remarks ?? null;
        $voucher->reference_no      = $reference;


        $subtype = AccCoa::
        where('id', $predefine_Account_left)
        ->whereNotNull('subtype_id')
        ->first();

        if($subtype){
            $subcode = AccSubcode::where('acc_subtype_id', $subtype->subtype_id)->where('reference_no', $customerId)->first();
        }else{
            $subcode = null;
        }

        $voucher->acc_subtype_id =  $subtype ? $subtype->subtype_id : null;
        $voucher->acc_subcode_id = $subcode ? $subcode->id : null;
        $voucher->ledger_comment = $ledger_comment;
        if($is_debit_or_credit == 2){
          $voucher->credit = $amount;
          $voucher->reverse_code = $credit;
        }else{
          $voucher->debit = $amount;
          $voucher->reverse_code = $credit;
        }
        $voucher->created_at = $voucher_date ?? Carbon::now();
        $latestVoucher  = AccVoucher::orderBy('id', 'DESC')->first();
        $voucher->voucher_no = $this->getVoucherStringAuto($type) .'-' . str_pad(($latestVoucher ? $latestVoucher->id : 0) + 1, 6, "0", STR_PAD_LEFT);

        $voucher->save();
    }

    //Get Voucher String
    private function getVoucherStringAuto(int $voucher_type) : string
    {
        switch ($voucher_type) {
            case 1:
              return "DV";
            case 2:
              return "CV";
            case 3:
              return "CT";
            default:
              return "JV";
        }
    }



}
