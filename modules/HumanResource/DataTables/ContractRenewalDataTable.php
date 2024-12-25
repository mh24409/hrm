<?php

namespace Modules\HumanResource\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Modules\HumanResource\Entities\Employee;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ContractRenewalDataTable extends DataTable
{
    /**
     * Build DataTable Class
     *
     * @param mixed $query Results from query() method.
     */
    public function dataTable(Builder $query): EloquentDataTable
    {
        $query = (new EloquentDataTable($query))

            ->addIndexColumn()

            ->addColumn('staff_name', function ($row) {
                return $row->full_name;
            })
            ->addColumn('position', function ($row) {
                return $row->position?->position_name;
            })
            ->addColumn('grade', function ($row) {
                return $row->employee_grade;
            })
            ->addColumn('joining_date', function ($row) {
                return $row->joinning_date;
            })
            ->addColumn('contract_start_date', function ($row) {
                return $row->contract_start_date;
            })
            ->addColumn('contract_end_date', function ($row) {
                return $row->contract_end_date;
            });

        return $query->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Employee $model): Builder
    {
        $department = $this->request->get('department_id');

        $query = $model->newQuery()
            ->when($department, function ($q) use ($department) {
                return $q->where('department_id', $department);
            })
            ->where('duty_type_id', 3)
            ->whereNotNull('id')
            ->with('department');

        return $query;

    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('contract-renewal-table')
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
                Button::make('csv')
                    ->className('btn btn-secondary buttons-csv buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4, 7]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4, 7]]),
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

            Column::make('staff_name')
                ->title(localize('staff_name'))
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false),

            Column::make('position')
                ->title(localize('position'))
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false),

            Column::make('grade')
                ->title(localize('grade'))
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false),

            Column::make('joining_date')
                ->title(localize('joining_date'))
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false),

            Column::make('contract_start_date')
                ->title(localize('contract_start_date'))
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false),

            Column::make('contract_end_date')
                ->title(localize('contract_end_date'))
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'ContractRenewal_' . date('YmdHis');
    }
}
