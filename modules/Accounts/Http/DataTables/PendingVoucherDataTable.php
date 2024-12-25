<?php

namespace Modules\Accounts\Http\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Accounts\Entities\AccVoucher;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PendingVoucherDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" name="voucher_checkbox[]" class="voucher_checkbox" value="' . $row->id . '">';
            })
            ->addColumn('account_name', function ($row) {
                $subCode = '';
                if ($row->subcode) {
                    $subCode .= ' - (' . ucwords($row->subcode?->name) . ')';
                }
                return ucwords($row->acc_coa?->account_name . $subCode);
            })

            ->filterColumn('account_name', function ($row, $keyword) {
                $row->whereHas('acc_coa', function ($row) use ($keyword) {
                    $row->where('account_name', 'like', "%{$keyword}%");
                });
            })

            ->addColumn('sub_type', function ($row) {
                return $row->subtype ? $row->subtype->subtype_name : '';
            })
            ->addColumn('reversed_account', function ($row) {
                return $row->acc_coa_reverse ? $row->acc_coa_reverse->account_name : '';
            })
            ->filterColumn('reversed_account', function ($row, $keyword) {
                $row->whereHas('acc_coa_reverse', function ($row) use ($keyword) {
                    $row->where('account_name', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('debit', function ($row) {
                return $row->debit ?? 0;
            })
            ->editColumn('credit', function ($row) {
                return $row->credit ?? 0;
            })

            ->rawColumns(['checkbox', 'account_name', 'sub_type', 'reversed_account']);

    }

    /**
     * Get query source of dataTable.
     */
    public function query(AccVoucher $model): QueryBuilder
    {
        $voucherDate = $this->request->get('voucher_date');
        $account_name = $this->request->get('account_name');
        $subtype_name = $this->request->get('subtype_name');

        return $model->newQuery()
            ->with(['acc_coa', 'subtype', 'acc_coa_reverse'])
            ->whereNull('approved_by')->whereNull('approved_at')

            ->when($account_name, function ($query) use ($account_name) {
                return $query->whereHas('acc_coa', function ($query) use ($account_name) {
                    $query->where('id', $account_name);
                });
            })

            ->when($subtype_name, function ($query) use ($subtype_name) {
                return $query->whereHas('subtype', function ($query) use ($subtype_name) {
                    $query->where('id', $subtype_name);
                });
            })

            ->when($voucherDate, function ($query) use ($voucherDate) {
                $string = explode('-', $voucherDate);
                if ($voucherDate) {
                    $fromDate = date('Y-m-d', strtotime($string[0]));
                    $toDate = date('Y-m-d', strtotime($string[1]));
                } else {
                    $fromDate = '';
                    $toDate = '';
                }
                return $query->whereBetween('voucher_date', [$fromDate, $toDate]);
            })
            ->orderBy('voucher_no', orderByData($this->request->get('order')));
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('voucher-pending-table')
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle')
            ->lengthMenu([[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']])
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->language([
                'processing' => '<div class="lds-spinner">
                <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
            ])
            ->selectStyleSingle()
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            ->buttons([]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('DT_RowIndex')
                ->title(localize('sl'))
                ->addClass('text-center column-sl')
                ->searchable(false)
                ->orderable(false)
                ->width(10),

            Column::computed('checkbox')
                ->title('<input type="checkbox" id="check_all" onclick="selectAll()"> All')
                ->orderable(false)
                ->exportable(false)
                ->printable(false)
                ->width(40),

            Column::make('voucher_no')
                ->title(localize('voucher_no'))
                ->searchable(true),
            Column::make('voucher_date')
                ->title(localize('date'))
                ->searchable(true),
            Column::make('account_name')
                ->title(localize('account_name'))
                ->searchable(true),
            Column::make('ledger_comment')
                ->title(localize('ledger_comment'))
                ->searchable(true),
            Column::make('sub_type')
                ->title(localize('sub_type'))
                ->searchable(true),
            Column::make('reversed_account')
                ->title(localize('reversed_account'))
                ->searchable(true),
            Column::make('debit')
                ->title(localize('debit'))
                ->searchable(true),
            Column::make('credit')
                ->title(localize('credit'))
                ->searchable(true),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Pending-Voucher-' . date('YmdHis');
    }

}
