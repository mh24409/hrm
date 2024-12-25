<?php

use Illuminate\Support\Facades\Route;
use Modules\Accounts\Http\Controllers\AccCoaController;
use Modules\Accounts\Http\Controllers\AccountsController;
use Modules\Accounts\Http\Controllers\AccReportController;
use Modules\Accounts\Http\Controllers\AccQuarterController;
use Modules\Accounts\Http\Controllers\AccSubcodeController;
use Modules\Accounts\Http\Controllers\AccountAjaxController;
use Modules\Accounts\Http\Controllers\DebitVoucherController;
use Modules\Accounts\Http\Controllers\ContraVoucherController;
use Modules\Accounts\Http\Controllers\CreditVoucherController;
use Modules\Accounts\Http\Controllers\FinancialYearController;
use Modules\Accounts\Http\Controllers\AccTransactionController;
use Modules\Accounts\Http\Controllers\JournalVoucherController;
use Modules\Accounts\Http\Controllers\AccOpeningBalanceController;
use Modules\Accounts\Http\Controllers\AccPredefineAccountController;
use Modules\Accounts\Http\Controllers\SubTypeController;

Route::group(['prefix' => 'accounts', 'middleware' => 'auth'], function () {

    Route::name('account.')->group(function () {
        Route::controller(AccCoaController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/get/detail/{coa}', 'show')->name('show');
            Route::get('/{coa}/edit', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
            Route::post('/del', 'destroy')->name('destroy');
            Route::get('/all/subtype', 'allsubtype')->name('allsubtype');
            //export data
            Route::get('/export-acc-coa', 'exportAccCoaToExcel')->name('export-acc-coa');
        });
    });

    Route::name('transaction.')->group(function () {
        Route::controller(AccTransactionController::class)->group(function () {
            Route::any('/transactions/pending/index', 'index')->name('index');
            Route::post('/transactions/approve', 'approve')->name('approve');
            Route::get('/transaction/approved/list', 'show')->name('show');
            Route::post('/transaction/update', 'update')->name('update');
            Route::post('/transaction/del', 'destroy')->name('destroy');
        });
    });

    Route::name('reports.')->group(function () {
        Route::controller(AccReportController::class)->group(function () {
            Route::get('/create/cash/book', 'cashbook')->name('cashbook');
            Route::post('/generate/cash/book', 'cashbookGenerate')->name('cashbookGenerate');

            Route::get('/create/bank/book', 'bankbook')->name('bankbook');
            Route::post('/generate/bank/book', 'bankbookGenerate')->name('bankbookGenerate');

            Route::get('/create/general/ledger', 'ledgergeneral')->name('ledgergeneral');
            Route::post('/generate/general/ledger', 'ledgergeneralGenerate')->name('ledgergeneralGenerate');

            Route::get('/balance-sheet', 'genarateBalanceSheet')->name('balance.sheet');
            Route::get('/profit-loss', 'genarateProfitLoss')->name('profit-loss');
            Route::get('/create/sub/ledger', 'subledger')->name('subledger');
            Route::any('/generate/sub/ledger', 'subledgerGenerate')->name('subledgerGenerate');
            Route::any('/redirect-sub-ledger-generate', 'redirectSubLedgerGenerate')->name('redirect_sub_ledger_generate');

            Route::get('/create/trail/balance', 'trilbalance')->name('trilbalance');
            Route::post('/generate/trail/balance', 'trilbalancelGenerate')->name('trilbalancelGenerate');

            Route::get('/create/day/book', 'daybook')->name('daybook');
            Route::post('/generate/day/book', 'daybookGenerate')->name('daybookGenerate');

            Route::get('/create/receipt/payment', 'receiptpayment')->name('receiptpayment');
            Route::post('/generate/receipt/payment', 'receiptpaymentGenerate')->name('receiptpaymentGenerate');

            //control ledger
            Route::match(['get', 'post'], '/control-ledger', 'controlLedger')->name('controlLedger');
            //note ledger
            Route::match(['get', 'post'], '/note-ledger', 'noteLedger')->name('noteLedger');

            // pdf create route
            Route::post('/cash-book/pdf', 'cashbookGenerate')->name('cashbookPdf');
            Route::post('/bank-book/pdf', 'bankbookGenerate')->name('bankbookPdf');
            Route::post('/day-book/pdf', 'daybookGenerate')->name('daybookPdf');
            Route::post('/ledger-general/pdf', 'ledgergeneralGenerate')->name('ledgergeneralPdf');
            Route::post('/sub-ledger/pdf', 'subledgerGenerate')->name('subledgerPdf');
            Route::post('/receipt-payment/pdf', 'receiptpaymentGenerate')->name('receiptpaymentPdf');
            Route::post('/trail-balance/pdf', 'trilbalancelGenerate')->name('trilbalancelPdf');
            Route::post('/profit-loss/pdf', 'genarateProfitLoss')->name('profitlossPdf');
            Route::post('/balance-sheet/pdf', 'genarateBalanceSheet')->name('balancesheetPdf');
            Route::post('/control-ledger/pdf', 'controlLedger')->name('controlLedgerPdf');
            Route::post('/note-ledger/pdf', 'noteLedger')->name('noteLedgerPdf');
            // voucher modal route
            Route::get('/voucher/modal/{id}', 'showVoucher')->name('showVoucher');
        });
    });

    Route::resource('opening-balances', AccOpeningBalanceController::class);
    Route::resource('debit-vouchers', DebitVoucherController::class);
    Route::get('vouchers/reverse/{uuid}', [AccTransactionController::class, 'reverseVoucher'])->name('vouchers.reverse');
    Route::resource('credit-vouchers', CreditVoucherController::class);
    Route::resource('contra-vouchers', ContraVoucherController::class);
    Route::resource('journal-vouchers', JournalVoucherController::class);
    Route::resource('financial-years', FinancialYearController::class);
    Route::post('financial-years/close', [FinancialYearController::class, 'closeFinancialYear'])->name('financial-years.close');
    Route::post('financial-years/status/{uuid}', [FinancialYearController::class, 'statusChange'])->name('financial-years.statusChange');
    Route::post('financial-years/reversed/{id}', [FinancialYearController::class, 'reverseFinancialYear'])->name('financial-years.reversed');

    Route::resource('subtypes', SubTypeController::class);
    Route::resource('subcodes', AccSubcodeController::class);
    Route::resource('predefine-accounts', AccPredefineAccountController::class);
    Route::resource('quarters', AccQuarterController::class);

    //voucher report pdf generate
    Route::get('contra-vouchers/pdf/{id}', [ContraVoucherController::class, 'downloadContraVoucherPdf'])->name('contra-vouchers.download');
    Route::get('credit-vouchers/pdf/{id}', [CreditVoucherController::class, 'downloadCreditVoucherPdf'])->name('credit-vouchers.download');
    Route::get('debit-vouchers/pdf/{id}', [DebitVoucherController::class, 'downloadDebitVoucherPdf'])->name('debit-vouchers.download');
    Route::get('journal-vouchers/pdf/{id}', [JournalVoucherController::class, 'downloadJournalVoucherPdf'])->name('journal-vouchers.download');
    //import opening balance excel
    Route::post('import/opening/balance', [AccOpeningBalanceController::class, 'importOpeningBalance'])->name('import.opening.balance');
});

Route::get('accounts/getsubtypecode/{id:id}', [AccountsController::class, 'getSubtypeByCode'])->name('subtypes.by-code');
Route::get('accounts/getsubtypbyid/{id}', [AccountsController::class, 'getSubtypeById'])->name('subtypes.by-id');
// all report ajax requst
Route::get('/acconts/ajax/subtype/coa/{subtypeid}', [AccountAjaxController::class, 'getCoaFromSubtype'])->name('getCoaFromSubtype');
Route::get('/acconts/ajax/subtype/code/{subtypeid}', [AccountAjaxController::class, 'getsubcode'])->name('getsubcode');
