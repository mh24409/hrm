<?php

namespace Modules\HumanResource\DataTables;

use Illuminate\Support\Str;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Modules\HumanResource\Entities\CandidateSelection;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CandidateSelectionDataTable extends DataTable
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
            ->editColumn('name', function ($row) {
                return $row->candidate->last_name ? $row->candidate->first_name . " " . $row->candidate->last_name : $row->candidate->first_name;
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereHas('candidate', function ($query) use ($keyword) {
                    $query->where("first_name", "like", "%{$keyword}%")
                        ->orWhere("last_name", "like", "%{$keyword}%");
                });
            })

            ->editColumn('candidate_id', function ($row) {
                return $row->candidate->candidate_rand_id;
            })

            ->editColumn('employee_id', function ($row) {
                return $row->employee->employee_id ?? 'N/A';
            })
            ->filterColumn('employee_id', function ($query, $keyword) {
                $query->whereHas('employee', function ($query) use ($keyword) {
                    $query->where("employee_id", "like", "%{$keyword}%")
                        ->orWhere("first_name", "like", "%{$keyword}%")
                        ->orWhere("last_name", "like", "%{$keyword}%")
                        ->orWhere("middle_name", "like", "%{$keyword}%");
                });
            })

            ->editColumn('position', function ($row) {
                return $row->position->position_name;
            })
            ->filterColumn('position', function ($query, $keyword) {
                $query->whereHas('position', function ($query) use ($keyword) {
                    $query->where("position_name", "like", "%{$keyword}%");
                });
            })

            ->editColumn('selection_terms', function ($row) {
                return Str::limit($row->selection_terms, 20);
            })

            ->addColumn('action', function ($row) {
                return '<span class="badge bg-success">' . localize("Employee Created") . '</span>';
            })

            ->rawColumns(['action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \modules\HumanResource\Entities\CandidateSelection $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CandidateSelection $model)
    {
        return $model->newQuery()->with(['employee', 'candidate', 'position'])->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('selection-table')
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
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6]]),
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

            Column::make('name')
                ->title(localize('name'))->orderable(false),

            Column::make('candidate_id')
                ->title(localize('candidate_id')),

            Column::make('employee_id')
                ->title(localize('employee_id')),

            Column::make('position')
                ->title(localize('position'))->orderable(false),

            Column::make('selection_terms')
                ->title(localize('selection_terms')),

            Column::make('action')
                ->title(localize('action'))->addClass('column-sl')->orderable(false)
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
        return 'Selectionlist_' . date('YmdHis');
    }
}
