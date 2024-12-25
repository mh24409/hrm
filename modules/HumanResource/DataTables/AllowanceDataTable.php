<?php

namespace Modules\HumanResource\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Modules\HumanResource\Entities\EmployeeAllowenceDeduction;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AllowanceDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query): EloquentDataTable
    {
        $query = (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('staff_name', function ($row) {
                return $row->employee?->full_name;
            })
            ->addColumn('branch', function ($row) {
                return $row->employee?->branch?->branch_name;
            })
            ->addColumn('position', function ($row) {
                return $row->employee?->position?->position_name;
            })
            ->addColumn('allowance_type', function ($row) {
                return $row->setup_rule?->name;
            })
            ->addColumn('allowance_amount', function ($row) {
                return app_setting()->currency?->title . $row->amount;
            })
            ->addColumn('total_allowance', function ($row) {
                return app_setting()->currency?->title . ' ' . number_format($row->sum('amount'), 2);
            });

        return $query->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     */
    public function query(EmployeeAllowenceDeduction $model)
    {
        $branch = $this->request->get('branch_id');
        $department = $this->request->get('department_id');

        $query = $model->newQuery()
            ->with('setup_rule', 'employee')
            ->whereHas('setup_rule', function ($query) {
                $query->where('type', 'allowance');
            })
            ->when($branch, function ($q) use ($branch) {
                $q->employee->where('branch_id', $branch);
            })
            ->when($department, function ($q) use ($department) {
                $q->employee->where('department_id', $department);
            })->orderBy('employee_id');

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
            ->setTableId('allowance-report-table')
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
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5]]),
                Button::make('pdf')
                    ->className('btn btn-secondary buttons-pdf buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-pdf"></i> PDF')
                    ->extend('pdfHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5]]),
                Button::make('print')
                    ->className('btn btn-secondary buttons-print btn-sm prints')
                    ->text('<i class="fa fa-print"></i> Print')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5]]),
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
                ->searchable(true)
                ->orderable(false),

            Column::make('branch')
                ->title(localize('branch'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('position')
                ->title(localize('position'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('allowance_type')
                ->title(localize('allowance_type'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('allowance_amount')
                ->title(localize('allowance_amount'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('total_allowance')
                ->title(localize('total_allowance') . "(" . localize('till_date') . ")")
                ->addClass('text-center')
                ->searchable(true)
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
        return 'Allowance_Report_' . date('YmdHis');
    }
}
