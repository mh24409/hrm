<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Accounts\Entities\FinancialYear;
use Modules\Report\Http\DataTables\AlertProductQtyDataTable;
use Modules\Report\Http\DataTables\CategoryWiseSalesReportDataTable;
use Modules\Report\Http\DataTables\DayWisePurchasesReportDataTable;
use Modules\Report\Http\DataTables\DayWiseSalesReportDataTable;
use Modules\Report\Http\DataTables\SaleDueReportDataTable;
use Modules\Report\Http\DataTables\SaleReportCasherDataTable;
use Modules\Report\Http\DataTables\SaleReportDataTable;
use Modules\Report\Http\DataTables\SaleReturnReportDataTable;
use Modules\Report\Http\DataTables\SupplierWiseSaleProfitReportDataTable;
use Modules\Report\Http\DataTables\UnDeliveredSaleReportDataTable;
use Modules\Report\Http\DataTables\UserWiseSalesReportDataTable;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read_stock_alert_product_report')->only('alert_product_qty_report');
        $this->middleware('permission:read_warehouse_report')->only('warehouseWiseReport');
        $this->middleware('permission:read_warehouse_wise_product')->only('warehouseWiseProductReport');
        $this->middleware('permission:read_purchase_details_report')->only('purchase_report');
        $this->middleware('permission:read_purchases_summary_report')->only('purchaseSummaryReport');
        $this->middleware('permission:read_undelivered_sales_report')->only('undelivered_sale_report');
        $this->middleware('permission:read_sales_return')->only('sales_return');
        $this->middleware('permission:read_sales_due_report')->only('sales_due_report');
        $this->middleware('permission:read_day_wise_sales_report')->only('day_wise_sales_report');
        $this->middleware('permission:read_user_wise_sales_report')->only('userwise_sales_report');
        $this->middleware('permission:read_sales_report')->only('sales_report');
    }

    /**
     * Sales All Report Start
     */
    public function sales_report(SaleReportDataTable $dataTable)
    {
        $customer_url = route('sale.searchCustomer');
        $sale_by_url = route('sale.searchAdmin');
        $warehouse_url = route('warehouse.get_search_warehouse');
        return $dataTable->render('report::sales-report.sales-report', compact('customer_url', 'sale_by_url', 'warehouse_url'));
    }

    public function userwise_sales_report(UserWiseSalesReportDataTable $dataTable)
    {
        $sale_by_url = route('sale.searchAdmin');
        return $dataTable->render('report::sales-report.userwise-sales-report', compact('sale_by_url'));
    }

    public function day_wise_sales_report(DayWiseSalesReportDataTable $dataTable)
    {
        return $dataTable->render('report::sales-report.day-wise-sales-report');
    }

    public function sales_due_report(SaleDueReportDataTable $dataTable)
    {
        $customer_url = route('sale.searchCustomer');
        $sale_by_url = route('sale.searchAdmin');
        return $dataTable->render('report::sales-report.sales-due-report', compact('customer_url', 'sale_by_url'));
    }

    public function sales_return(SaleReturnReportDataTable $dataTable)
    {
        $customer_url = route('sale.searchCustomer');
        return $dataTable->render('report::sales-report.sales-return', compact('customer_url'));
    }

    public function undelivered_sale_report(UnDeliveredSaleReportDataTable $dataTable)
    {
        $customer_url = route('sale.searchCustomer');
        $sale_by_url = route('sale.searchAdmin');
        return $dataTable->render('report::sales-report.undelivered-sale-report', compact('customer_url', 'sale_by_url'));
    }

    public function supplier_wise_sale_profit_report(SupplierWiseSaleProfitReportDataTable $dataTable)
    {
        $fiscal_year = FinancialYear::where('status', 1)->where('is_close', 0)->first();
        $toDate = Carbon::parse($fiscal_year->start_date)->format('m-d-Y');
        $formDate = Carbon::parse($fiscal_year->end_date)->format('m-d-Y');
        $supplier_url = route('report.get-supplier');
        return $dataTable->render('report::sales-report.supplier-wise-sale-profit-report', compact('supplier_url', 'toDate', 'formDate'));
    }

    public function sale_report_casher(SaleReportCasherDataTable $dataTable)
    {
        $customer_url = route('sale.searchCustomer');
        $sale_by_url = route('sale.searchAdmin');
        return $dataTable->render('report::sales-report.sale-report-casher', compact('customer_url', 'sale_by_url'));
    }
    public function CategoryWiseSalesReport(CategoryWiseSalesReportDataTable $dataTable)
    {
        $product_url = route('product.search_product');
        $product_model_url = route('product.search_product_model');
        $brand_url = route('product.search_brand');
        $customer_url = route('sale.searchCustomer');
        $category_url = route('product.search_category');
        return $dataTable->render('report::sales-report.category-wise-sale-report', compact('product_url', 'product_model_url', 'brand_url', 'category_url', 'customer_url'));
    }
    /**
     * Sales All Report End
     */

    /**
     * Purchase & Received All Report Start
     */
    public function purchaseSummaryReport(DayWisePurchasesReportDataTable $dataTable)
    {
        return $dataTable->render('report::purchase-report.purchase-summary-report');
    }

    /**
     * Purchase All Report End
     */

    public function getResponseWarehouseWiseProductReport(Request $request)
    {

        if ($request->ajax()) {

            $product_id = $request->product_id;
            $searchAny = null;

            if ($product_id) {
                $product_id = (int) $product_id;
                $searchAny .= "where i.id = '$product_id' ";
            }

            $warehouse_id = $request->get('warehouse_id');

            if ($warehouse_id) {
                $warehouse_id = (int) $warehouse_id;
                $searchAny .= $searchAny ? "and" : "where";
                $searchAny .= " w.id = $warehouse_id";
            }

            $date = $request->get('date');
            $string = explode('-', $date);

            if ($date) {
                $fromDate = date('Y-m-d', strtotime($string[0]));
                $toDate = date('Y-m-d', strtotime($string[1]));
            } else {
                $fromDate = '';
                $toDate = '';
            }

            $data = DB::select("Select i.id, s.warehouse_id, w.name, i.product_name, i.product_model, i.category_id, i.price, i.cost, s.Open_Qty, s.delivered_qty, s.delivered_Amount, s.rece_qty, s.rece_Amount, s.pur_ret_qty, s.pur_ret_Amount, s.sale_ret_Qty, s.sale_ret_Amount, s.wastage_ret_qty, s.wastage_ret_Amount, s.adjustment_qty, s.transfer_qty, s.transfer_rec_qty
            FROM product_information i
            left join (
                SELECT t.product_id, t.warehouse_id, sum(t.open_Qty) Open_Qty, sum(t.delivered_qty) delivered_qty, sum(t.delivered_Amount) delivered_Amount, sum(t.rece_qty) rece_qty, sum(t.rece_Amount) rece_Amount, sum(t.pur_ret_qty) as pur_ret_qty, sum(t.pur_ret_Amount) as pur_ret_Amount, sum(t.sale_ret_Qty) sale_ret_Qty, sum(t.sale_ret_Amount) as sale_ret_Amount, sum(t.wastage_ret_qty) wastage_ret_qty, sum(t.wastage_ret_Amount) as wastage_ret_Amount, sum(t.adjustment_qty) adjustment_qty, sum(t.transfer_qty) transfer_qty, sum(t.transfer_rec_qty) transfer_rec_qty
                FROM (
                    SELECT p.product_id, p.warehouse_id, sum(p.quantity) Open_Qty, 0 as delivered_qty, 0 as delivered_Amount, 0 rece_qty, 0 as rece_Amount, 0 pur_ret_qty, 0 as pur_ret_Amount, 0 sale_ret_Qty, 0 as sale_ret_Amount, 0 wastage_ret_qty, 0 as wastage_ret_Amount, 0 adjustment_qty, 0 transfer_qty, 0 transfer_rec_qty
                    FROM opening_stocks p
                    where p.opening_stock_date < ?
                    Group by p.product_id , p.warehouse_id

                    union all
                    SELECT d.product_id, d.warehouse_id, 0 - sum(d.quantity) Open_Qty, 0 as delivered_qty, 0 as delivered_Amount, 0 rece_qty, 0 as rece_Amount, 0 pur_ret_qty, 0 as pur_ret_Amount, 0 sale_ret_Qty, 0 as sale_ret_Amount, 0 wastage_ret_qty, 0 as wastage_ret_Amount, 0 adjustment_qty, 0 transfer_qty, 0 transfer_rec_qty
                    FROM delivery_items p
                    left join delivery_item_details d ON p.id = d.delivery_id
                    where p.delivery_date < ?
                    Group by d.product_id , d.warehouse_id

                    union all
                    SELECT d.product_id, w.warehouse_id, sum(w.warehouse_base_qty) Open_Qty, 0 pur_qty, 0 pur_Amount, 0 as rece_qty, 0 as rece_Amount, 0 as pur_ret_qty, 0 as pur_ret_Amount, 0 sale_ret_Qty, 0 as sale_ret_Amount, 0 wastage_ret_qty, 0 as wastage_ret_Amount, 0 adjustment_qty, 0 transfer_qty, 0 transfer_rec_qty
                    FROM receives p
                    left join receive_details d ON p.id = d.receive_id
                    left join receive_by_warehouses w on w.receive_id = p.id and d.product_id = w.product_id
                    where p.receive_date < ?
                    Group by d.product_id , w.warehouse_id

                    union all
                    SELECT d.product_id, d.warehouse_id, 0 - sum(d.quantity) Open_Qty, 0 pur_qty, 0 pur_Amount, 0 as rece_qty, 0 as rece_Amount, 0 as pur_ret_qty, 0 as pur_ret_Amount, 0 sale_ret_Qty, 0 as sale_ret_Amount, 0 wastage_ret_qty, 0 as wastage_ret_Amount, 0 adjustment_qty, 0 transfer_qty, 0 transfer_rec_qty
                    FROM purchase_receive_returns p
                    left join purchase_receive_return_details d ON p.id = d.return_id
                    where p.receive_date < ?
                    Group by d.product_id , d.warehouse_id

                    union all
                    SELECT d.product_id, d.warehouse_id, sum(d.quantity) Open_Qty, 0 pur_qty, 0 pur_Amount, 0 as rece_qty, 0 as rece_Amount, 0 as pur_ret_qty, 0 as pur_ret_Amount, 0 sale_ret_Qty, 0 as sale_ret_Amount, 0 wastage_ret_qty, 0 as wastage_ret_Amount, 0 adjustment_qty, 0 transfer_qty, 0 transfer_rec_qty
                    FROM sale_returns p
                    left join sale_return_details d ON p.id = d.return_id
                    where p.sale_date < ?
                    Group by d.product_id , d.warehouse_id

                    union all
                    SELECT d.product_id, p.warehouse_id ,0 - sum(d.quantity) Open_Qty, 0 pur_qty, 0 pur_Amount, 0 as rece_qty, 0 as rece_Amount, 0 as pur_ret_qty, 0 as pur_ret_Amount, 0 sale_ret_Qty, 0 as sale_ret_Amount, 0 wastage_ret_qty, 0 as wastage_ret_Amount, 0 adjustment_qty, 0 transfer_qty, 0 transfer_rec_qty
                    FROM wastages p
                    left join wastage_details d ON p.id = d.wastage_id
                    where p.wastage_date < ?
                    Group by d.product_id , p.warehouse_id

                    union all
                    SELECT d.product_id, p.warehouse_id, 0 - sum(d.quantity) Open_Qty, 0 pur_qty, 0 pur_Amount, 0 as rece_qty, 0 as rece_Amount, 0 as pur_ret_qty, 0 as pur_ret_Amount, 0 sale_ret_Qty, 0 as sale_ret_Amount, 0 as wastage_ret_qty, 0 as wastage_ret_Amount, 0 adjustment_qty, 0 transfer_qty, 0 transfer_rec_qty
                    FROM adjustments p
                    left join adjustment_details d ON p.id = d.adjustment_id
                    where p.adjustment_date < ?
                    Group by d.product_id , p.warehouse_id

                    union all
                    SELECT d.product_id, p.from_warehouse_id as warehouse_id, 0 - sum(d.quantity) Open_Qty, 0 pur_qty, 0 pur_Amount, 0 as rece_qty, 0 as rece_Amount, 0 as pur_ret_qty, 0 as pur_ret_Amount, 0 sale_ret_Qty, 0 as sale_ret_Amount, 0 as wastage_ret_qty, 0 as wastage_ret_Amount, 0 adjustment_qty, 0 transfer_qty, 0 transfer_rec_qty
                    FROM transfers p
                    left join transfer_details d ON p.id = d.transfer_id
                    where p.transfer_date < ? and p.status = 'Approved'
                    Group by d.product_id , warehouse_id

                    union all
                    SELECT d.product_id, p.to_warehouse_id as warehouse_id, 0 - sum(d.quantity) Open_Qty, 0 pur_qty, 0 pur_Amount, 0 as rece_qty, 0 as rece_Amount, 0 as pur_ret_qty, 0 as pur_ret_Amount, 0 sale_ret_Qty, 0 as sale_ret_Amount, 0 as wastage_ret_qty, 0 as wastage_ret_Amount, 0 adjustment_qty, 0 transfer_qty, 0 transfer_rec_qty
                    FROM transfer_receives p
                    left join transfer_receive_details d ON p.id = d.transfer_receive_id
                    where p.transfer_receive_date < ?
                    Group by d.product_id , warehouse_id

                    union all
                    SELECT d.product_id, d.warehouse_id, 0 Open_Qty, sum(d.quantity) as delivered_qty, sum(d.quantity * d.price) as delivered_Amount, 0 rece_qty, 0 as rece_Amount, 0 as pur_ret_qty, 0 as pur_ret_Amount, 0 sale_ret_Qty, 0 as sale_ret_Amount, 0 wastage_ret_qty, 0 as wastage_ret_Amount, 0 adjustment_qty, 0 transfer_qty, 0 transfer_rec_qty
                    FROM delivery_items p
                    left join delivery_item_details d ON p.id = d.delivery_id
                    where p.delivery_date between ? and ?
                    Group by d.product_id , d.warehouse_id

                    union all
                    SELECT d.product_id, w.warehouse_id, 0 Open_Qty, 0 pur_qty, 0 pur_Amount, sum(w.warehouse_base_qty) rece_qty, sum(w.warehouse_base_qty * d.rate) as rece_Amount, 0 as pur_ret_qty, 0 as pur_ret_Amount, 0 sale_ret_Qty, 0 as sale_ret_Amount, 0 wastage_ret_qty, 0 as wastage_ret_Amount, 0 adjustment_qty, 0 transfer_qty, 0 transfer_rec_qty
                    FROM receives p
                    left join receive_details d ON p.id = d.receive_id
                    left join receive_by_warehouses w on w.receive_id = p.id and d.product_id = w.product_id
                    where p.receive_date between ? and ?
                    Group by d.product_id , w.warehouse_id

                    union all
                    SELECT d.product_id, d.warehouse_id, 0 Open_Qty, 0 pur_qty, 0 pur_Amount, 0 as rece_qty, 0 as rece_Amount, sum(d.quantity) as pur_ret_qty, sum(d.quantity * d.rate) as pur_ret_Amount, 0 sale_ret_Qty, 0 as sale_ret_Amount, 0 wastage_ret_qty, 0 as wastage_ret_Amount, 0 adjustment_qty, 0 transfer_qty, 0 transfer_rec_qty
                    FROM purchase_receive_returns p
                    left join purchase_receive_return_details d ON p.id = d.return_id
                    where p.receive_date between ? and ?
                    Group by d.product_id , d.warehouse_id

                    union all
                    SELECT d.product_id, d.warehouse_id, 0 Open_Qty, 0 pur_qty, 0 pur_Amount, 0 as rece_qty, 0 as rece_Amount, 0 as pur_ret_qty, 0 as pur_ret_Amount, sum(d.quantity) sale_ret_Qty, sum(d.quantity * d.rate) as sale_ret_Amount, 0 wastage_ret_qty, 0 as wastage_ret_Amount, 0 adjustment_qty, 0 transfer_qty, 0 transfer_rec_qty
                    FROM sale_returns p
                    left join sale_return_details d ON p.id = d.return_id
                    where p.sale_date between ? and ?
                    Group by d.product_id , d.warehouse_id

                    union all
                    SELECT d.product_id, d.warehouse_id ,0 Open_Qty, 0 pur_qty, 0 pur_Amount, 0 as rece_qty, 0 as rece_Amount, 0 as pur_ret_qty, 0 as pur_ret_Amount, 0 sale_ret_Qty, 0 as sale_ret_Amount, sum(d.quantity) wastage_ret_qty, sum(d.quantity * d.rate) as wastage_ret_Amount, 0 adjustment_qty, 0 transfer_qty, 0 transfer_rec_qty
                    FROM wastages p
                    left join wastage_details d ON p.id = d.wastage_id
                    where p.wastage_date between ? and ?
                    Group by d.product_id , d.warehouse_id

                    union all
                    SELECT d.product_id, d.warehouse_id, 0 Open_Qty, 0 pur_qty, 0 pur_Amount, 0 as rece_qty, 0 as rece_Amount, 0 as pur_ret_qty, 0 as pur_ret_Amount, 0 sale_ret_Qty, 0 as sale_ret_Amount, 0 as wastage_ret_qty, 0 as wastage_ret_Amount, sum(d.quantity) adjustment_qty, 0 transfer_qty, 0 transfer_rec_qty
                    FROM adjustments p
                    left join adjustment_details d ON p.id = d.adjustment_id
                    where p.adjustment_date between ? and ?
                    Group by d.product_id , d.warehouse_id

                    union all
                    SELECT d.product_id, d.from_warehouse_id as warehouse_id, 0 Open_Qty, 0 pur_qty, 0 pur_Amount, 0 as rece_qty, 0 as rece_Amount, 0 as pur_ret_qty, 0 as pur_ret_Amount, 0 sale_ret_Qty, 0 as sale_ret_Amount, 0 as wastage_ret_qty, 0 as wastage_ret_Amount, 0 adjustment_qty, sum(d.quantity) transfer_qty, 0 transfer_rec_qty
                    FROM transfers p
                    left join transfer_details d ON p.id = d.transfer_id
                    where p.transfer_date between ? and ? and p.status = 'Approved'
                    Group by d.product_id , warehouse_id

                    union all
                    SELECT d.product_id, d.to_warehouse_id as warehouse_id, 0 Open_Qty, 0 pur_qty, 0 pur_Amount, 0 as rece_qty, 0 as rece_Amount, 0 as pur_ret_qty, 0 as pur_ret_Amount, 0 sale_ret_Qty, 0 as sale_ret_Amount, 0 as wastage_ret_qty, 0 as wastage_ret_Amount, 0 adjustment_qty, 0 transfer_qty, sum(d.quantity) transfer_rec_qty
                    FROM transfer_receives p
                    left join transfer_receive_details d ON p.id = d.transfer_receive_id
                    where p.transfer_receive_date between ? and ?
                    Group by d.product_id , warehouse_id

                    ) t
                Group by t.product_id , t.warehouse_id
            ) s
            ON s.product_id = i.id
            left join warehouses w ON w.id = s.warehouse_id
            $searchAny
                    ", [$fromDate, $fromDate, $fromDate, $fromDate, $fromDate, $fromDate, $toDate, $toDate, $fromDate, $fromDate, $toDate, $fromDate, $toDate, $fromDate, $toDate, $fromDate, $toDate, $fromDate, $toDate, $fromDate, $toDate, $fromDate, $toDate, $fromDate, $toDate]);

            return DataTables::of($data)->addIndexColumn()
                ->editColumn('warehouse_name', function ($row) {
                    return ucwords($row->name);
                })
                ->editColumn('product_name', function ($row) {
                    return ucwords($row->product_name . ' (' . $row->product_model . ')');
                })
                ->addColumn('open_quantity', function ($row) {
                    return $row->Open_Qty ?? 0;
                })
                ->addColumn('in_qty', function ($row) {
                    return $row->rece_qty ?? 0;
                })
                ->addColumn('out_qty', function ($row) {
                    return $row->delivered_qty ?? 0;
                })
                ->addColumn('purchase_return_qty', function ($row) {
                    return $row->pur_ret_qty ?? 0;
                })
                ->addColumn('sale_ret_Qty', function ($row) {
                    return bt_number_format($row->sale_ret_Qty);
                })
                ->addColumn('wastage_qty', function ($row) {
                    return bt_number_format($row->wastage_ret_qty);
                })
                ->addColumn('adjustment_qty', function ($row) {
                    return bt_number_format($row->adjustment_qty);
                })
                ->addColumn('transfer_qty', function ($row) {
                    return bt_number_format($row->transfer_qty);
                })
                ->addColumn('transfer_rec_qty', function ($row) {
                    return bt_number_format($row->transfer_rec_qty);
                })
                ->addColumn('stock', function ($row) {
                    $stock = ((float) $row->Open_Qty + (float) $row->rece_qty + (float) $row->sale_ret_Qty + (float) $row->transfer_rec_qty) - ((float) $row->pur_ret_qty + (float) $row->wastage_ret_qty + (float) $row->adjustment_qty + (float) $row->delivered_qty + (float) $row->transfer_qty);
                    return bt_number_format($stock) ?? 0;
                })
                ->rawColumns(['product_name', 'product_category', 'sale_price', 'open_quantity', 'purchase_price', 'in_qty', 'out_qty', 'purchase_return_qty', 'stock', 'purchase_return_amount', 'sale_ret_Qty', 'sale_ret_Amount', 'wastage_qty', 'wastage_Amount', 'adjustment_qty', 'transfer_qty', 'transfer_rec_qty', 'stock_purchase_price'])
                ->make(true);
        }

    }
    /**
     * Warehouse All Report End
     */

    // AlertProductQtyDataTable
    public function alert_product_qty_report(AlertProductQtyDataTable $dataTable)
    {
        $category_url = route('report.get-category');
        $product_url = route('report.get-product');
        return $dataTable->render('report::alert-product-qty-report', compact('category_url', 'product_url'));
    }
}
