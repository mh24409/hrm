<?php

namespace Modules\HumanResource\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\ProcurementPurchaseOrder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProcurementPurchaseOrderDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->editColumn('purchase_order', function ($row) {
                $formattedId = 'PO-' . sprintf('%05s', $row->id);
                return $formattedId;
            })

            ->addColumn('action', function ($row) {
                $button = '';
                if (auth()->user()->can('update_purchase_order')) {
                    $button .= '<a href="' . route('purchase.edit', $row->id) . '" class="btn btn-success-soft btn-sm me-1 mt-sm-1 mt-lg-0" data-bs-toggle="tooltip" data-bs-placement="left" title="Update"><i class="fas fa-edit"></i></a>';
                }

                if (auth()->user()->can('delete_purchase_order')) {
                    if (!$row->goods_received_id) {
                        $button .= '<a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm mt-sm-1 mt-lg-0" data-bs-toggle="tooltip" title="Delete" data-route="' . route('purchase.destroy', $row->id) . '" data-csrf="' . csrf_token() . '"><i class="fas fa-trash-alt"></i></a>';
                    }
                }

                if (auth()->user()->can('read_purchase_order')) {
                    $button .= '<a href="' . asset('storage/' . $row->pdf_link) . '" class="btn btn-success-soft btn-sm ms-1 mt-sm-1 mt-lg-0" data-bs-toggle="tooltip" title="PDF" download ><i class="fa-solid fa-file-arrow-down"></i></a>';
                }

                return $button;
            })

            ->rawColumns(['purchase_order', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \modules\HumanResource\Entities\ProcurementPurchaseOrder $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ProcurementPurchaseOrder $model)
    {
        return $model->newQuery()->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('bid-table')
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle table-sm')
            ->columns($this->getColumns())
            ->serverSide(false)
            ->minifiedAjax()
            ->language([
                'processing' => '<div class="lds-spinner">
                <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
            ])
            ->responsive(true)
            ->selectStyleSingle()
            ->lengthMenu([[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']])
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            ->buttons([
                Button::make('csv')
                    ->className('btn btn-secondary buttons-csv buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5]]),
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [

            Column::make('DT_RowIndex')
                ->title(localize('sl'))
                ->addClass('text-center column-sl')
                ->width(50)
                ->searchable(false)
                ->orderable(false),

            Column::make('purchase_order')
                ->title(localize('purchase_order')),

            Column::make('vendor_name')
                ->title(localize('vendor_name')),

            Column::make('location')
                ->title(localize('location')),

            Column::make('created_date')
                ->title(localize('date')),

            Column::make('total')
                ->title(localize('total')),

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
        return 'PurchaseOrderList_' . date('YmdHis');
    }
}
