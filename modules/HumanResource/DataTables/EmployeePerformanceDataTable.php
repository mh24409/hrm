<?php

namespace Modules\HumanResource\DataTables;

use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Modules\HumanResource\Entities\EmployeePerformance;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class EmployeePerformanceDataTable extends DataTable
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

            ->editColumn('employee_id', function ($query) {
                return $query->employee->full_name;
            })
            ->filterColumn('employee_id', function ($query, $keyword) {
                $query->whereHas('employee', function ($query) use ($keyword) {
                    $query->where('first_name', 'like', '%' . $keyword . '%')
                        ->orWhere('last_name', 'like', '%' . $keyword . '%')
                        ->orWhere('middle_name', 'like', '%' . $keyword . '%');
                });
            })
            ->editColumn('created_at', function ($query) {
                return Carbon::parse($query->created_at)->format('Y-m-d');
            })
            ->addColumn('action', function ($query) {

                $button = '';
                $button .= '<div class="btn-group" role="group">';
                if (auth()->user()->can('read_employee_performance')) {
                    $button .= '<a href="' . route("employee-performances.show", $query->uuid) . '" class="btn btn-primary-soft btn-sm me-1" title="Edit"><i class="fa fa-eye"></i></a>';
                }
                if (auth()->user()->can('update_employee_performance')) {
                    $button .= '<a href="' . route('employee-performances.edit', $query->uuid) . '" class="btn btn-primary-soft btn-sm me-1" title="Edit"><i class="fa fa-edit"></i></a>';
                }
                if (auth()->user()->can('delete_employee_performance')) {
                    $button .= '<a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm" data-bs-toggle="tooltip" title="Delete" data-route="' . route('employee-performances.destroy', $query->uuid) . '" data-csrf="' . csrf_token() . '"><i class="fa fa-trash"></i></a>';
                }
                $button .= '</div>';

                return $button;
            })
            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query(EmployeePerformance $model): QueryBuilder
    {
        return $model->newQuery()->with('employee');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('employee-performance-table')
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle')
            ->columns($this->getColumns())
            ->orderBy(3, 'desc')
            ->minifiedAjax()
            ->language([
                //change preloader icon
                'processing' => '<div class="lds-spinner">
                <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
            ])
            ->selectStyleSingle()
            ->lengthMenu([[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']])
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            ->buttons([
                Button::make('csv')
                    ->className('btn btn-secondary buttons-csv buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')->exportOptions(['columns' => [0, 1, 2, 3]]),
            ]);
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
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false),

            Column::make('employee_id')
                ->title(localize('employee_name'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false)
                ->printable(true),

            Column::make('total_score')
                ->title(localize('total_score'))
                ->addClass('text-center')
                ->searchable(true)
                ->printable(true),

            Column::make('created_at')
                ->title(localize('create_date'))
                ->addClass('text-center')
                ->searchable(true)
                ->printable(true),

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
        return 'EmployeePerformance-' . date('YmdHis');
    }
}
