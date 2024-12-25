<?php

namespace Modules\HumanResource\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\EmployeeDocs;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmployeeDocumentsDataTable extends DataTable
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

            ->addColumn('branch', function ($doc) {
                return $doc->employee?->branch?->branch_name;
            })
            ->filterColumn('branch', function ($query, $keyword) {
                $query->whereHas('employee', function ($query) use ($keyword) {
                    $query->whereHas('branch', function ($query) use ($keyword) {
                        $query->where('branch_name', 'like', "%{$keyword}%");
                    });
                });
            })

            ->addColumn('employee', function ($doc) {
                return $doc->employee?->full_name;
            })
            ->filterColumn('employee', function ($query, $keyword) {
                $query->whereHas('employee', function ($query) use ($keyword) {
                    $query->where('first_name', 'like', "%{$keyword}%")
                        ->orWhere('last_name', 'like', "%{$keyword}%")
                        ->orWhere('middle_name', 'like', "%{$keyword}%");
                });
            })

            ->editColumn('file', function ($doc) {
                return '<a href="' . asset('storage/' . $doc->file_path) . '" target="_blank"><i class="far fa-file fa-lg fs-20"></i></a>';
            })

            ->setRowClass(function ($doc) {
                if (check_expiry($doc->expiry_date)) {
                    return 'alert-danger';
                } else if (check_expiry($doc->expiry_date, 30)) {
                    return 'alert-warning';
                }
            });

        return $query->escapeColumns([]);

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Modules\HumanResource\Entities\EmployeeDocs $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(EmployeeDocs $model)
    {

        $fromDate = '';
        $toDate = '';

        $employee_id = $this->request->get('employee_id');
        $daterange = $this->request->get('daterange');

        if ($daterange) {
            $string = explode('-', $daterange);
            $fromDate = date('Y-m-d', strtotime($string[0]));
            $toDate = date('Y-m-d', strtotime($string[1]));
        }

        $employee_documents = $model->newQuery()
            ->when($daterange, function ($query) use ($fromDate, $toDate) {
                return $query->whereBetween('expiry_date', [$fromDate, $toDate]);
            })
            ->when($employee_id, function ($query) use ($employee_id) {
                return $query->where('employee_id', $employee_id);
            });

        return $employee_documents;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('employeedocuments-table')
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle')
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
                    ->text('<i class="fa fa-copy"></i> Copy'),
                Button::make('csv')
                    ->className('btn btn-secondary buttons-csv buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 5]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 5]]),
                Button::make('pdf')
                    ->className('btn btn-secondary buttons-pdf buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-pdf"></i> PDF')
                    ->extend('pdfHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 5]]),
                Button::make('print')
                    ->className('btn btn-secondary buttons-print btn-sm prints')
                    ->text('<i class="fa fa-print"></i> Print')->exportOptions(['columns' => [0, 1, 2, 3, 5]]),
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

            Column::make('branch')
                ->title(localize('branch')),

            Column::make('employee')
                ->title(localize('employee_name')),

            Column::make('doc_title')
                ->title(localize('doc_title')),

            Column::make('file')
                ->title(localize('file'))
                ->searchable(false)
                ->orderable(false),

            Column::make('expiry_date')
                ->title(localize('expiry_date')),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'EmployeeDocuments_' . date('YmdHis');
    }
}
