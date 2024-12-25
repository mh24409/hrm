<?php

namespace Modules\HumanResource\DataTables;


use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Modules\HumanResource\Entities\EmployeePerformanceType;
use Modules\HumanResource\Entities\EmployeePerformanceCriteria;
use Modules\HumanResource\Entities\EmployeePerformanceEvaluationType;

class PerformanceCriteriasDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $user = Auth::user();
                $button = "";
                if ($user->can('edit_performance_criteria')) {
                    $button .= '<a href="#" class="btn btn-primary-soft btn-sm me-1" data-bs-toggle="modal" data-bs-target="#update-criteria-'. $row->id.'" title="Edit"><i class="fa fa-edit"></i></a>';
                }
                if ($user->can('delete_performance_criteria')) {
                    $button .= '<a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm" data-bs-toggle="tooltip" title="Delete" data-route="'.route('performance-criterias.destroy', $row->id).'" data-csrf="'.csrf_token().'"><i class="fa fa-trash"></i></a>';
                }
                if ($user->can('edit_performance_criteria')) {
                    $button .= view('humanresource::employee.performance.criteria.modal.edit', [
                        'criteria' => $row,
                        'performance_types'     => EmployeePerformanceType::all(),
                        'evaluation_types'      => EmployeePerformanceEvaluationType::all()
                    ])->render();
                }

                return $button;
            })->addColumn('evaluation_type', function ($criteria) {
                return $criteria->evaluation_type?->type_name;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Modules\HumanResource\Entities\EmployeePerformanceCriteria $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(EmployeePerformanceCriteria $model): QueryBuilder
    {
        return $model->newQuery()->with(['performance_type', 'evaluation_type']);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('performancecriterias-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->autoWidth(false)
                    ->selectStyleSingle()
                    ->lengthMenu([
                        [25, 50, 100, 200, -1],
                        [25, 50, 100, 200, "All"]
                    ])
                    ->dom("<'row'<'col-md-4'l><'col-md-4 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                    ]);


    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex', 'SL')
                ->orderable(false)
                ->searchable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::make('title'),
            'performance_type' => new \Yajra\DataTables\Html\Column(['title' => 'Performance Type', 'data' => 'performance_type.title', 'name' => 'performance_type.title']),
            Column::make('description'),
            Column::computed('evaluation_type')->exportable(false)->printable(false)
            ->orderable(false)
            ->searchable(false),
            Column::computed('action')->exportable(false)->printable(false)
                ->orderable(false)
                ->searchable(false)

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'PerformanceCriterias_' . date('YmdHis');
    }
}
