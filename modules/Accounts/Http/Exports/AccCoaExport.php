<?php

namespace Modules\Accounts\Http\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class AccCoaExport implements FromCollection, WithHeadings, WithStrictNullComparison, WithColumnWidths
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = DB::select("SELECT a.account_code as a_code ,a.account_name as a_name ,b.account_code as b_code,b.account_name as b_name ,c.account_code as c_code, c.account_name as c_name ,d.account_code as d_code ,d.account_name as d_name
        from (
            SELECT id,account_code,account_name ,parent_id, is_active, deleted_at
            FROM acc_coas
            WHERE head_level = 1
        )a left join (
            SELECT id,account_code,account_name ,parent_id, is_active, deleted_at
            FROM acc_coas
            WHERE head_level = 2
        )b on a.id = b.parent_id
        left join (
            SELECT id,account_code,account_name ,parent_id, is_active, deleted_at
            FROM acc_coas
            WHERE head_level = 3
        )c on b.id = c.parent_id
        left join (
            SELECT id,account_code,account_name ,parent_id, is_active, deleted_at
            FROM acc_coas
            WHERE head_level = 4
        )d on c.id = d.parent_id
        where
          a.is_active = 1 and
          b.is_active = 1 and
          c.is_active = 1 and
          d.is_active = 1 and
          a.deleted_at is null and
          b.deleted_at is null and
          c.deleted_at is null and
          d.deleted_at is null
        ");
        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Account Code (Level 1)',
            'Account Name (Level 1)',
            'Account Code (Level 2)',
            'Account Name (Level 2)',
            'Account Code (Level 3)',
            'Account Name (Level 3)',
            'Account Code (Level 4)',
            'Account Name (Level 4)',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22,
            'B' => 22,
            'C' => 22,
            'D' => 22,
            'E' => 22,
            'F' => 22,
            'G' => 22,
            'H' => 22,
        ];
    }
}
