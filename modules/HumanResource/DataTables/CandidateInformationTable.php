<?php

namespace Modules\HumanResource\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\CandidateInformation;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CandidateInformationTable extends DataTable
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
                return $row->last_name ? $row->first_name . " " . $row->last_name : $row->first_name;
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->whereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$keyword}%"]);
            })

            ->editColumn('picture', function ($row) {
                $image = '';
                if (!empty($row->picture) || $row->picture != null) {
                    $image .= '<img src="' . asset('storage/' . $row->picture) . '" width="60" height="60" style="border-radius: 8px; border: 2px solid #e1e9f1;">';
                } else {
                    $image .= '<img src="' . asset('backend/assets/dist/img/nopreview.jpeg') . '" width="60" height="60" style="border-radius: 8px; border: 2px solid #e1e9f1;">';
                }
                return $image;
            })

            ->addColumn('action', function ($row) {
                $button = '';
                if (auth()->user()->can('update_candidate_list')) {
                    $button .= '<a href="' . route('candidate.edit', $row->id) . '" class="btn btn-success-soft btn-sm me-1 mt-sm-1 mt-lg-0"><i class="fas fa-edit"></i></a>';
                }

                if (auth()->user()->can('delete_candidate_list')) {
                    $button .= '<a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm mt-sm-1 mt-lg-0" data-bs-toggle="tooltip" title="Delete" data-route="' . route('candidate.destroy', $row->id) . '" data-csrf="' . csrf_token() . '"><i class="fas fa-trash-alt"></i></a>';
                }

                if (auth()->user()->can('read_candidate_list')) {
                    $button .= '<a href="' . route('candidate.show', $row->id) . '" class="btn btn-success-soft btn-sm ms-sm-0 ms-lg-1 mt-sm-1 mt-lg-0"><i class="fa fa-eye"></i></a>';
                }

                return $button;
            })

            ->rawColumns(['picture', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \modules\HumanResource\Entities\CandidateInformation $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CandidateInformation $model)
    {
        return $model->newQuery()->orderBy('id', 'desc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('candidate-table')
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

            Column::make('candidate_rand_id')
                ->title(localize('candidate_id')),

            Column::make('picture')
                ->title(localize('photograph'))
                ->searchable(false)
                ->orderable(false),

            Column::make('email')
                ->title(localize('email_address')),

            Column::make('ssn')
                ->title(localize('SSN')),

            Column::make('phone')
                ->title(localize('phone')),

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
        return 'CandidateList_' . date('YmdHis');
    }
}
