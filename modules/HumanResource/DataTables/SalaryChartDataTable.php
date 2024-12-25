<?php

namespace Modules\HumanResource\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\EmployeeSalaryType;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SalaryChartDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $query = (new EloquentDataTable($query))
            ->addIndexColumn()

            ->addColumn('employee_name', function ($row) {
                return $row->employee?->full_name;
            })
            ->filterColumn('employee_name', function ($query, $keyword) {
                $query->whereHas('employee', function ($query) use ($keyword) {
                    $query->where('first_name', 'like', "%{$keyword}%")
                        ->orWhere('last_name', 'like', "%{$keyword}%")
                        ->orWhere('middle_name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('gross_salary', function ($row) {
                return $row->employee?->employee_files?->gross_salary;
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d');
            })
            ->addColumn('updated_at', function ($row) {
                return $row->updated_at->format('Y-m-d');
            })

            ->addColumn('action', function ($info) {
                if (auth()->user()->can('update_salary_setup')) {
                    $button = '<a href="' . route('salary-setup.edit', $info->employee_id) . '" class="btn btn-warning btn-sm me-1" title=""><i class="fa fa-edit"></i> Edit</a>';
                }
                return $button;
            });

        return $query->escapeColumns([]);

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\EmployeeDocument $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(EmployeeSalaryType $model)
    {
        $salary_setup = $model->newQuery()->where('is_active', true)->with('setup_rule')->groupBy('employee_id');
        return $salary_setup;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('salary-chart-table')
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle table-sm')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->language([
                //change preloader icon
                'processing' => '<div class="lds-spinner">
                <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
            ])
            ->responsive(true)
            ->selectStyleSingle()
            ->lengthMenu([[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']])
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            ->buttons([
                Button::make('copy')
                    ->className('btn btn-secondary buttons-copy buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-copy"></i> Copy'),
                Button::make('csv')
                    ->className('btn btn-secondary buttons-csv buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4]]),
                Button::make('pdf')
                    ->className('btn btn-secondary buttons-pdf buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-pdf"></i> PDF')
                    ->extend('pdfHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4]]),
                Button::make('print')
                    ->className('btn btn-secondary buttons-print btn-sm prints')
                    ->text('<i class="fa fa-print"></i> Print')->exportOptions(['columns' => [0, 1, 2, 3, 4]]),
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
                ->searchable(false)
                ->orderable(false),

            Column::make('employee_name')
                ->title(localize('employee_name')),

            Column::make('gross_salary')
                ->title(localize('gross_salary')),

            Column::make('created_at')
                ->title(localize('created_at')),

            Column::make('updated_at')
                ->title(localize('updated_at')),

            Column::make('action')
                ->title(localize('action'))->addClass('column-sl')->orderable(false)
                ->width(300)
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
        return 'EmployeesSalary_' . date('YmdHis');
    }
}
