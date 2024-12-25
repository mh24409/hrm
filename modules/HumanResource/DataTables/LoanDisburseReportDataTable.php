<?php

namespace Modules\HumanResource\DataTables;

use App\Traits\ReportLayoutTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\Employee;
use Modules\HumanResource\Entities\Loan;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class LoanDisburseReportDataTable extends DataTable
{
    use ReportLayoutTrait;

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
            ->addColumn('designation', function ($row) {
                return $row->employee?->position?->position_name;
            })
            ->addColumn('employee_code', function ($row) {
                return $row->employee?->employee_id;
            })
            ->addColumn('joinning_date', function ($row) {
                return $row->employee?->joinning_date;
            })

            ->filterColumn('employee_name', function ($query, $keyword) {
                $query->whereHas('employee', function ($query) use ($keyword) {
                    $query->where('first_name', 'like', "%{$keyword}%")
                        ->orWhere('last_name', 'like', "%{$keyword}%")
                        ->orWhere('middle_name', 'like', "%{$keyword}%");
                });
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\EmployeeDocument $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Loan $model)
    {
        $employee_id = $this->request->get('employee_id');
        $purchase_date = $this->request->get('purchase_date');

        return $model->newQuery()->with(['employee' => function ($q) {
            $q->with('position');
        }, 'permission_by'])
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
        $print_admin_and_time = localize('print_date') . Carbon::now()->format('d-m-Y h:i:sa') . ", User: " . auth()->user()->full_name;
        return $this->builder()
            ->setTableId('employee-loan-table')
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
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]]),
                Button::make('print')
                    ->className('btn btn-secondary buttons-print btn-sm prints')
                    ->text('<i class="fa fa-print"></i> Print')
                    ->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]])
                    ->customize(
                        'function(win) {

                            $(win.document.body).css(\'padding\',\'0px\');
                            $(win.document.body).find(\'table\').addClass(\'print-table-border\',\'fs-10\');

                            //remove header
                            $(win.document.body).find(\'h1\').remove();


                           //add print date and time
                            $(win.document.body)
                                .prepend(
                                    \'<p class="fs-10 mb-0 pb-0">' . $print_admin_and_time . '</p>\'+
                                    \'<div class="text-center mt-0 pt-0"><img src="' . logo_64_data() . '" alt="logo" width="135"></div>\'+
                                    \'<p class="text-center fs-12 mt-0 pt-0">' . app_setting()->address . '</p>\'+
                                    \'<h5 class="text-center">Loan Disburse Report</h5>\'
                                );

                        }'
                    ),
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

            Column::make('designation')
                ->title(localize('designation')),

            Column::make('employee_code')
                ->title(localize('employee_code')),

            Column::make('joinning_date')
                ->title(localize('joining_date')),

            Column::make('loan_no')
                ->title(localize('loan_no')),

            Column::make('approved_date')
                ->title(localize('loan_approved_date')),

            Column::make('amount')
                ->title(localize('loan_amount')),

            Column::make('installment_period')
                ->title(localize('no_of_installment')),

            Column::make('repayment_amount')
                ->title(localize('repayment_amount')),
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
