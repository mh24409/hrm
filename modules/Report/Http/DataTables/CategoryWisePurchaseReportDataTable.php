<?php

namespace Modules\Report\Http\DataTables;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Modules\Purchase\Entities\ReceiveDetail;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CategoryWisePurchaseReportDataTable extends DataTable
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

            ->addColumn('receive_date', function ($row) {
                return $row->receive_date;
            })
            ->addColumn('supplier_name', function ($row) {
                return ucwords($row->receive?->supplier?->supplier_name);
            })

            ->addColumn('category_name', function ($row) {
                return $row->product?->category?->category_name;
            })
            ->addColumn('brand_name', function ($row) {
                return $row->product?->brand?->brand_name;
            })
            ->addColumn('product_name', function ($row) {
                return $row->product->product_name;
            })
            ->addColumn('model', function ($row) {
                return $row->product?->product_model;
            })
            ->addColumn('quantity', function ($row) {
                return bt_number_format($row->total_quantity);
            })
            ->addColumn('price', function ($row) {
                return bt_number_format($row->average_price);
            })
            ->addColumn('amount', function ($row) {
                return bt_number_format($row->total_amount);
            })

            ->rawColumns(['category_name', 'supplier_name', 'brand_name', 'product_name', 'model', 'quantity', 'price', 'amount']);

    }

    /**
     * Get query source of dataTable.
     */
    public function query(ReceiveDetail $model): QueryBuilder
    {

        $receive_date = $this->request->get('receive_date');
        $category_id = $this->request->get('category_id');
        $brand_id = $this->request->get('brand_id');
        $product_id = $this->request->get('product_id');
        $supplier_id = $this->request->get('supplier_id');
        $product_model = $this->request->get('product_model');

        $req_order = $this->request->get('order');
        $dir = $req_order[0]['dir'];

        if ($this->request->get('order.0.column') == 0) {
            $this->request->merge(['order' => array(array('column' => '1', 'dir' => $dir))]);
        }

        return $model->newQuery()
            ->with(['receive', 'product' => function ($query) {
                $query->with(['category', 'brand']);
            },
                'receive' => function ($query) {
                    $query->with(['supplier']);
                }])
            ->when($supplier_id, function ($query) use ($supplier_id) {
                return $query->whereHas('receive.supplier', function ($query) use ($supplier_id) {
                    $query->where('id', $supplier_id);
                });
            })
            ->when($category_id, function ($query) use ($category_id) {
                return $query->whereHas('product.category', function ($query) use ($category_id) {
                    $query->where('id', $category_id);
                });
            })
            ->when($brand_id, function ($query) use ($brand_id) {
                return $query->whereHas('product.brand', function ($query) use ($brand_id) {
                    $query->where('id', $brand_id);
                });
            })
            ->when($product_id, function ($query) use ($product_id) {
                $query->where('product_id', $product_id);
            })
            ->when($product_model, function ($query) use ($product_model) {
                return $query->whereHas('product', function ($query) use ($product_model) {
                    $query->where('id', $product_model);
                });
            })

            ->when($receive_date, function ($query) use ($receive_date) {
                $string = explode('-', $receive_date);
                if ($receive_date) {
                    $fromDate = date('Y-m-d', strtotime($string[0]));
                    $toDate = date('Y-m-d', strtotime($string[1]));
                } else {
                    $fromDate = '';
                    $toDate = '';
                }
                return $query->whereBetween('receive_date', [$fromDate, $toDate]);
            })
            ->groupBy(['receive_date', 'product_id'])
            ->select([
                'receive_date',
                'product_id',
                'receive_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('(SUM(quantity*rate)/SUM(quantity)) as average_price'),
                DB::raw('SUM(quantity*rate) as total_amount'),
            ])
            ->orderBy('receive_date', orderByData($this->request->get('order')));
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
            ->setTableId('category-wise-purchase-report-table')
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
                       var quantity = api
                            .column( 7 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                        var price = api
                            .column( 8 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                       var amount = api
                            .column( 9 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        quantity = quantity;
                        price = bt_number_format(price);
                        amount = bt_number_format(amount);
                        $(api.column( 6).footer() ).html(`<span class="text-end d-block">Total</span>`);
                        $(api.column( 7 ).footer() ).html(quantity);
                        $(api.column( 8 ).footer() ).html(price);
                        $(api.column( 9 ).footer() ).html(amount);
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

                                //padding on body
                                $(win.document.body).css(\'font-family\',\'Arial\');

                                $(win.document.body).find(\'table\').addClass(\'print-table-border\');

                                //date range
                                var date_range = $(\'#receive_date\').val();
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
                                        \'<h5 class="text-center">Category Wise Purchase Report</h5>\'+
                                        \'<p class="text-end mb-0 fs-10">Date Range: \'+date_range+\'</p>\'
                                    );

                                //add print page number
                                $(win.document.body).find(\'div.footer\').prepend(\'<p id="page"></p>\');
                                $(win.document.body).find(\'div.footer\').prepend(\'<p class="text-end mb-0 fs-10">Page: <span></span></p>\');
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
                ->orderable(false),

            Column::make('receive_date')
                ->title(localize('receive_date')),
            Column::make('supplier_name')
                ->title(localize('supplier_name')),
            Column::make('category_name')
                ->title(localize('category_name')),
            Column::make('brand_name')
                ->title(localize('brand_name')),
            Column::make('product_name')
                ->title(localize('product_name')),
            Column::make('model')
                ->title(localize('model')),
            Column::make('quantity')
                ->title(localize('quantity'))
                ->addClass('text-end'),
            Column::make('price')
                ->title(localize('price') . ' (' . currency() . ')')
                ->addClass('text-end')
                ->searchable(false),
            Column::make('amount')
                ->title(localize('amount') . ' (' . currency() . ')')
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
        var date_range = $(\'#receive_date\').val();
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
