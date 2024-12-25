<?php

namespace Modules\Report\Http\DataTables;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Sale\Entities\Invoice;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DayWiseSalesReportDataTable extends DataTable
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
            ->editColumn('total_amount', function ($invoice) {
                return bt_number_format($invoice->total_amount);
            })
            ->editColumn('due_amount', function ($invoice) {
                return bt_number_format($invoice->due_amount);
            })
            ->editColumn('paid_amount', function ($invoice) {
                return bt_number_format($invoice->paid_amount);
            })
            ->rawColumns(['total_amount', 'due_amount', 'paid_amount']);

    }

    /**
     * Get query source of dataTable.
     */
    public function query(Invoice $model): QueryBuilder
    {

        $from_date = $this->request->get('from_date');
        if ($from_date) {
            $from_date = date('Y-m-d', strtotime($from_date));
        }
        $to_date = $this->request->get('to_date');
        if ($to_date) {
            $to_date = date('Y-m-d', strtotime($to_date));
        }

        $req_order = $this->request->get('order');
        $dir = $req_order[0]['dir'];

        if ($this->request->get('order.0.column') == 0) {
            $this->request->merge(['order' => array(array('column' => '1', 'dir' => $dir))]);
        }

        return $model->newQuery()
            ->where(function ($query) {
                $query->where('sale_type', 1)->orWhere('sale_type', 3);
            })
            ->when($from_date, function ($query) use ($from_date) {
                return $query->whereDate('date', '>=', $from_date);
            })
            ->when($to_date, function ($query) use ($to_date) {
                return $query->whereDate('date', '<=', $to_date);
            })
        //sum of total amount
            ->selectRaw('date, sum(total_amount) as total_amount, sum(paid_amount) as paid_amount, sum(due_amount) as due_amount ,count(id) as total_order')
            ->groupBy('date')
            ->orderBy('date', orderByData($this->request->get('order')));

    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {

        $print_admin_and_time = localize('print_date') . Carbon::now()->format('d-m-Y h:i:sa') . ", User: " . auth()->user()->full_name;
        $span = "<span class='text-end d-block'>Total</span>";
        return $this->builder()
            ->setTableId('day-wise-sale-report-table')
            ->setTableAttribute('class', 'table table-bordered table-hover w-100')
            ->language([
                //change preloader icon
                'processing' => '<div class="lds-spinner">
                        <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
            ])
            ->columns($this->getColumns())
            ->lengthMenu([[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']])
            ->footerCallback('function ( row, data, start, end, display ) {
                        var api = this.api(), data;
                        var intVal = function ( i ) {
                            return typeof i === \'string\' ?
                                i.replace(/[\$,]/g, \'\')*1 :
                                typeof i === \'number\' ?
                                    i : 0;
                        };
                      var total_order = api
                             .column( 1 )
                             .data()
                             .reduce( function (a, b) {
                                 return intVal(a) + intVal(b);
                             }, 0 );
                       var total = api
                            .column( 2 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );


                       var paid_amount = api
                            .column( 3 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                       var due_amount = api
                            .column(4 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        total = bt_number_format(total);
                        paid_amount = bt_number_format(paid_amount);
                        due_amount = bt_number_format(due_amount);
                        $(api.column( 0).footer() ).html("' . $span . '");
                        $(api.column( 1 ).footer() ).html(total_order);
                        $(api.column( 2 ).footer() ).html(total);
                        $(api.column( 3 ).footer() ).html(paid_amount);
                        $(api.column( 4 ).footer() ).html(due_amount);
                    }')
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 py-2 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            ->rowReorder(true)
            ->select(true)
            ->buttons([

                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->footer(true),
                Button::make('pdf')
                    ->className('btn btn-secondary buttons-pdf buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-pdf"></i> PDF')
                    ->pageSize('A4')
                    ->customize(
                        'function(doc) {
                                ' . $this->pdfDesign() . '
                                ' . $this->pdfCustomizeTableHeader() . '
                            }'
                    )
                    ->footer(true),
                Button::make('print')
                    ->className('btn btn-secondary buttons-print btn-sm prints')
                    ->text('<i class="fa fa-print"></i> Print')
                    ->customize(
                        'function(win) {

                                $(win.document.body).css(\'padding\',\'0px\');
                                $(win.document.body).find(\'table\').addClass(\'print-table-border\',\'fs-10\');

                                //date range
                                var date_range = $(\'#sale_date\').val();
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
                                        \'<h5 class="text-center">Day Wise Sales Report</h5>\'+
                                        \'<p class="text-end mb-0 fs-10">Date Range: \'+date_range+\'</p>\'
                                    );

                            }'
                    )
                    ->footer(true),
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
            Column::make('date')
                ->title('Date')
                ->searchable(true)
                ->addClass('text-start'),
            Column::make('total_order')
                ->title('Total Sale')
                ->searchable(false)
                ->addClass('text-end'),
            Column::make('total_amount')
                ->title('Total Amount (' . currency() . ')')
                ->searchable(false)
                ->addClass('text-end'),
            Column::make('paid_amount')
                ->title('Paid Amount (' . currency() . ')')
                ->searchable(false)
                ->addClass('text-end'),
            Column::make('due_amount')
                ->title('Due Amount (' . currency() . ')')
                ->searchable(false)
                ->addClass('text-end'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Quotation_' . date('YmdHis');
    }

    //pdf table style
    private function pdfCustomizeTableHeader(): string
    {
        return '
            //row width
            doc.content[5].table.widths[2] = 100;

            //header colum css
            doc.content[5].table.body[0][0].alignment = \'left\';
            doc.content[5].table.body[0][1].alignment = \'left\';
            doc.content[5].table.body[0][2].alignment = \'left\';


            //change body colum css
            doc.content[5].table.body.forEach(function(row) {
                row[0].alignment = \'left\';
                row[1].alignment = \'left\';
                row[2].alignment = \'left\';
                row[3].alignment = \'right\';
                row[4].alignment = \'right\';
            });

            //change footer css
            doc.content[5].table.body[doc.content[5].table.body.length - 1].forEach(function(cell) {
                cell.alignment = \'right\';
                cell.bold = true;
                cell.color = \'#000\';
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
                { text: \'Date: \'+date_range, fontSize: 8, bold: false },
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
