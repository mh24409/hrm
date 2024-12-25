<?php

namespace Modules\Report\Http\DataTables;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Accounts\Entities\FinancialYear;
use Yajra\DataTables\CollectionDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SupplierWiseSaleProfitReportDataTable extends DataTable
{
    public function dataTable(): CollectionDataTable
    {
        $query = (new CollectionDataTable($this->collection()))
            ->addIndexColumn()
            ->editColumn('profit', function ($row) {
                return bt_number_format($row->profit);
            })

            ->editColumn('sale_amount', function ($row) {
                return bt_number_format($row->sale_amount);
            })
            ->editColumn('cost_price', function ($row) {
                return bt_number_format($row->cost_price);
            })
            ->editColumn('sale_qty', function ($row) {
                return bt_number_format($row->sale_qty);
            })
            ->addColumn('sale_no', function ($row) {
                $data = '';
                if (isset($row->invoice)) {
                    $data .= '<a href="javascript:void(0)" onclick="invoiceView(' . $row->inv_id . ')" id="invoiceView-' . $row->inv_id . '" class="text-primary" data-url="' . route('sale.details', $row->inv_id) . '">' . $row->invoice . '</a>';
                } else {
                    $data .= '';
                }

                return $data;
            })
            ->rawColumns(['sale_no']);
        return $query->escapeColumns([]);
    }

    public function collection()
    {
        $supplier_id = $this->request->get('p_supplier');
        if ($supplier_id && $supplier_id != 'null' && $supplier_id != 'undefined' && $supplier_id != '') {
            $supplier_id = "where p.supplier_id = $supplier_id";
        } else {
            $supplier_id = "";
        }

        $date = false;
        $from_date = $this->request->get('from_date');
        if ($from_date && $from_date != 'null' && $from_date != 'undefined' && $from_date != '') {
            $from_date = date('Y-m-d', strtotime($from_date));
            $date = true;
        }
        $to_date = $this->request->get('to_date');
        if ($to_date && $to_date != 'null' && $to_date != 'undefined' && $to_date != '') {
            $to_date = date('Y-m-d', strtotime($to_date));
            $date = true;
        }
        if ($date) {
            $toDate = $from_date;
            $formDate = $to_date;
        } else {

            $fiscalYear = FinancialYear::where('status', true)->where('is_close', false)->first();
            $toDate = Carbon::parse($fiscalYear->start_date)->format('Y-m-d');
            $formDate = Carbon::parse($fiscalYear->end_date)->format('Y-m-d');
        }

        $sale_report = $this->request->get('sale_report');
        $reportDayWise = false;
        if ($sale_report != 'null' && $sale_report != 'undefined' && $sale_report != '') {
            if ($sale_report == 'day_wise') {
                $reportDayWise = true;
                session()->put('reportDayWise', true);
            }
        }

        if ($reportDayWise == false) {
            $data = DB::select("SELECT category_id, category_name, product_name, unit_name, inv_id, invoice, date, sum(sale_qty) sale_qty, sum(sale_amount) sale_amount, sum(cost_price) cost_price,
                sum(sale_amount-cost_price) as profit, supplier_name, supplier_mobile, supplier_address
                from (
                SELECT p.category_id, p.product_name , c.category_name, u.unit_name, sp.id as inv_id, sp.invoice, sp.date, sum(sp.sale_qty) sale_qty, sum(sp.sale_amount) sale_amount, sum(p.cost * sp.sale_qty) cost_price, s.supplier_name as supplier_name, s.mobile as supplier_mobile, s.address as supplier_address
                FROM `product_information`  p
                left join suppliers s on p.supplier_id = s.id
                left join categories c on p.category_id = c.id
                left join unit_of_measurements u on p.UOM_id = u.id
                left join (
                    SELECT i.`id`, i.`invoice`, i.`date` , d.product_id, p.supplier_id,sum(d.quantity) sale_qty, sum(d.quantity  * d.rate) as sale_amount
                    FROM `invoices` i
                    left join invoice_details d on i.id = d.invoice_id
                    left join product_information p on d.product_id = p.id
                    WHERE date between '$toDate' and '$formDate'
                    and i.invoice > 0
                    Group by  i.`invoice`, i.`date` , d.product_id, p.supplier_id
                ) sp
                on p.id = sp.product_id and p.supplier_id = sp.supplier_id
                $supplier_id
                group by p.category_id, p.product_name , u.unit_name ,sp.invoice, sp.date
                ) a
                group by category_id, product_name , unit_name,  invoice, date");
        } else {
            $data = DB::select("SELECT category_id, category_name, product_name , unit_name, date, sum(sale_qty) sale_qty, sum(sale_amount) sale_amount, sum(cost_price) cost_price,
                sum(sale_amount-cost_price) as profit, supplier_name, supplier_mobile, supplier_address
                from (
                SELECT p.category_id, p.product_name , c.category_name, u.unit_name, sp.id as inv_id, sp.invoice, sp.date, sum(sp.sale_qty) sale_qty, sum(sp.sale_amount) sale_amount, sum(p.cost * sp.sale_qty) cost_price, s.supplier_name as supplier_name, s.mobile as supplier_mobile, s.address as supplier_address
                FROM `product_information`  p
                left join suppliers s on p.supplier_id = s.id
                left join categories c on p.category_id = c.id
                left join unit_of_measurements u on p.UOM_id = u.id
                left join (
                    SELECT i.`invoice`, i.`date` , d.product_id, p.supplier_id,sum(d.quantity) sale_qty, sum(d.quantity  * d.rate) as sale_amount
                    FROM `invoices` i
                    left join invoice_details d on i.id = d.invoice_id
                    left join product_information p on d.product_id = p.id
                    WHERE date between '$toDate' and '$formDate'
                    and i.invoice > 0
                    Group by  i.`invoice`, i.`date` , d.product_id, p.supplier_id
                ) sp
                on p.id = sp.product_id and p.supplier_id = sp.supplier_id
                $supplier_id
                group by p.category_id, p.product_name , u.unit_name ,sp.invoice, sp.date
                ) a
                group by category_id, product_name , unit_name,  date");
        }

        $data = collect($data);
        return $data;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        $print_admin_and_time = localize('print_date') . Carbon::now()->format('d-m-Y h:i:sa') . ", User: " . auth()->user()->full_name;
        $supplier_name = ''; // Initialize an empty variable to store the supplier name
        $supplier_mobile = ''; // Initialize an empty variable to store the supplier mobile
        $supplier_address = ''; // Initialize an empty variable to store the supplier address

        // Check if the supplier_id parameter is set in the request
        $data = $this->collection();
        if (count($data) > 0) {
            $supplier_name = $data[0]?->supplier_name;
            $supplier_mobile = $data[0]?->supplier_mobile;
            $supplier_address = $data[0]?->supplier_address;
        }

        return $this->builder()
            ->setTableId('supplier_wise_sale_profit')
            ->setTableAttribute('class', 'table table-bordered table-hover w-100 align-middle')
            ->responsive()
            ->language([
                //change preloader icon
                'processing' => '<div class="lds-spinner">
                <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>',
            ])
            ->columns($this->getColumns())
            ->lengthMenu([[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']])
            ->dom("<'row mb-3'<'col-md-4'l><'col-md-4 py-2 text-center'B><'col-md-4'f>>rt<'bottom'<'row'<'col-md-6'i><'col-md-6'p>>><'clear'>")
            ->footerCallback('function ( row, data, start, end, display ) {
                var api = this.api(), data;
                var intVal = function ( i ) {
                    return typeof i === \'string\' ?
                        i.replace(/[\$,]/g, \'\')*1 :
                        typeof i === \'number\' ?
                            i : 0;
                };
               var qty = api
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
               var sale_amount = api
                    .column( 7 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
               var cost_price = api
                    .column( 8 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

               var profit = api
                    .column( 9 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                sale_amount = bt_number_format(sale_amount);
                cost_price = bt_number_format(cost_price);
                profit = bt_number_format(profit);
                $(api.column( 5).footer() ).html(`<span class="text-end d-block">Total</span>`);
                $(api.column( 6 ).footer() ).html(bt_number_format(qty));
                $(api.column( 7 ).footer() ).html(sale_amount);
                $(api.column( 8 ).footer() ).html(cost_price);
                $(api.column( 9 ).footer() ).html(profit);
            }')
            ->buttons([

                Button::make('excel')
                    ->className('btn btn-secondary buttons-excel buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-excel"></i> Excel'),
                Button::make('pdf')
                    ->className('btn btn-secondary buttons-pdf buttons-html5 btn-sm prints')
                    ->text('<i class="fa fa-file-pdf"></i> PDF')
                    ->footer(true)
                    ->customize(
                        'function(doc) {
                                ' . $this->pdfDesign() . '
                                ' . $this->pdfCustomizeTableHeader() . '
                            }'
                    ),
                Button::make('print')
                    ->className('btn btn-secondary buttons-print btn-sm prints')
                    ->text('<i class="fa fa-print"></i> Print')
                    ->footer(true)
                    ->customize(
                        'function(win) {
                            console.log(win)
                                $(win.document.body).css(\'padding\',\'0px\');
                                $(win.document.body).find(\'table\').addClass(\'print-table-border\');

                                // date range
                                var date_range = $(\'.custom-date-range-supplier-wise-sale\').val();
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
                                        \'<h5 class="text-center">Supplier Wise Sale Profit</h5>\'+
                                        \'<h6 class="text-center mb-0 pb-0 ">' . $supplier_name . ' (' . $supplier_mobile . ')' . '</h6>\'+
                                        \'<p class="fs-12 mb-0 pb-0 text-center">' . $supplier_address . '</p>\'+
                                        \'<p class="text-end mb-0 fs-10">Date Range: \'+date_range+\'</p>\'
                                    );

                            }'
                    ),
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
            Column::make('category_name')
                ->title(localize('category_name')),
            Column::make('product_name')
                ->title(localize('product_name')),

            Column::make('unit_name')
                ->addClass('text-center')
                ->title(localize('unit_name')),
            Column::make('sale_no')
                ->addClass('text-center')
                ->title(localize('sale_no')),
            Column::make('date')
                ->addClass('text-center')
                ->title(localize('date')),

            Column::make('sale_qty')
                ->addClass('text-center')
                ->title(localize('sale_qty')),

            Column::make('sale_amount')
                ->addClass('text-end')
                ->title(localize('sale_amount')),

            Column::make('cost_price')
                ->addClass('text-end')
                ->title(localize('cost_price')),

            Column::make('profit')
                ->addClass('text-end')
                ->title(localize('profit')),
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
            doc.content[5].table.widths[0] = 25;
            doc.content[5].table.widths[2] = 120;
            doc.content[5].table.widths[3] = 25;
            doc.content[5].table.widths[4] = 30;
            doc.content[5].table.widths[5] = 40;
            doc.content[5].table.widths[6] = 25;

            //header colum css
            doc.content[5].table.body[0][0].alignment = \'center\';
            doc.content[5].table.body[0][1].alignment = \'left\';
            doc.content[5].table.body[0][2].alignment = \'center\';
            doc.content[5].table.body[0][3].alignment = \'center\';
            doc.content[5].table.body[0][4].alignment = \'center\';
            doc.content[5].table.body[0][5].alignment = \'center\';
            doc.content[5].table.body[0][6].alignment = \'center\';
            doc.content[5].table.body[0][7].alignment = \'left\';
            doc.content[5].table.body[0][8].alignment = \'left\';
            doc.content[5].table.body[0][9].alignment = \'left\';


            //change body colum css
            doc.content[5].table.body.forEach(function(row) {
                row[0].alignment = \'center\';
                row[1].alignment = \'left\';
                row[2].alignment = \'center\';
                row[3].alignment = \'center\';
                row[4].alignment = \'center\';
                row[5].alignment = \'center\';
                row[6].alignment = \'center\';
                row[7].alignment = \'right\';
                row[8].alignment = \'right\';
                row[9].alignment = \'right\';
            });

            //change footer css
            doc.content[5].table.body[doc.content[5].table.body.length - 1].forEach(function(cell) {
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
                { text: \'\', fontSize: 7, bold: false },
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
                { text: "", fontSize: 8, bold: false },
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
