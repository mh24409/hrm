<?php

namespace Modules\HumanResource\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\Vacation;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class VacationDataTable extends DataTable
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
            ->addColumn('title', function ($row) {
                return $row->title;
            })
            ->addColumn('employee_name', function ($row) {
                return $row->employee?->full_name;
            })
            ->addColumn('amount', function ($row) {
                return $row->amount;
            })
            ->addColumn('date', function ($row) {
                return $row->year;
            })
            ->addColumn('action', function ($row) {
                $button = '<a href="#" class="btn btn-primary-soft btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editVacation-' . $row->id . '" title="' . get_phrases("edit") . '"><i class="fa fa-edit"></i></a>';
                $button .= '<a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm" data-bs-toggle="tooltip" title="' . get_phrases("delete") . '" data-route="' . route('vacation.destroy', $row->uuid) . '" data-csrf="' . csrf_token() . '"><i class="fa fa-trash"></i></a>';

                $button .= view('humanresource::vacation.modal.edit', [
                    'item' => $row,
                    'employees' => Employee::all(),
                ])->render();

                return $button;
            });

        return $query->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Modules\HumanResource\Entities\Vacation $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Vacation $model): Builder
    {
        return $model->newQuery()->with('employee');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('vacation-table')
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

            Column::make('title')
                ->title(localize('title'))
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false),

            Column::make('employee_name')
                ->title(localize('employee_name'))
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false),

            Column::make('amount')
                ->title(localize('amount'))
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false),

            Column::make('date')
                ->title(localize('date'))
                ->addClass('text-center')
                ->searchable(false)
                ->orderable(false),

            Column::make('action')
                ->title(localize('action'))->addClass('column-sl')->orderable(false)
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
        return 'Vacation_' . date('YmdHis');
    }
}
