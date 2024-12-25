<?php

namespace Modules\Accounts\Http\Traits;

use Carbon\Carbon;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\AccSubcode;
use Modules\Accounts\Entities\AccVoucher;
use Modules\Accounts\Entities\FinancialYear;

trait AccVoucherTrait
{

  /**
   * $debit = Debit Account
   * $credit = Credit Account
   * $amount = Total Amount
   * $type = Voucher Type, 1 for debit, 2 for credit, 3 for contra, 4 for journal
   * $reference = Reference form voucher create (e.g. invoice no)
   * $customerId = Customer Id
   * $remarks = Reference form voucher create
   * $date = Voucher Date
   * $ledger_comment = Ledger Comment
   * $voucher_date = Voucher Date
   */

  public function createVoucher(int $debit, int $credit, float $amount, int $type, string $reference, int $customerId = null, string $remarks = null, string $date = null, string $ledger_comment = null, $voucher_date = null): string
  {

    $financial_year = FinancialYear::where('status', true)->where('is_close', false)->first();

    $voucher = new AccVoucher();

    $voucher->financial_year_id = $financial_year->id;
    $voucher->voucher_date      = $date ?? Carbon::today()->toDateString();
    $voucher->voucher_type      = $type;
    $voucher->acc_coa_id        = $debit;
    $voucher->narration         = $remarks ?? null;
    $voucher->reference_no      = $reference;


    $debit_account = AccCoa::where('id', $debit)->whereNotNull('subtype_id')->first();
    $credit_account = AccCoa::where('id', $credit)->whereNotNull('subtype_id')->first();
    if ($debit_account) {
      $subcode = AccSubcode::where('acc_subtype_id', $debit_account->subtype_id)->where('reference_no', $customerId)->first();
    } else if ($credit_account) {
      $subcode = AccSubcode::where('acc_subtype_id', $credit_account->subtype_id)->where('reference_no', $customerId)->first();
    }


    $voucher->acc_subtype_id =  $debit_account ? $debit_account->subtype_id ?? null : $credit_account->subtype_id ?? null;

    $voucher->acc_subcode_id = $subcode->id ?? null;

    $voucher->ledger_comment = $ledger_comment;
    if ($type == 2) {
      $voucher->credit = $amount;
      $voucher->reverse_code = $credit;
    } else {
      $voucher->debit = $amount;
      $voucher->reverse_code = $credit;
    }

    $voucher->created_at = $voucher_date ?? Carbon::now();
    $latestVoucher  = AccVoucher::orderBy('id', 'DESC')->first();
    $voucher->voucher_no = $this->getVoucherString($type) . '-' . str_pad(($latestVoucher ? $latestVoucher->id : 0) + 1, 6, "0", STR_PAD_LEFT);
    $voucher->save();

    return $voucher->voucher_no;
  }

  //Get Voucher String
  private function getVoucherString(int $voucher_type): string
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
