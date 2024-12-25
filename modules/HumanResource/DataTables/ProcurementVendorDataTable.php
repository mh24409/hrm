<?php

namespace Modules\HumanResource\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\ProcurementVendor;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProcurementVendorDataTable extends DataTable
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
            ->editColumn('country', function ($row) {
                return $row->country?->country_name ?? 'N/A';
            })
            ->filterColumn('country', function ($query, $keyword) {
                $query->whereHas('country', function ($query) use ($keyword) {
                    $query->where('country_name', 'like', "%{$keyword}%");
                });
            })

            ->addColumn('action', function ($row) {
                $button = '';
                if (auth()->user()->can('update_vendors')) {
                    $button .= '<button onclick="editVendorDetails(' . $row->id . ')" id="editVendorDetails-' . $row->id . '" data-url="' . route('committee.edit', $row->id) . '" class="btn btn-success-soft btn-sm me-1" ><i class="fas fa-edit"></i></button>';
                }

                if (auth()->user()->can('delete_vendors')) {
                    $button .= '<a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm mt-sm-1 mt-lg-0" data-bs-toggle="tooltip" title="Delete" data-route="' . route('vendor.destroy', $row->id) . '" data-csrf="' . csrf_token() . '"><i class="fas fa-trash-alt"></i></a>';
                }

                return $button;
            })

            ->rawColumns(['country', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \modules\HumanResource\Entities\ProcurementVendor $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ProcurementVendor $model)
    {
        return $model->newQuery()
            ->with('country')
            ->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('vendor-table')
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle table-sm')
            ->columns($this->getColumns())
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
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7]]),
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

            Column::make('name')
                ->title(localize('vendor_name'))
                ->orderable(false),

            Column::make('mobile')
                ->title(localize('mobile_number'))
                ->orderable(false),

            Column::make('email')
                ->title(localize('email_address'))
                ->orderable(false),

            Column::make('city')
                ->title(localize('city')),

            Column::make('zip')
                ->title(localize('zip_code')),

            Column::make('country')
                ->title(localize('country'))->orderable(false),

            Column::make('previous_balance')
                ->title(localize('previous_balance')),

            Column::make('action')
                ->title(localize('action'))->addClass('column-sl')
                ->orderable(false)
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
        return 'VendorList_' . date('YmdHis');
    }
}
