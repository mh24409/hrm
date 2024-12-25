<?php

namespace Modules\HumanResource\DataTables;

use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Modules\HumanResource\Entities\Department;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class SubDepartmentDataTable extends DataTable
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

            ->editColumn('department_name', function ($row) {
                return $row->department_name ?? 'N/A';
            })
            ->editColumn('parent_name', function ($row) {
                return $row->parentDept()?->department_name ?? 'N/A';
            })

            ->editColumn('is_active', function ($row) {
                $statusBtn = '';
                if ($row->is_active == 1) {
                    $statusBtn = '<span class="badge bg-success">' . localize('active') . '</span>';
                } elseif ($row->is_active == 0) {
                    $statusBtn = '<span class="badge bg-danger">' . localize('inactive') . '</span>';
                }

                return $statusBtn;
            })

            ->addColumn('action', function ($row) {
                $button = '';
                if (auth()->user()->can('update_sub_department')) {
                    $button .= '<button onclick="editDetails(\'' . $row->uuid . '\')" id="editDetails-' . $row->uuid . '" data-edit-url="' . route('divisions.edit', $row->uuid) . '"  class="btn btn-success-soft btn-sm me-1" ><i class="fas fa-edit"></i></button>';
                }

                if (auth()->user()->can('delete_sub_department')) {
                    $button .= ' <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm" data-bs-toggle="tooltip" title="Delete" data-route="' . route('divisions.destroy', $row->uuid) . '" data-csrf="' . csrf_token() . '"><i class="fa fa-trash"></i></a>';
                }

                return $button;
            })

            ->rawColumns(['is_active', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param Department $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Department $model)
    {
        return $model->newQuery()
            ->whereNotNull('parent_id')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('sub-department-table')
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle table-sm')
            ->columns($this->getColumns())
            ->serverSide(false)
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
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3]]),
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

            Column::make('department_name')
                ->title(localize('sub_department_name')),

            Column::make('parent_name')
                ->title(localize('department_name')),

            Column::make('is_active')
                ->title(localize('status')),

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
        return 'Division_' . date('YmdHis');
    }
}
