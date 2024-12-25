<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocalizationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Modules\Localize\Entities\Langstring;
use Modules\Localize\Entities\Langstrval;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('dev/artisan-http/storage-link', function () {
    Artisan::call('module:asset-link');
    Artisan::call('storage:unlink');
    Artisan::call('storage:link');
});

Auth::routes();

Route::get('get-localization-strings', [LocalizationController::class, 'index'])->name('get-localization-strings');
Route::post('get-localization-strings', [LocalizationController::class, 'store']);

Route::group(['middleware' => ['auth', 'isAdmin']], function () {
    Route::get('dashboard', [HomeController::class, 'index'])->name('home');
    Route::post('departmentWiseAttendance/{type}', [HomeController::class, 'attendanceDepartmentWise'])->name('departmentWiseAttendance');
    Route::post('positionWiseRecruitment/{type}', [HomeController::class, 'recruitmentPositionWise'])->name('positionWiseRecruitment');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard/home', [HomeController::class, 'staffHome'])->name('staffHome');
    Route::get('/dashboard/employee', [HomeController::class, 'myProfile'])->name('myProfile');
    Route::get('/dashboard/employee/edit', [HomeController::class, 'editMyProfile'])->name('editMyProfile');

    Route::get('/dashboard/profile', [HomeController::class, 'empProfile'])->name('empProfile');

    Route::get('/dashboard/today-yesterday-sales', [HomeController::class, 'todayAndYesterdaySales'])->name('todayAndYesterdaySales');
    Route::get('/dashboard/sales-return-amount', [HomeController::class, 'salesReturnAmount'])->name('salesReturnAmount');

    Route::post('/dashboard/total-sales-amount', [HomeController::class, 'totalSalesAmount'])->name('totalSalesAmount');
    Route::post('/dashboard/stock-valuation', [HomeController::class, 'stockValuationData'])->name('stockValuation');
    Route::post('/dashboard/invoice-due', [HomeController::class, 'invoiceDue'])->name('invoiceDue');
    Route::post('/dashboard/total-expense', [HomeController::class, 'totalExpense'])->name('totalExpense');
    Route::post('/dashboard/purchase-return-amount', [HomeController::class, 'purchaseReturnAmount'])->name('purchaseReturnAmount');
    Route::post('/dashboard/purchase-due', [HomeController::class, 'purchaseDue'])->name('purchaseDue');

    Route::post('/dashboard/sales/report', [HomeController::class, 'last30daySales'])->name('last30daySales');
    Route::post('/dashboard/purchase/report', [HomeController::class, 'last30dayPurchase'])->name('last30dayPurchase');
    Route::get('/dashboard/stock-alert', [HomeController::class, 'getStockAlertProductList'])->name('getStockAlertProductList');
    Route::get('/dashboard/low-stock', [HomeController::class, 'getLowStockProductList'])->name('getLowStockProductList');
    Route::post('/dashboard/income-expense/report', [HomeController::class, 'incomeExpense'])->name('incomeExpense');
    Route::post('/dashboard/warehouse-wise-stock/report', [HomeController::class, 'warehouseWiseStock'])->name('warehouseWiseStock');
    Route::post('/dashboard/bank-and-cash-balance', [HomeController::class, 'bankAndCashBalance'])->name('bankAndCashBalance');
    Route::post('/dashboard/counter-wise-sale', [HomeController::class, 'counterWiseSale'])->name('counterWiseSale');
    Route::post('/dashboard/cashier-wise-sale', [HomeController::class, 'cashierWiseSale'])->name('cashierWiseSale');
    Route::post('/dashboard/most-selling-product', [HomeController::class, 'mostSellingProduct'])->name('mostSellingProduct');
    Route::post('/dashboard/less-selling-product', [HomeController::class, 'lessSellingProduct'])->name('lessSellingProduct');

    // For employee dashboard reports/charts
    Route::get('/home/yearly_employee_points', [HomeController::class, 'yearlyEmployeePoints'])->name('home.yearly-employee-points');
    Route::get('/home/monthly_employee_points', [HomeController::class, 'monthlyEmployeePoints'])->name('home.monthly-employee-points');
    Route::get('/home/monthly_employee_attendence', [HomeController::class, 'monthlyEmployeeAttendence'])->name('home.monthly-employee-attendence');
});

//All Clear
Route::get('/all-clear', [HomeController::class, 'allClear'])->name('all_clear');

Route::get('/insert-language', function () {
    DB::table('langstrings')->truncate();
    $lang_strs = __('language');
    foreach ($lang_strs as $i => $str) {
        $lang = new Langstring();
        $lang->key = $i;
        $lang->save();
    }
    return 'Phrase Inserted Successfully..!!';
});

Route::get('/insert-language-value', function () {
    // DB::table('langstrvals')->truncate();
    $lang_strs = __('language');

    $key = 0;
    foreach ($lang_strs as $i => $str) {
        $lang = new Langstrval();
        $lang->localize_id = 2;
        $lang->langstring_id = $key + 1;
        $lang->phrase_value = $str;
        $lang->save();

        $key++;
    }

    return 'Phrase Value Inserted Successfully..!!';
});

Route::get('test1', function () {
    session()->put('test1', 'Phrase Value Inserted Successfully..!!');
    return session()->get('test1');
});
