<?php

namespace Modules\Report\Http\DataTables;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Modules\Purchase\Entities\Purchase;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StockReportByProductCategoryDataTable extends DataTable
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

            ->addColumn('supplier_name', function ($purchase) {
                return ucwords($purchase->supplier->supplier_name);
            })

            ->filterColumn('supplier_name', function ($query, $keyword) {
                $query->whereHas('supplier', function ($query) use ($keyword) {
                    $query->where('supplier_name', 'like', "%{$keyword}%");
                });
            })

            ->editColumn('purchase_no', function ($purchase) {
                return $purchase->purchase_no;
            })

            ->addColumn('sale_price', function ($row) {
                return number_format($row->price, 2) ?? 0.00;
            })
            ->addColumn('purchase_price', function ($row) {
                return number_format($row->cost, 2) ?? 0.00;
            })

            ->editColumn('grand_total_amount', function ($purchase) {
                return number_format($purchase->grand_total_amount, 2);
            })

            ->editColumn('due_amount', function ($purchase) {
                return number_format($purchase->due_amount, 2);
            })
            ->editColumn('paid_amount', function ($purchase) {
                return number_format($purchase->paid_amount, 2);
            })

            ->rawColumns(['supplier_name', 'sale_price', 'purchase_price', 'grand_total_amount', 'due_amount', 'paid_amount']);

    }

    /**
     * Get query source of dataTable.
     */
    public function query(Purchase $model): QueryBuilder
    {
        $purchase_no = $this->request->get('purchase_no');
        $supplier_name = $this->request->get('supplier_name');
        $purchase_date = $this->request->get('purchase_date');

        $req_order = $this->request->get('order');
        $dir = $req_order[0]['dir'];

        if ($this->request->get('order.0.column') == 0) {
            $this->request->merge(['order' => array(array('column' => '1', 'dir' => $dir))]);
        }

        return $model->newQuery()
            ->with(['supplier'])

            ->when($supplier_name, function ($query) use ($supplier_name) {
                return $query->whereHas('supplier', function ($query) use ($supplier_name) {
                    $query->where('id', $supplier_name);
                });
            })

            ->when($purchase_no, function ($query) use ($purchase_no) {
                return $query->where('purchase_no', $purchase_no);
            })
            ->when($purchase_date, function ($query) use ($purchase_date) {
                $string = explode('-', $purchase_date);
                if ($purchase_date) {
                    $fromDate = date('Y-m-d', strtotime($string[0]));
                    $toDate = date('Y-m-d', strtotime($string[1]));
                } else {
                    $fromDate = '';
                    $toDate = '';
                }
                return $query->whereBetween('purchase_date', [$fromDate, $toDate]);
            })
            ->orderBy('purchase_no', orderByData($this->request->get('order')));
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {

        $print_admin_and_time = localize('print_date') . Carbon::now()->format('d-m-Y h:i:sa') . ", User: " . auth()->user()->full_name;
        return $this->builder()
            ->setTableId('purchase-report-table')
            ->setTableAttribute('class', 'table table-bordered table-hover w-100')
            ->responsive()
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
                       var total = api
                            .column( 4 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                        var paid_amount = api
                            .column( 5 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                       var due_amount = api
                            .column(6 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        total = total.toFixed(2);
                        paid_amount = paid_amount.toFixed(2);
                        due_amount = due_amount.toFixed(2);
                        $(api.column( 3).footer() ).html(`<span class="text-end d-block">Total</span>`);
                        $(api.column( 4 ).footer() ).html(total);
                        $(api.column( 5 ).footer() ).html(paid_amount);
                        $(api.column( 6 ).footer() ).html(due_amount);
                    }')
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 py-2 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            ->buttons([

                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel')
                    ->footer(true),
                Button::make('pdf')
                    ->className('btn btn-secondary buttons-pdf buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-pdf"></i> PDF')
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

                                $(win.document.body).css(\'font-family\',\'Arial\');
                                $(win.document.body).find(\'table\').addClass(\'print-table-border\');

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
                                        \'<h5 class="text-center">Stock Report (Product Category)</h5>\'+
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
            Column::make('DT_RowIndex')
                ->title(localize('sl'))
                ->addClass('text-center column-sl')
                ->searchable(false)
                ->orderable(true),

            Column::make('purchase_no')
                ->title(localize('purchase_no')),

            Column::make('purchase_date')
                ->title(localize('purchase_date')),

            Column::make('sale_price')
                ->title(localize('sale_price')),

            Column::make('purchase_price')
                ->title(localize('purchase_price')),

            Column::make('supplier_name')
                ->title(localize('supplier_name')),

            Column::make('grand_total_amount')
                ->title(localize('total_amount'))
                ->addClass('text-end')
                ->searchable(false),

            Column::make('paid_amount')
                ->title(localize('paid_amount'))
                ->addClass('text-end')
                ->searchable(false),

            Column::make('due_amount')
                ->title(localize('due_amount'))
                ->addClass('text-end')
                ->searchable(false),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Purchase-Report-' . date('YmdHis');
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
                row[5].alignment = \'right\';
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
        var date_range = $(\'#stock-report-range\').val();
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
