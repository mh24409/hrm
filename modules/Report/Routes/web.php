<?php
use Illuminate\Support\Facades\Route;
use Modules\Report\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'report', 'middleware' => 'auth'], function () {
    Route::name('report.')->group(function () {
        Route::controller(ReportController::class)->group(function () {

            //Sales Report Route
            Route::get('/sales-report', 'sales_report')->name('sales-report');
            Route::get('/sale-report-casher', 'sale_report_casher')->name('sale-report-casher');
            Route::get('/userwise-sales-report', 'userwise_sales_report')->name('userwise-sales-report');
            Route::get('/day-wise-sales-report', 'day_wise_sales_report')->name('daywise-sales-report');
            Route::get('/sales-due-report', 'sales_due_report')->name('sales-due-report');
            Route::get('/sales-return', 'sales_return')->name('sales-return');
            Route::get('/profit-report-sales-wise', 'profit_report_sales_wise')->name('profit-report-sales-wise');
            Route::get('/undelivered-sale-report', 'undelivered_sale_report')->name('undelivered-sale-report');
            Route::get('/supplier-wise-sale-profit-report', 'supplier_wise_sale_profit_report')->name('supplier-wise-sale-profit-report');
            Route::get('/cash-register-report', 'cashRegisterReport')->name('cash_register_report');
            Route::get('/category-wise-sale-report', 'CategoryWiseSalesReport')->name('category_wise_sale_report');

            //Purchase Report Route
            Route::get('/purchase-summary-report', 'purchaseSummaryReport')->name('purchases_summary_report');
            Route::get('/purchase-report', 'purchase_report')->name('purchase-report');
            Route::get('/purchase-details-show/{id:id}', 'purchaseDetailsShow')->name('purchase_details_show');
            Route::get('/receive-report', 'receive_report')->name('receive-report');
            Route::get('/receive-details-show/{id:id}', 'receiveDetailsShow')->name('receive_details_show');
            Route::get('/category-wise-purchase-report', 'CategoryWisePurchaseReport')->name('category_wise_purchase_report');

            //Warehouse Wise Report Route
            Route::get('/warehouse-wise-report', 'warehouseWiseReport')->name('warehouse_wise_report');
            Route::get('/warehouse-wise-product-report', 'warehouseWiseProductReport')->name('warehouse_wise_product_report');
            Route::get('/warehouse-wise-product-report/get-response', 'getResponseWarehouseWiseProductReport');

            //Alert Report
            Route::get('/alert-product-qty-report', 'alert_product_qty_report')->name('alert-product-qty-report');

            //dropdown data
            Route::post('/get-product', 'all_product_dropdown')->name('get-product');
            Route::post('/get-category', 'all_category_dropdown')->name('get-category');
            Route::post('/get-supplier', 'all_supplier_dropdown')->name('get-supplier');

            //cash register
            Route::get('/cash-register', 'cashRegister')->name('cash-register');
        });
    });
});
