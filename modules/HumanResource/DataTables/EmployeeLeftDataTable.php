<?php

namespace Modules\HumanResource\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\EmployeeLeft;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmployeeLeftDataTable extends DataTable
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
            ->addColumn('type_of_left', function ($row) {
                return $row->employeeLeftType?->title;
            })
            ->filterColumn('employee_name', function ($query, $keyword) {
                $query->whereHas('employee', function ($query) use ($keyword) {
                    $query->where('first_name', 'like', "%{$keyword}%")
                        ->orWhere('last_name', 'like', "%{$keyword}%")
                        ->orWhere('middle_name', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('type_of_left', function ($query, $keyword) {
                $query->whereHas('employeeLeftType', function ($query) use ($keyword) {
                    $query->where('title', 'like', "%{$keyword}%");
                });
            })

            ->addColumn('action', function ($row) {

                $button = '';
                if (auth()->user()->can('update_leave')) {
                    $button .= '<a onclick="editEmployeeLeft(' . $row->id . ')" class="btn btn-primary-soft btn-sm me-1"  title="' . __("language.edit") . '"><i class="fa fa-edit"></i></a>';

                }

                if (auth()->user()->can('delete_leave')) {
                    $button .= '<a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm" data-bs-toggle="tooltip"  title="' . __("language.delete") . '" data-route="' . route('left.destroy', $row->id) . '" data-csrf="' . csrf_token() . '"><i class="fas fa-trash-alt"></i></a>';

                }

                return $button;
            });
        return $query->escapeColumns([]);

    }

    /**
     * Get query source of dataTable.
     *
     * @param \Modules\HumanResource\Entities\EmployeeLeft $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(EmployeeLeft $model)
    {
        $allData = $model->newQuery()->with(['employeeLeftType',
            'employee']);

        return $allData;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('employees-left-table')
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
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 5]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 5]]),
                Button::make('pdf')
                    ->className('btn btn-secondary buttons-pdf buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-pdf"></i> PDF')
                    ->extend('pdfHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 5]]),
                Button::make('print')
                    ->className('btn btn-secondary buttons-print btn-sm prints')
                    ->text('<i class="fa fa-print"></i> Print')->exportOptions(['columns' => [0, 1, 2, 3, 5]]),
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

            Column::make('left_date')
                ->title(localize('left_date')),

            Column::make('type_of_left')
                ->title(localize('type_of_left')),

            Column::make('comments')
                ->title(localize('comment')),

            Column::make('action')
                ->title('Action')
                ->addClass('d-flex align-middle')
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
        return 'Left_Employees_' . date('YmdHis');
    }
}
