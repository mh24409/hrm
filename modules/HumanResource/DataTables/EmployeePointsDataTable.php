<?php

namespace Modules\HumanResource\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\RewardPoint;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmployeePointsDataTable extends DataTable
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
            ->addColumn('employee_name', function ($row) {
                return ucwords($row->employee?->full_name ?? '');
            })
            ->filterColumn('employee_name', function ($query, $keyword) {
                $query->whereHas('employee', function ($query) use ($keyword) {
                    $query->where('first_name', 'like', "%{$keyword}%")
                    ->orWhere('last_name', 'like', "%{$keyword}%");
                });
            })

            ->addColumn('attendance_points', function ($row) {
                return $row->attendance;
            })

            ->addColumn('collaborative_points', function ($row) {
                return $row->collaborative;
            })

            ->addColumn('management_points', function ($row) {
                return $row->management;
            })

            ->addColumn('total_points', function ($row) {
                return $row->total;
            })

            ->addColumn('date', function ($row) {
                // Assuming $row->date is in Y-m-d format
                $date = strtotime($row->date);
                // Get the month name
                $monthName = date('F Y', $date);
                return $monthName;
            })

            ->rawColumns([]);

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\EmployeeDocument $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(RewardPoint $model)
    {
        
        $start_date = $this->request->get('start_date');
        $end_date   = $this->request->get('end_date');

        return $model->newQuery()
            ->with('employee')
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {

                // Extract year and month from start date
                $start_date = strtotime($start_date);
                $start_year = date('Y', $start_date);
                $start_month = date('m', $start_date);
        
                // Extract year and month from end date
                $end_date = strtotime($end_date);
                $end_year = date('Y', $end_date);
                $end_month = date('m', $end_date);
            
                // Filter data based on year and month range
                return $query->where(function ($query) use ($start_year, $start_month, $end_year, $end_month) {
                    // Condition for start date
                    $query->where(function ($query) use ($start_year, $start_month) {
                        $query->whereYear('date', '>=', $start_year)
                                ->whereMonth('date', '>=', $start_month);
                    });
        
                    // Condition for end date
                    $query->where(function ($query) use ($end_year, $end_month) {
                        $query->whereYear('date', '<=', $end_year)
                                ->whereMonth('date', '<=', $end_month);
                    });
                });
            })->orderBy('employee_id', orderByData($this->request->get('order')));
            
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('employee-points-table')
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
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5]]),
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

            Column::make('employee_name')
                ->title(localize('employee_name')),

            Column::make('attendance_points')
                ->title(localize('attendance_points')),

            Column::make('collaborative_points')
                ->title(localize('collaborative_points')),

            Column::make('management_points')
                ->title(localize('management_points')),

            Column::make('total_points')
                ->title(localize('total_points')),

            Column::make('date')
                ->title(localize('date')),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'EmployeesPoints_' . date('YmdHis');
    }
}
