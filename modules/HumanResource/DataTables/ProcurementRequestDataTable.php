<?php

namespace Modules\HumanResource\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\ProcurementRequest;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProcurementRequestDataTable extends DataTable
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
            ->editColumn('employee_id', function ($row) {
                return $row->employee?->full_name ?? 'N/A';
            })
            ->filterColumn('employee_id', function ($query, $keyword) {
                $query->whereHas('employee', function ($query) use ($keyword) {
                    $query->where('first_name', 'like', "%{$keyword}%")
                        ->orWhere('last_name', 'like', "%{$keyword}%")
                        ->orWhere('middle_name', 'like', "%{$keyword}%");
                });
            })

            ->editColumn('department_id', function ($row) {
                return $row->department?->department_name ?? 'N/A';
            })
            ->filterColumn('department_id', function ($query, $keyword) {
                $query->whereHas('department', function ($query) use ($keyword) {
                    $query->where('department_name', 'like', "%{$keyword}%");
                });
            })

            ->editColumn('quote_status', function ($row) {
                $statusBtn = '';
                if ($row->is_quoted) {
                    $statusBtn .= '<p class="btn btn-xs btn-success m-0 pb-2">' . localize("converted") . '</p>';
                } else {
                    $statusBtn .= '<p class="btn btn-xs btn-danger m-0 pb-2">' . localize("not_converted") . '</p>';
                }

                return $statusBtn;
            })

            ->addColumn('action', function ($row) {
                $button = '';
                if (auth()->user()->can('update_request')) {
                    $button .= '<a href="' . route('procurement_request.edit', $row->id) . '" class="btn btn-success-soft btn-sm me-1 mt-sm-1 mt-lg-0" data-bs-toggle="tooltip" data-bs-placement="left" title="Update"><i class="fas fa-edit"></i></a>';
                }

                if (auth()->user()->can('update_request')) {
                    if (!$row->is_approve) {
                        $button .= '<a href="' . route('procurement_request.show', $row->id) . '" class="btn btn-primary-soft btn-sm me-1 mt-sm-1 mt-lg-0" data-bs-toggle="tooltip" data-bs-placement="left" title="Approve"><i class="fa-solid fa-check"></i></a>';
                    }
                }

                if (auth()->user()->can('create_quotation')) {
                    if ($row->is_approve && !$row->is_quoted) {
                        $button .= '<a href="' . route('quotation.create', ['quotation' => $row->id]) . '" class="btn btn-primary-soft btn-sm me-1 mt-sm-1 mt-lg-0" data-bs-toggle="tooltip" data-bs-placement="left" title="Quotation"><i class="fa fa-book"></i></a>';
                    }
                }

                if (auth()->user()->can('delete_request')) {
                    $button .= '<a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm mt-sm-1 mt-lg-0" data-bs-toggle="tooltip" title="Delete" data-route="' . route('procurement_request.destroy', $row->id) . '" data-csrf="' . csrf_token() . '"><i class="fas fa-trash-alt"></i></a>';
                }

                if (auth()->user()->can('read_request')) {
                    $button .= '<a href="' . asset('storage/' . $row->pdf_link) . '" class="btn btn-success-soft btn-sm ms-1 mt-sm-1 mt-lg-0" data-bs-toggle="tooltip" title="PDF" download ><i class="fa-solid fa-file-arrow-down"></i></a>';
                }

                return $button;
            })

            ->rawColumns(['requesting_person', 'requesting_department', 'quote_status', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \modules\HumanResource\Entities\ProcurementRequest $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ProcurementRequest $model)
    {
        return $model->newQuery()
            ->with(['employee', 'department'])
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
            ->setTableId('request-table')
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
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4]]),
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

            Column::make('employee_id')
                ->title(localize('requesting_person'))
                ->orderable(false),

            Column::make('department_id')
                ->title(localize('requesting_department')),

            Column::make('date')
                ->title(localize('date')),

            Column::make('quote_status')
                ->title(localize('quote_status'))
                ->searchable(false)
                ->orderable(false),

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
        return 'RequestList_' . date('YmdHis');
    }
}
