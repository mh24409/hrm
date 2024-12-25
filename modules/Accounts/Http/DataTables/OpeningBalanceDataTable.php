<?php

namespace Modules\Accounts\Http\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Accounts\Entities\AccOpeningBalance;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class OpeningBalanceDataTable extends DataTable
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
            ->addColumn('financial_year', function ($row) {
                return $row->financial_year ? $row->financial_year->financial_year : '';
            })
            ->addColumn('account_name', function ($row) {
                return $row->acc_coa->account_name . ' -(' . $row->subCode?->name . ')';
            })

            ->filterColumn('account_name', function ($query, $keyword) {
                $query->whereHas('subCode', function ($query) use ($keyword) {
                    $query->where('name', 'like', "%{$keyword}%");
                });
            })

            ->addColumn('subtype', function ($row) {
                return $row->subtype ? $row->subtype->subtype_name : '';
            })
            ->filterColumn('subtype', function ($query, $keyword) {
                $query->whereHas('subtype', function ($query) use ($keyword) {
                    $query->where('subtype_name', 'like', "%{$keyword}%");
                });
            })

            ->editColumn('debit', function ($row) {
                return bt_number_format($row->debit);
            })
            ->editColumn('credit', function ($row) {
                return bt_number_format($row->credit);
            })

            ->addColumn('action', function ($row) {

                $button = '';

                if (auth()->user()->can('update_opening_balance')) {
                    $button .= '<a href="' . route('opening-balances.edit', $row->uuid) . '" class="btn btn-info-soft btn-sm me-1"  data-placement="right" title="Update"><i class="fas fa-edit"></i></a>';
                }
                if (auth()->user()->can('delete_opening_balance')) {
                    $button .= '<a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm" data-bs-toggle="tooltip" title="Delete" data-route="' . route('opening-balances.destroy', $row->uuid) . '" data-csrf="' . csrf_token() . '"><i class="fas fa-trash-alt"></i></a>';
                }
                return $button;
            })

            ->rawColumns(['financial_year', 'account_name', 'subtype', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query(AccOpeningBalance $model): QueryBuilder
    {

        return $model->newQuery()->with('subCode')->orderBy('id', orderByData($this->request->get('order')));


    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('opening-balance-table')
            ->responsive(true)
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->language([
                //change preloader icon
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
                ->orderable(false),

            Column::make('financial_year')
                ->title(localize('year'))
                ->searchable(true),

            Column::make('open_date')
                ->title(localize('open_date')),

            Column::make('account_name')
                ->title(localize('account_name')),

            Column::make('subtype')
                ->addClass('text-end')
                ->title(localize('subtype')),

            Column::make('debit')
                ->addClass('text-end')
                ->title(localize('debit') . ' (' . currency() . ')'),

            Column::make('credit')
                ->addClass('text-end')
                ->title(localize('credit') . ' (' . currency() . ')'),

            Column::make('action')
                ->title(localize('action'))->addClass('column-sl')->orderable(false)
                ->searchable(false)
                ->printable(false)
                ->exportable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Purchase_' . date('YmdHis');
    }
}
