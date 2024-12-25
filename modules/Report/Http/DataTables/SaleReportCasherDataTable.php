<?php

namespace Modules\Report\Http\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Carbon;
use Modules\Sale\Entities\Invoice;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SaleReportCasherDataTable extends DataTable
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
            ->filterColumn('customer_name', function ($query, $keyword) {
                $query->whereHas('customer', function ($query) use ($keyword) {
                    $query->where('customer_name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('invoice', function ($invoice) {
                return '<a href="javascript:void(0)" onclick="invoiceView(' . $invoice->id . ')" id="invoiceView-' . $invoice->id . '" class="text-primary" data-url="' . route('sale.details', $invoice->id) . '">' . $invoice->invoice . '</a>';
            })
            //order time
            ->addColumn('time', function ($invoice) {
                return date('h:i:s A', strtotime($invoice->created_at));
            })
            ->addColumn('customer_name', function ($invoice) {
                return '<a href="' . route('reports.redirect_sub_ledger_generate', ['acc_subcode_id' => $invoice->customer_id, 'type' => 'customer']) . '" target="_blank">' . ucwords($invoice->customer->customer_name) . '</a>';
            })
            //payment method
            ->addColumn('payment_method', function ($invoice) {
                //show all payment_name use bootstarp bags separate
                $payment_name = '';
                //use dynamic bg color
                $dynamic_bg = ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-dark'];
                if ($invoice->salePayment) {
                    $newKey = 0;
                    foreach ($invoice->salePayment as $key => $payment) {
                        //check key exist in dynamic_bg array count($dynamic_bg)
                        if ($key >= count($dynamic_bg)) {
                            $newKey = $key - count($dynamic_bg);
                        } else {
                            $newKey = $key;
                        }

                        if ($key != 0 && $key != count($invoice->salePayment)) {
                            $payment_name .= ',';
                        }
                        $payment_name .= '<span class="badge m-1 ' . $dynamic_bg[$newKey] . '">' . $payment->payment_name . '</span>';
                    }
                }
                return $payment_name;

            })
            //total amount
            ->editColumn('item_total', function ($invoice) {
                return bt_number_format($invoice->item_total);
            })

            //sale_tax
            ->editColumn('sale_tax', function ($invoice) {
                return bt_number_format($invoice->sale_tax);
            })

            //Expenses
            ->addColumn('expenses', function ($invoice) {
                $allExpenses = $invoice->expenses_amount_one + $invoice->expenses_amount_two + $invoice->expenses_amount_three + $invoice->expenses_amount_four;
                return bt_number_format($allExpenses);
            })

            //invoice_discount
            ->editColumn('invoice_discount', function ($invoice) {
                return bt_number_format($invoice->invoice_discount);
            })

            //all total
            ->addColumn('all_total', function ($invoice) {
                $allExpenses = $invoice->expenses_amount_one + $invoice->expenses_amount_two + $invoice->expenses_amount_three + $invoice->expenses_amount_four;
                $allTotal = $invoice->item_total + $invoice->sale_tax + $allExpenses - $invoice->invoice_discount;
                return bt_number_format($allTotal);
            })
            ->rawColumns(['customer_name', 'invoice', 'sale_tax', 'all_total', 'expenses', 'total_amount', 'due_amount', 'paid_amount', 'time', 'payment_method', 'item_total']);

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

        $sale_no = $this->request->get('sale_no');
        $customer_name = $this->request->get('customer_name');

        $req_order = $this->request->get('order');
        $dir = $req_order[0]['dir'];

        if ($this->request->get('order.0.column') == 0) {
            $this->request->merge(['order' => [['column' => '1', 'dir' => $dir]]]);
        }

        return $model->newQuery()
            ->with(['customer', 'salePayment'])
            ->where(function ($query) {
                $query->where('sale_type', 1)->orWhere('sale_type', 3);
            })
            ->when($from_date, function ($query) use ($from_date) {
                return $query->whereDate('date', '>=', $from_date);
            })
            ->when($to_date, function ($query) use ($to_date) {
                return $query->whereDate('date', '<=', $to_date);
            })
            ->when($sale_no, function ($query) use ($sale_no) {
                return $query->where('invoice', $sale_no);
            })
            ->when($customer_name, function ($query) use ($customer_name) {
                return $query->whereHas('customer', function ($query) use ($customer_name) {
                    $query->where('id', $customer_name);
                });
            })
            ->orderBy('invoice', orderByData($this->request->get('order')));
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        $span = "<span class='text-end d-block'>Total</span>";

        $print_admin_and_time = localize('print_date') . Carbon::now()->format('d-m-Y h:i:sa') . ", User: " . auth()->user()->full_name;
        return $this->builder()
            ->setTableId('sale-report-table')
            ->language([
                //change preloader icon
                'processing' => '<div class="lds-spinner">
                        <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
            ])
            ->setTableAttribute('class', 'table table-bordered table-hover w-100')
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
                            .column( 5 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                        var vat_tax = api
                            .column( 6 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                       var expenses = api
                            .column(7 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                       var discount = api
                            .column(8 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                       var total = api
                            .column(9 )
                            .data()
                            .reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );

                        total_order = bt_number_format(total_order);
                        vat_tax = bt_number_format(vat_tax);
                        expenses = bt_number_format(expenses);
                        discount = bt_number_format(discount);
                        total = bt_number_format(total);
                        $(api.column( 4).footer() ).html("' . $span . '");
                        $(api.column( 5 ).footer() ).html(total_order);
                        $(api.column( 6 ).footer() ).html(vat_tax);
                        $(api.column( 7 ).footer() ).html(expenses);
                        $(api.column( 8 ).footer() ).html(discount);
                        $(api.column( 9 ).footer() ).html(total);

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

                                //padding on body
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
                                        \'<h5 class="text-center">Sale Report Cashier</h5>\'+
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
            Column::make('date')
                ->title('Date')
                ->searchable(true)
                ->addClass('text-start'),
            Column::make('time')
                ->title('Time')
                ->searchable(true)
                ->addClass('text-start'),

            Column::make('invoice')
                ->title('Sale No')
                ->searchable(true)
                ->addClass('text-start'),

            Column::make('customer_name')
                ->title('Customer Name')
                ->searchable(true)
                ->addClass('text-start'),

            Column::make('payment_method')
                ->title('Payment Method')
                ->width('15%')
                ->searchable(true)
                ->addClass('text-start'),

            Column::make('item_total')
                ->title('Total Order (' . currency() . ')')
                ->searchable(true)
                ->addClass('text-end'),

            Column::make('sale_tax')
                ->title('VAT/TAX (' . currency() . ')')
                ->searchable(true)
                ->addClass('text-end'),

            Column::make('expenses')
                ->title('Expenses (' . currency() . ')')
                ->searchable(true)
                ->addClass('text-end'),

            Column::make('invoice_discount')
                ->title('Discount (' . currency() . ')')
                ->searchable(true)
                ->addClass('text-end'),

            Column::make('all_total')
                ->title('Total Amount (' . currency() . ')')
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
        return 'Sales-Report-' . date('YmdHis');
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
        doc.content[5].layout = objLayout;


        ';
    }

}
