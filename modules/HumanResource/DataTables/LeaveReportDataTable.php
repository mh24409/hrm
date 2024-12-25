<?php

namespace Modules\HumanResource\DataTables;

use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Modules\HumanResource\Entities\Employee;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\ApplyLeave;

class LeaveReportDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $query = (new EloquentDataTable($query))

            ->addIndexColumn()
            ->editColumn('employee_name', function ($query) {
                return $query?->employee?->full_name;
            })
            ->editColumn('department_id', function ($query) {
                return $query?->employee?->department?->department_name;
            })
            ->editColumn('leave_type', function ($query) {
                return $query?->leaveType->leave_type;
            })
            ->editColumn('start_date', function ($query) {
                return $query->leave_approved_start_date;
            })
            ->editColumn('end_date', function ($query) {
                return $query->leave_approved_end_date;
            });

        return $query->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ApplyLeave $model)
    {
        $department_id = $this->request->get('department_id');
        $leave_type_id = $this->request->get('leave_type_id');
        $date = $this->request->get('date');
        $string = explode('-', $date);

        $startDate = null;
        $endDate = null;

        if ($date) {
            $startDate = date('Y-m-d', strtotime($string[0]));
            $endDate = date('Y-m-d', strtotime($string[1]));
        }

        $query = $model->newQuery()
            ->where('is_approved_by_manager', true)
            ->when($leave_type_id, function ($query) use ($leave_type_id) {
                $query->where('leave_type_id', $leave_type_id);
            })
            ->when($department_id, function ($query) use ($department_id) {
                $query->with(['employee' => function ($query) use ($department_id) {
                    $query->with('department:id,department_name')->where('department_id', $department_id);
                }]);
            })
            ->with(['employee', 'leaveType'])
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereDate('leave_approved_start_date', '>=', $startDate)
                    ->whereDate('leave_approved_end_date', '<=', $endDate);
            });

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        $print_admin_and_time = localize('print_date') . Carbon::now()->format('d-m-Y h:i:sa') . ", User: " . auth()->user()->full_name;
        return $this->builder()
            ->setTableId('leave-report-table')
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle')
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
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5]]),
                Button::make('print')
                    ->className('btn btn-secondary buttons-print btn-sm prints')
                    ->text('<i class="fa fa-print"></i> Print')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5]])
                    ->footer(false)
                    ->customize(
                        'function(win) {

                            $(win.document.body).css(\'padding\',\'20px\');
                            $(win.document.body).find(\'table\').addClass(\'print-table-border\',\'fs-10\');

                            //date range
                            var date_range = $(\'#date\').val();
                            if(date_range == \'\'){
                                date_range = \'All\';
                            }
                            //remove header
                            $(win.document.body).find(\'h1\').remove();


                           //add print date and time
                            $(win.document.body)
                                .prepend(
                                    \'<p class="fs-10 mb-0 pb-0">' . $print_admin_and_time . '</p>\'+
                                    \'<div class="text-center mt-0 pt-0"><img src="' . logo_64_data() . '" alt="logo" width="135"></div>\'+
                                    \'<p class="text-center fs-12 mt-0 pt-0">' . app_setting()->address . '</p>\'+
                                    \'<h5 class="text-center">Daily Attendance Report</h5>\'+
                                    \'<p class="text-end mb-0 fs-10">Date Range: \'+date_range+\'</p>\'
                                );

                        }'
                    ),
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
                ->title(localize('name'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('department_id')
                ->title(localize('department'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('leave_type')
                ->title(localize('leave_type'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('start_date')
                ->title(localize('start_date'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('end_date')
                ->title(localize('end_date'))
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
        return 'LeaveReport_' . date('YmdHis');
    }
}
