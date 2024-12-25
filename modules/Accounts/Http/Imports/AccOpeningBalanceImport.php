<?php

namespace Modules\Accounts\Http\Imports;


use Carbon\Carbon;
use Modules\Accounts\Entities\AccCoa;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Modules\Accounts\Entities\FinancialYear;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\Accounts\Entities\AccOpeningBalance;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class AccOpeningBalanceImport implements ToModel, WithHeadingRow,SkipsEmptyRows, WithStrictNullComparison
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $date = intval($row['open_date']);
        $open_date = Date::excelToDateTimeObject($date);
        $open_date = Carbon::parse($open_date)->format('Y-m-d');
        $financial_year = FinancialYear::where('financial_year', $row['financial_year'])->first()->id;
        $account_code = AccCoa::where('account_code', $row['account_code'])->first()->id ?? null;
        $acc_subtype =  AccCoa::where('account_code', $row['account_code'])->with('subtype')->first();
        if ($acc_subtype->subtype) {
            $acc_subtype_id = $acc_subtype->subtype->id;
        } else {
            $acc_subtype_id = null;
        }
        $created_by = auth()->user()->id ?? null;

        return new AccOpeningBalance([
            'financial_year_id'  => $financial_year,
            'acc_coa_id'	     => $account_code,
            'acc_subtype_id'     => $acc_subtype_id,
            'debit'	             => $row['debit'],
            'credit'             => $row['credit'],
            'open_date'	         => $open_date ,
            'created_by'	     => $created_by,
        ]);
    }

    // heading row must be 1
    public function headingRow(): int
    {
        return 1;
    }


}
