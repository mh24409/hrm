<?php

namespace Modules\HumanResource\DataTables;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\Attendance;
use Modules\HumanResource\Entities\ManualAttendance;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LateClosingAttendanceDataTable extends DataTable
{
    /**
     * Build DataTable Class
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $query = (new EloquentDataTable($query))

            ->addIndexColumn()
            ->editColumn('date', function ($query) {
                return Carbon::parse($query->time)->format('d-m-Y');
            })
            ->editColumn('in_time', function ($query) {
                return Carbon::parse($query->in_time)->format('h:i:s A');
            })
            ->editColumn('out_time', function ($query) {
                return Carbon::parse($query->out_time)->format('h:i:s A');
            })
            ->editColumn('setup_in_time', function ($query) {
                return $query->employee?->attendance_time->start_time;
            })
            ->editColumn('late_time', function ($query) {
                $in_time = Carbon::parse($query->in_time);
                $setup_in_time = Carbon::parse($query->employee?->attendance_time?->start_time);

                if ($in_time->greaterThan($setup_in_time)) {
                    $late_time = @$in_time->diffInMinutes($setup_in_time) . ' min';
                } else {
                    $late_time = '--';
                }

                return $late_time;
            })
            ->editColumn('setup_out_time', function ($query) {
                return $query->employee->attendance_time->end_time;
            })
            ->editColumn('early_closing', function ($query) {
                $out_time = Carbon::parse($query->out_time);
                $setup_out_time = Carbon::parse($query->employee?->attendance_time->end_time);

                if ($out_time->lessThan($setup_out_time)) {
                    $early_closing = @$setup_out_time->diffInMinutes($out_time) . ' min';
                } else {
                    $early_closing = '--';
                }

                return $early_closing;
            });

        return $query->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Attendance $model)
    {
        $employee_id = $this->request->get('employee_id');
        $year = $this->request->get('year');
        $month = $this->request->get('month');

        $query = $model->newQuery()
            ->with(['employee' => function ($query) {
                $query->with('attendance_time');
            }])
            ->selectRaw('DATE(time) as date, MIN(time) as in_time, MAX(time) as out_time, time, employee_id')
            ->whereEmployeeId($employee_id)
            ->whereYear('time', '=', $year)
            ->whereMonth('time', '=', $month)
            ->groupBy('date');
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
            ->setTableId('lateness-closing-attendance-table')
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
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7]]),
                Button::make('print')
                    ->className('btn btn-secondary buttons-print btn-sm prints')
                    ->text('<i class="fa fa-print"></i> Print')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7]])
                    ->footer(false)
                    ->customize(
                        'function(win) {
                            $(win.document.body).css(\'padding\',\'20px\');
                            $(win.document.body).find(\'table\').addClass(\'table-border\',\'fs-10\');

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

            Column::make('date')
                ->title(localize('date'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('in_time')
                ->title(localize('in_time'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('setup_in_time')
                ->title(localize('attendance_setup_(_in_time_)'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('late_time')
                ->title(localize('late_time'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('out_time')
                ->title(localize('out_time'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('setup_out_time')
                ->title(localize('attendance_setup_(_out_time_)'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('early_closing')
                ->title(localize('early_closing'))
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
        return 'LatenessClosingAttendance_' . date('YmdHis');
    }
}
