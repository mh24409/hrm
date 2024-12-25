<?php

namespace Modules\HumanResource\DataTables;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\Attendance;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class JobCardDataTable extends DataTable
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
            ->addColumn('date', function ($row) {
                return $row->date;
            })
            ->addColumn('time_in', function ($row) {
                return Carbon::parse($row->in_time)->format('H:i:s');
            })

            ->addColumn('time_out', function ($row) {
                return Carbon::parse($row->out_time)->format('H:i:s');
            })
            ->addColumn('late', function ($row) {

                $in_time = Carbon::parse($row->in_time)->format('H:i:s');

                $start_time = Carbon::parse($row->employee?->employee_group?->start_time);

                $totalDuration = 0;
                if ($in_time && $start_time) {
                    $totalDuration = $start_time->diffInSeconds($in_time);
                }

                if ($start_time->format('H:i:s') < $in_time) {
                    return '<span class="badge badge-danger-soft sale-badge-ft-13">Late ' . ' (' . gmdate('H:i:s', $totalDuration) . ') </span>';
                }
            })
            ->addColumn('status', function ($row) {
                $status = '';

                if ($row->status == 'Present') {
                    $status .= '<span class="badge badge-success-soft">' . $row->status . '</span><br>';
                } else if ($row->status == 'Absent') {
                    $status .= '<span class="badge badge-danger-soft">' . $row->status . '</span><br>';
                } else if ($row->status == 'Holiday') {
                    $status .= '<span class="badge badge-warning-soft">' . $row->status . '</span><br>';
                } else if ($row->status == 'Weekend') {
                    $status .= '<span class="badge badge-warning-soft">' . $row->status . '</span><br>';
                }
                return $status;
            });

        return $query->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Attendance $model)
    {
        $fromDate = Carbon::now()->startOfMonth();
        $toDate = Carbon::now()->endOfMonth();

        $date = $this->request->get('date');
        $string = explode('-', $date);
        if ($date) {
            $fromDate = date('Y-m-d', strtotime($string[0]));
            $toDate = date('Y-m-d', strtotime($string[1]));
        }

        $employee_id = $this->request->get('employee_id');

        $query = $model->newQuery()->selectRaw('employee_id, DATE(time) as date, MIN(time) as in_time, MAX(time) as out_time')
            ->where('employee_id', $employee_id)
            ->when($date, function ($query) use ($fromDate, $toDate) {
                $query->whereDate('time', '>=', $fromDate)
                    ->whereDate('time', '<=', $toDate);
            })
            ->with('employee.employee_group')
            ->groupBy('employee_id', 'date');

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
            ->setTableId('job-card-table')
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->language([
                'processing' => '<div class="lds-spinner">
                <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
            ])
            ->responsive(true)
            ->selectStyleSingle()
            ->lengthMenu([[30, 50, 100, -1], [30, 50, 100, 'All']])
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            ->footerCallback('function ( row, data, start, end, display ) {
                var api = this.api(), data;

                $(api.column( 1).footer() ).html(`<span class="text-end d-block">Present</span>`);
                $(api.column( 2 ).footer() ).html(Object.values(data).filter(v => v.in_time).length);
                $(api.column( 3).footer() ).html(`<span class="text-end d-block">Late</span>`);
                $(api.column( 4 ).footer() ).html(Object.values(data).filter(v => v.late).length);
            }')
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
                ->searchable(false)
                ->orderable(false),

            Column::make('date')
                ->title(localize('date'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('time_in')
                ->title(localize('time_in'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('time_out')
                ->title(localize('time_out'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('late')
                ->title(localize('late'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('status')
                ->title(localize('status')),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'jobCard' . date('YmdHis');
    }
}
