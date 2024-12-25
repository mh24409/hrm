<?php

namespace Modules\HumanResource\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Modules\HumanResource\Entities\Employee;
use Modules\Setting\Entities\DocExpiredSetting;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class EmployeeReportDataTable extends DataTable
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

            ->editColumn('employee_id', function ($employee) {
                return $employee->employee_id;
            })
            ->editColumn('full_name', function ($employee) {
                return ucwords($employee->full_name);
            })
            ->filterColumn('full_name', function ($query, $keyword) {
                $query->where('first_name', 'like', "%{$keyword}%")
                    ->orWhere('last_name', 'like', "%{$keyword}%")
                    ->orWhere('middle_name', 'like', "%{$keyword}%");
            })
            ->editColumn('position_id', function ($employee) {
                return ucwords($employee->position ? $employee->position->position_name : '');
            })
            ->filterColumn('position_id', function ($query, $keyword) {
                $query->whereHas('position', function ($query) use ($keyword) {
                    $query->where('position_name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('status', function ($employee) {
                $status = '';

                if ($employee->is_active == 1) {
                    $status .= '<span class="badge badge-success-soft">' . localize('active') . '</span>';
                } else {
                    $status .= '<span class="badge badge-danger-soft">' . localize('inactive') . '</span>';
                }
                return $status;
            })

            ->rawColumns(['status', 'action']);
    }

    /**
     * Get query source of dataTable.
     */
    public function query(Employee $model): QueryBuilder
    {
        $employee_id = $this->request->get('employee_id');
        $position_id = $this->request->get('position_id');

        return $model->newQuery()
            ->when($employee_id, function ($query) use ($employee_id) {
                return $query->where('employee_id', $employee_id);
            })
            ->when($position_id, function ($query) use ($position_id) {
                return $query->where('position_id', $position_id);
            })
            ->with(['department', 'position']);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('employee-report-table')
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'asc')
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
                    ->text('<i class="fa fa-file-csv"></i> CSV'),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel'),
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
                ->orderable(false)
                ->searchable(false),

            Column::make('employee_id')
                ->title(localize('employee_id'))
                ->orderable(true),

            Column::make('full_name')
                ->title(localize('name_of_employee'))
                ->orderable(false),

            Column::make('email')
                ->title(localize('email'))
                ->orderable(true),

            Column::make('phone')
                ->title(localize('mobile_no'))
                ->orderable(true),

            Column::make('date_of_birth')
                ->title(localize('date_of_birth'))
                ->orderable(true),

            Column::make('position_id')
                ->title(localize('designation'))
                ->orderable(true),

            Column::make('joining_date')
                ->title(localize('joining_date'))
                ->orderable(true),

            Column::make('status')
                ->title(localize('status'))
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
        return 'EmployeeReport-' . date('YmdHis');
    }
}
