<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', [ApiController::class, 'index']);

Route::controller(ApiController::class)->group(function () {

    Route::get('/language', 'language')->name('language');
    Route::get('/webSetting', 'webSetting')->name('webSetting');

    Route::get('/login', 'login')->name('login');
    Route::post('/password_recovery', 'password_recovery')->name('password_recovery');

    Route::get('/recovery_form/{token_id}','recoveryForm')->name('recovery_form');
    Route::post('/recovery_submit/{token_id}','recoverySubmit')->name('recovery_submit');

    Route::get('/add_attendance','addAttendance')->name('api.add_attendance');

    Route::get('/attendance_history','attendanceHistory')->name('attendance_history');
    Route::get('/attendance_datewise','attendanceDatewise')->name('attendance_datewise');

    Route::get('/current_month_totalhours','currentMonthTotalHours')->name('current_month_totalhours');
    Route::get('/noticeinfo','noticeInfo')->name('noticeinfo');
    Route::get('/loan_amount','loanAmount')->name('loan_amount');
    Route::get('/graph_info','graphInfo')->name('graph_info');
    Route::get('/salary_info','salaryInfo')->name('salary_info');
    Route::get('/leave_type_list','leaveTypeList')->name('leave_type_list');
    Route::get('/leave_application','leaveApplication')->name('leave_application');
    Route::get('/leave_list','leaveList')->name('leave_list');
    Route::get('/ledger','ledger')->name('ledger');
    Route::get('/leave_remaining','leaveRemaining')->name('leave_remaining');
    Route::get('/current_month_totalday','currentMonthTotalday')->name('current_month_totalday');

});