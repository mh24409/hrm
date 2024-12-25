<?php

namespace Modules\HumanResource\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\Loan;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LoanDataTable extends DataTable
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
            ->addColumn('permitted_by', function ($row) {
                return $row->permission_by?->full_name ?? '';
            })
            ->addColumn('status', function ($row) {
                $status = '';

                if ($row->is_active == 1) {
                    $status .= '<span class="badge badge-success-soft">' . localize('active') . '</span>';
                } else {
                    $status .= '<span class="badge badge-danger-soft">' . localize('inactive') . '</span>';
                }
                return $status;
            })
            ->addColumn('action', function ($row) {
                $button = '';
                if (auth()->user()->can('update_loan')) {
                    $button .= '<a href="' . route('hr.loan.edit', $row->uuid) . '" class="btn btn-info-soft btn-sm me-1 mt-1"  data-placement="right" title="Update"><i class="fas fa-edit"></i></a>';
                }
                if (auth()->user()->can('delete_loan')) {
                    $button .= '<a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm mt-1" data-bs-toggle="tooltip" title="Delete" data-route="' . route('loans.destroy', $row->uuid) . '" data-csrf="' . csrf_token() . '"><i class="fas fa-trash-alt"></i></a>';
                }

                return $button;
            })
            ->rawColumns(['employee_name', 'permitted_by', 'status', 'action']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param Loan $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Loan $model)
    {
        $employee_id = $this->request->get('employee_id');

        return $model->newQuery()
            ->with('employee', 'permission_by')
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
            ->setTableId('employee-loan-table')
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle table-sm')
            ->columns($this->getColumns())
            ->orderBy(9, 'desc')
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
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 11]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 11]]),
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
                ->title(localize('employee_name'))
                ->orderable(false),

            Column::make('permitted_by')
                ->title(localize('permitted_by'))
                ->orderable(false),

            Column::make('loan_no')
                ->title(localize('loan_no')),

            Column::make('amount')
                ->title(localize('amount')),

            Column::make('interest_rate')
                ->title(localize('interest_rate')),

            Column::make('installment_period')
                ->title(localize('installment_period')),

            Column::make('installment_cleared')
                ->title(localize('installment_cleared')),

            Column::make('repayment_amount')
                ->title(localize('repayment_amount')),

            Column::make('approved_date')
                ->title(localize('approved_date')),

            Column::make('repayment_start_date')
                ->title(localize('repayment_from')),

            Column::make('status')
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
        return 'EmployeesLoan_' . date('YmdHis');
    }
}
