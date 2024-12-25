<?php

namespace Modules\HumanResource\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\SalaryGenerate;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmployeesSalaryDataTable extends DataTable
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
                return ucwords($row->employee?->full_name ?? '');
            })
            ->filterColumn('employee_name', function ($query, $keyword) {
                $query->whereHas('employee', function ($query) use ($keyword) {
                    $query->where('first_name', 'like', "%{$keyword}%")
                        ->orWhere('last_name', 'like', "%{$keyword}%")
                        ->orWhere('middle_name', 'like', "%{$keyword}%");
                });
            })
            ->editColumn('net_salary', function ($row) {
                return number_format($row->net_salary, 2);
            })
            ->addColumn('action', function ($info) {
                if (auth()->user()->can('read_manage_employee_salary')) {
                    $button = '<a href="' . route('employee.payslip', $info->uuid) . '" class="btn btn-warning btn-sm me-1" title="' . localize('payslip') . '"><i class="fa fa-eye"></i> ' . localize('payslip') . '</a>';

                }
                if (auth()->user()->can('update_manage_employee_salary')) {
                    $button .= ' <a href="' . route('employee.payslip-pdf', $info->uuid) . '" class="btn btn-success  btn-sm me-1" title="' . localize('download_pay_slip') . '"><i class="fa fa-download"></i> ' . localize('download_pay_slip') . '</a>';

                }

                return $button;
            });

        return $query->escapeColumns([]);

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\EmployeeDocument $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(SalaryGenerate $model)
    {

        $salary_month = $this->request->get('salary_month');

        $salary_documents = $model->newQuery()
            ->with('employee')
            ->when($salary_month, function ($query) use ($salary_month) {
                return $query->where('salary_month_year', $salary_month);
            });
        return $salary_documents;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('employeesSalary-table')
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
                    ->text('<i class="fa fa-copy"></i> Copy')->exportOptions(['columns' => [0, 1, 2, 3]]),
                Button::make('csv')
                    ->className('btn btn-secondary buttons-csv buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3]]),
                Button::make('pdf')
                    ->className('btn btn-secondary buttons-pdf buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-pdf"></i> PDF')
                    ->extend('pdfHtml5')->exportOptions(['columns' => [0, 1, 2, 3]])
                    ->customize("function(doc) {
                        doc.styles.tableHeader.alignment = 'center';
                        doc.styles.tableBodyEven.alignment = 'center';
                        doc.styles.tableBodyOdd.alignment = 'center';
                        doc.styles.tableFooter.alignment = 'center';
                    }"),
                Button::make('print')
                    ->className('btn btn-secondary buttons-print btn-sm prints')
                    ->text('<i class="fa fa-print"></i> Print')->exportOptions(['columns' => [0, 1, 2, 3]])
                    ->customize("function (win) {
                        $(win.document.body).find('h1')
                            .css('text-align', 'left')
                            .css('margin-bottom', '20px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }"),
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
                ->title(localize('employee_name'))
                ->addClass('text-center'),

            Column::make('salary_month_year')
                ->title(localize('salary_month'))
                ->addClass('text-center'),

            Column::make('net_salary')
                ->title(localize('total_salary'))
                ->addClass('text-center'),

            Column::make('action')
                ->title(localize('action'))->addClass('column-sl')->orderable(false)
                ->width(300)
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
        return 'EmployeesSalary_' . date('YmdHis');
    }
}
