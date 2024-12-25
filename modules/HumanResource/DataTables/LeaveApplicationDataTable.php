<?php

namespace Modules\HumanResource\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\ApplyLeave;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\LeaveType;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LeaveApplicationDataTable extends DataTable
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
                        ->orWhere('last_name', 'like', "%{$keyword}%")
                        ->orWhere('middle_name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('type', function ($row) {
                return ucwords($row->leaveType?->leave_type);
            })
            ->filterColumn('type', function ($query, $keyword) {
                $query->whereHas('leaveType', function ($query) use ($keyword) {
                    $query->where('leave_type', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('reason', function ($row) {
                return ucwords($row->reason);
            })

            ->filterColumn('reason', function ($query, $keyword) {
                $query->where('reason', 'like', "%{$keyword}%");
            })
            ->filterColumn('manager_approved_description', function ($query, $keyword) {
                $query->where('manager_approved_description', 'like', "%{$keyword}%");
            })
            ->addColumn('hard_copy', function ($row) {
                $document = '';
                if (!empty($row->location) || $row->location != null) {
                    $document .= '<img src="' . asset('storage/' . $row->location) . '" class="img-fluid img-thumbnail" width="60">';
                } else {
                    $document .= '<img src="' . asset('backend/assets/dist/img/nopreview.jpeg') . '" class="img-fluid img-thumbnail" width="60">';
                }

                return $document;
            })

            ->addColumn('status', function ($row) {
                $status = '';

                if ($row->is_approved_by_manager == 1) {
                    $status .= '<span class="badge badge-success-soft">' . localize('approved_by_manager') . '</span><br>';
                } else if ($row->is_approved_by_manager == 0) {
                    $status .= '<span class="badge badge-danger-soft">' . localize('pending_by_manager') . '</span><br>';
                }

                if ($row->is_approved == 1) {
                    $status .= '<span class="badge badge-success-soft">' . localize('approved') . '</span>';
                } else if ($row->is_approved == 0) {
                    $status .= '<span class="badge badge-danger-soft">' . localize('pending') . '</span>';
                }
                return $status;
            })

            ->addColumn('action', function ($row) {
                $button = '';
                if ($row->is_approved == 0) {

                    if (auth()->user()->can('update_leave_application')) {

                        $button .= '<a href="#" class="btn btn-success-soft btn-sm me-1 approve-application" data-url="' . route('leave.show_approve_leave_application', $row->id) . '" title="Approve"><i class="fa fa-check"></i></a>';
                    }

                    if (auth()->user()->can('update_leave_application')) {

                        $button .= '<a href="#" class="btn btn-success-soft btn-sm me-1 edit-application" data-url="' . route('leave.leave_application_edit', $row->id) . '" title="Edit"><i class="fa fa-edit"></i></a>';
                    }
                    if (auth()->user()->can('delete_leave_application')) {
                        $button .= '<a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm" data-bs-toggle="tooltip" title="Delete" data-route="' . route('leave.destroy', $row->uuid) . '" data-csrf="' . csrf_token() . '"><i class="fas fa-trash-alt"></i></a>';
                    }
                }

                return $button;
            })

            ->rawColumns(['employee_name', 'type', 'hard_copy', 'status', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \modules\HumanResource\Entities\ApplyLeave $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ApplyLeave $model)
    {
        $employee_id = $this->request->get('employee_id');

        return $model->newQuery()
            ->with('employee', 'leaveType')
            ->when($employee_id, function ($query) use ($employee_id) {
                return $query->where('employee_id', $employee_id);
            });
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('leave-application-table')
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
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]]),
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
                ->width(50)
                ->searchable(false)
                ->orderable(false),

            Column::make('employee_name')
                ->title(localize('employee_name')),

            Column::make('type')
                ->title(localize('type')),

            Column::make('leave_apply_date')
                ->title(localize('apply_date')),

            Column::make('leave_apply_start_date')
                ->title(localize('leave_start_date')),

            Column::make('leave_apply_end_date')
                ->title(localize('leave_end_date')),

            Column::make('total_apply_day')
                ->title(localize('days')),

            Column::make('reason')
                ->title(localize('reason')),

            Column::make('leave_approved_date')
                ->title(localize('approved_date')),

            Column::make('leave_approved_start_date')
                ->title(localize('approved_start_date')),

            Column::make('leave_approved_end_date')
                ->title(localize('approved_end_date')),

            Column::make('total_approved_day')
                ->title(localize('approved_days')),

            Column::make('hard_copy')
                ->title(localize('hard_copy')),

            Column::make('manager_approved_description')
                ->title(localize('manager_comments')),

            Column::make('status')
                ->title(localize('status')),

            Column::make('action')
                ->title(localize('action'))->addClass('column-sl')->orderable(false)
                ->width(130)
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
        return 'EmployeesLeave_' . date('YmdHis');
    }
}
