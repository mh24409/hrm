<?php

namespace Modules\HumanResource\DataTables;

use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Modules\HumanResource\Entities\Employee;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\HumanResource\Entities\ManualAttendance;

class DailyPresentReportDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $query = (new EloquentDataTable($query))

            ->addIndexColumn()
            ->editColumn('employee_name', function ($query) {
                return $query->employee->full_name;
            })
            ->editColumn('employee_id', function ($query) {
                return $query->employee->employee_id;
            })
            ->editColumn('department_id', function ($query) {
                return $query->employee->department?->department_name;
            })
            ->editColumn('date', function ($query) {
                return Carbon::parse($query->date)->format('d-m-Y');
            })
            ->editColumn('in_time', function ($query) {
                return Carbon::parse($query?->in_time)->format('h:i:s A');
            })
            ->editColumn('out_time', function ($query) {
                return Carbon::parse($query?->out_time)->format('h:i:s A');
            })
            ->editColumn('status', function () {
                return localize('present');
            });

        return $query->escapeColumns([]);
    }

    /**
     * Get query source of dataTable.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ManualAttendance $model)
    {
        $department_id = $this->request->get('department_id');
        $date = $this->request->get('date');
        $query         = $model->newQuery()
            ->with(['employee' => function ($query) {
                $query->select('id', 'employee_id', 'first_name', 'middle_name', 'last_name', 'department_id')
                    ->with('department:id,department_name');
            }])
            ->selectRaw('DATE(time) as date, employee_id, TIME(MIN(time)) as in_time, TIME(MAX(time)) as out_time')
            ->whereDate('time', $date)
            ->when($department_id > 0, function ($query) use ($department_id) {
                $query->whereHas('employee', function ($query) use ($department_id) {
                    $query->where('department_id', $department_id);
                });
            })
            ->groupBy('employee_id', 'date');


        return $query;
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
            ->setTableId('daily-present-report-table')
            ->setTableAttribute('class', 'table table-hover table-bordered align-middle')
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
                    ->text('<i class="fa fa-file-csv"></i> CSV')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7]]),
                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->extend('excelHtml5')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7]]),
                Button::make('print')
                    ->className('btn btn-secondary buttons-print btn-sm prints')
                    ->text('<i class="fa fa-print"></i> Print')->exportOptions(['columns' => [0, 1, 2, 3, 4, 5, 6, 7]])
                    ->footer(false)
                    ->customize(
                        'function(win) {

                            $(win.document.body).css(\'padding\',\'20px\');
                            $(win.document.body).find(\'table\').addClass(\'print-table-border\',\'fs-10\');

                            //date range
                            var date_range = $(\'#date\').val();
                            if(date_range == \'\'){
                                date_range = \'All\';
                            }
                            //remove header
                            $(win.document.body).find(\'h1\').remove();


                           //add print date and time
                            $(win.document.body)
                                .prepend(
                                    \'<p class="fs-10 mb-0 pb-0">' . $print_admin_and_time . '</p>\'+
                                    \'<div class="text-center mt-0 pt-0"><img src="' . logo_64_data() . '" alt="logo" width="135"></div>\'+
                                    \'<p class="text-center fs-12 mt-0 pt-0">' . app_setting()->address . '</p>\'+
                                    \'<h5 class="text-center">Daily Attendance Report</h5>\'+
                                    \'<p class="text-end mb-0 fs-10">Date Range: \'+date_range+\'</p>\'
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
                ->searchable(false)
                ->orderable(false),

            Column::make('employee_id')
                ->title(localize('employee_id'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('employee_name')
                ->title(localize('name'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('department_id')
                ->title(localize('department'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('date')
                ->title(localize('date'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('in_time')
                ->title(localize('in_time'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('out_time')
                ->title(localize('out_time'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),

            Column::make('status')
                ->title(localize('status'))
                ->addClass('text-center')
                ->searchable(true)
                ->orderable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'DailyPresentReport_' . date('YmdHis');
    }

    //pdf table style
    private function pdfCustomizeTableHeader(): string
    {
        return '
             //row width
             doc.content[5].table.widths[2] = 100;

             //header colum css
             doc.content[5].table.body[0][0].alignment = \'center\';
             doc.content[5].table.body[0][1].alignment = \'center\';
             doc.content[5].table.body[0][2].alignment = \'center\';


             //change body colum css
             doc.content[5].table.body.forEach(function(row) {
                 row[0].alignment = \'center\';
                 row[1].alignment = \'center\';
                 row[2].alignment = \'center\';
                 row[3].alignment = \'center\';
                 row[4].alignment = \'center\';
                 row[5].alignment = \'center\';
                 row[6].alignment = \'center\';
                 row[7].alignment = \'right\';
                 row[8].alignment = \'right\';
             });

         ';
    }

    //pdf export design
    private function pdfDesign(): string
    {

        $print_admin_and_time = localize('print_date') . Carbon::now()->format('d-m-Y h:i:sa') . ", User: " . auth()->user()->full_name;
        return '
         //pdf margin
         doc.pageMargins = [15, 5, 15, 10];

         //admin name top of page left
         doc.content.splice(0, 0, {
             //left, top, right, bottom
             margin: [0, 0, 0, 0],
             alignment: \'left\',
             text: [
                 { text: \'' . $print_admin_and_time . '\', fontSize: 7, bold: false },
                 ]
             });



         //pdf header add logo
         doc.content.splice(1, 0, {
             margin: [0, 0, 0, 0],
             alignment: \'center\',
             width: 110,
              image: \'' . logo_64_data() . '\'
         });

         //pdf header add address
         doc.content.splice(2, 0, {
             //left, top, right, bottom
             margin: [0, 0, 0, 10],
             alignment: \'center\',
             text: [
                 { text: \'' . app_setting()->address . '\', fontSize: 8, bold: false },
                 ]
             });

         //page title size
         doc.content[3].text = "Daily Attendance Report";


         //date range
         var date_range = $(\'#sale_date\').val();
         if(date_range == \'\'){
             date_range = \'All\';
         }
         doc.content.splice(4, 0, {
             //left, top, right, bottom
             margin: [0, -10, 0, 5],
             alignment: \'right\',
             text: [
                 { text:""  },
                 ]
             });

         doc.content[5].table.widths = Array(doc.content[5].table.body[0].length + 1).join(\'*\').split(\'\');

         //change table font size && table fill color
         doc.content[5].table.body.forEach(function(row) {
             row.forEach(function(cell) {
                 cell.fontSize = 8;
                 cell.fillColor = \'#fff\';
             });
         });

         //change header text color
         doc.content[5].table.body[0].forEach(function(cell) {
             cell.color = \'#000\';
         });

         //change footer text color
         doc.content[5].table.body[doc.content[5].table.body.length - 1].forEach(function(cell) {
             cell.color = \'#000\';
             cell.alignment = \'right\';
         });

         var objLayout = {};
         objLayout[\'hLineWidth\'] = function(i) { return .5; };
         objLayout[\'vLineWidth\'] = function(i) { return .5; };
         objLayout[\'hLineColor\'] = function(i) { return \'#000\'; };
         objLayout[\'vLineColor\'] = function(i) { return \'#000\'; };
         objLayout[\'paddingLeft\'] = function(i) { return 4; };
         objLayout[\'paddingRight\'] = function(i) { return 4; };
         doc.content[5].layout = objLayout;';
    }
}
