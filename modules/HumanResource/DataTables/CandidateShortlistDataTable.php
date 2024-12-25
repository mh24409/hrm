<?php

namespace Modules\HumanResource\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\CandidateShortlist;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CandidateShortlistDataTable extends DataTable
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
            ->editColumn('candidate_id', function ($row) {
                return $row->candidate->last_name ? $row->candidate->first_name . " " . $row->candidate->last_name : $row->candidate->first_name;
            })
            ->filterColumn('candidate_id', function ($query, $keyword) {
                $query->whereHas('candidate', function ($query) use ($keyword) {
                    $query->where("first_name", "like", "%{$keyword}%")
                        ->orWhere("last_name", "like", "%{$keyword}%");
                });
            })

            ->editColumn('candidate_rand_id', function ($row) {
                return $row->candidate->candidate_rand_id;
            })
            ->filterColumn('candidate_rand_id', function ($query, $keyword) {
                $query->whereHas('candidate', function ($query) use ($keyword) {
                    $query->where("candidate_rand_id", "like", "%{$keyword}%");
                });
            })

            ->editColumn('position_id', function ($row) {
                return $row->position->position_name;
            })
            ->filterColumn('position_id', function ($query, $keyword) {
                $query->whereHas('position', function ($query) use ($keyword) {
                    $query->where("position_name", "like", "%{$keyword}%");
                });
            })

            ->addColumn('action', function ($row) {
                $button = '';
                if (auth()->user()->can('update_candidate_shortlist')) {
                    $button .= '<button onclick="editShortlistDetails(' . $row->id . ')" id="editShortlistDetails-' . $row->id . '" data-url="' . route('shortlist.edit', $row->id) . '" class="btn btn-success-soft btn-sm me-1" ><i class="fas fa-edit"></i></button>';
                }

                if (auth()->user()->can('delete_candidate_shortlist')) {
                    $button .= '<a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm mt-sm-1 mt-lg-0" data-bs-toggle="tooltip" title="Delete" data-route="' . route('shortlist.destroy', $row->id) . '" data-csrf="' . csrf_token() . '"><i class="fas fa-trash-alt"></i></a>';
                }

                return $button;
            })

            ->rawColumns(['name', 'candidate_id', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \modules\HumanResource\Entities\CandidateShortlist $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CandidateShortlist $model)
    {
        return $model->newQuery()->with(['candidate', 'position'])->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('shortlist-table')
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

            Column::make('candidate_id')
                ->title(localize('name'))->orderable(false),

            Column::make('candidate_rand_id')
                ->title(localize('candidate_id'))->orderable(false),

            Column::make('position_id')
                ->title(localize('job_position')),

            Column::make('shortlist_date')
                ->title(localize('shortlist_date')),

            Column::make('interview_date')
                ->title(localize('interview_date')),

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
        return 'CandidateShortlist_' . date('YmdHis');
    }
}
