<?php

use Illuminate\Support\Facades\Route;
use Modules\HumanResource\Http\Controllers\BankController;
use Modules\HumanResource\Http\Controllers\LoanController;
use Modules\HumanResource\Http\Controllers\UnitController;
use Modules\HumanResource\Http\Controllers\AwardController;
use Modules\HumanResource\Http\Controllers\LeaveController;
use Modules\HumanResource\Http\Controllers\GenderController;
use Modules\HumanResource\Http\Controllers\IdcardController;
use Modules\HumanResource\Http\Controllers\NoticeController;
use Modules\HumanResource\Http\Controllers\ReportController;
use Modules\HumanResource\Http\Controllers\HolidayController;
use Modules\HumanResource\Http\Controllers\MessageController;
use Modules\HumanResource\Http\Controllers\DivisionController;
use Modules\HumanResource\Http\Controllers\EmployeeController;
use Modules\HumanResource\Http\Controllers\PositionController;
use Modules\HumanResource\Http\Controllers\LeaveTypeController;
use Modules\HumanResource\Http\Controllers\SetupRuleController;
use Modules\HumanResource\Http\Controllers\DepartmentController;
use Modules\HumanResource\Http\Controllers\ProcurementCommitteeController;
use Modules\HumanResource\Http\Controllers\ProcurementRequestController;
use Modules\HumanResource\Http\Controllers\ProcurementVendorController;
use Modules\HumanResource\Http\Controllers\RewardPointController;
use Modules\HumanResource\Http\Controllers\SalarySetupController;
use Modules\HumanResource\Http\Controllers\PayFrequencyController;
use Modules\HumanResource\Http\Controllers\HumanResourceController;
use Modules\HumanResource\Http\Controllers\SalaryAdvanceController;
use Modules\HumanResource\Http\Controllers\SalaryGenerateController;
use Modules\HumanResource\Http\Controllers\ManualAttendanceController;
use Modules\HumanResource\Http\Controllers\CandidateInterviewController;
use Modules\HumanResource\Http\Controllers\CandidateSelectionController;
use Modules\HumanResource\Http\Controllers\CandidateShortlistController;
use Modules\HumanResource\Http\Controllers\EmployeePerformanceController;
use Modules\HumanResource\Http\Controllers\CandidateInformationController;
use Modules\HumanResource\Http\Controllers\EmployeePerformanceCriteriaController;
use Modules\HumanResource\Http\Controllers\ProcurementBidAnalysisController;
use Modules\HumanResource\Http\Controllers\ProcurementGoodsReceivedController;
use Modules\HumanResource\Http\Controllers\ProcurementPurchaseOrderController;
use Modules\HumanResource\Http\Controllers\ProcurementQuotationController;
use Modules\HumanResource\Http\Controllers\ProjectManagementController;
use Modules\HumanResource\Http\Controllers\ProjectTasksController;
use Modules\HumanResource\Http\Controllers\ProjectSprintsController;
use Modules\HumanResource\Http\Controllers\ProjectReportsController;
use Modules\HumanResource\Http\Controllers\TaxCalculationController;

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

Route::group(['prefix' => 'hr', 'middleware' => ['auth']], function () {

    Route::get('dashboard', [HumanResourceController::class, 'index'])->name('hr.dashboard');

    Route::resource('departments', DepartmentController::class);
    Route::get('sub-departments', [DepartmentController::class, 'getSubDepartments'])->name('sub-departments.index');
    Route::get('get-employees', [DepartmentController::class, 'getEmployees'])->name('get-employees-department');

    Route::resource('genders', GenderController::class);
    Route::resource('divisions', DivisionController::class);
    Route::resource('positions', PositionController::class);

    Route::resource('employees', EmployeeController::class);
    Route::post('employee-info', [EmployeeController::class, 'employeeInfo'])->name('employee.employee_info');
    Route::get('inactive-list', [EmployeeController::class, 'inactive_list'])->name('employees.inactive_list');
    Route::get('employee/status-change/{id:id}', [EmployeeController::class, 'statusChange'])->name('employee.status_change');
    Route::get('employee/download/{id:id}', [EmployeeController::class, 'download'])->name('employee.download');
    Route::post('employee/profile-picture-update/{id:id}', [EmployeeController::class, 'profilePictureUpdate'])->name('employee.profile_picture_update');
    Route::post('employee/skill-type', [EmployeeController::class, 'skillTypeStore'])->name('employee.skill_type_store');
    Route::get('employee/get-skill-type', [EmployeeController::class, 'getSkillType'])->name('employee.get_skill_type');
    Route::post('employee/delete-skill-type/{id:id}', [EmployeeController::class, 'deleteSkillType'])->name('employee.delete_skill_type');
    Route::post('employee/certificate-type', [EmployeeController::class, 'certificateTypeStore'])->name('employee.certificate_type_store');
    Route::get('employee/get-certificate-type', [EmployeeController::class, 'getCertificateType'])->name('employee.get_certificate_type');
    Route::post('employee/delete-certificate-type/{id:id}', [EmployeeController::class, 'deleteCertificateType'])->name('employee.delete_certificate_type');
    Route::get('get-employee/{id}', [EmployeeController::class, 'getEmployeeByID'])->name('employee.by-id');

    Route::get('setup-rules', [SetupRuleController::class, 'index'])->name('setup_rules.index');
    Route::post('setup-rules/store', [SetupRuleController::class, 'store'])->name('setup_rules.store');
    Route::post('setup-rules/{id:id}', [SetupRuleController::class, 'setupRulesUpdate'])->name('setup_rules.update');
    Route::delete('setup-rules/destroy/{id:id}', [SetupRuleController::class, 'destroy'])->name('setup_rules.destroy');
    Route::resource('pay-frequencies', PayFrequencyController::class);
    Route::resource('loans', LoanController::class);
    Route::get('loans', [LoanController::class, 'index'])->name('hr.loans.index');
    Route::get('loan/edit/{uuid}', [LoanController::class, 'edit'])->name('hr.loan.edit');
    Route::get('loans-report', [LoanController::class, 'loanReportForm'])->name('hr.loans.report');
    Route::get('loans-report-loan_disburse_report', [LoanController::class, 'loan_disburse_report'])->name('hr.loans.loan_disburse_report');
    Route::get('loans-report/employee', [LoanController::class, 'employeeLoan'])->name('hr.loans.employee');
    Route::get('loans-report/employee-report', [LoanController::class, 'employeeLoanReport'])->name('hr.loans.employee.report');

    Route::prefix('payroll')->group(function () {
        Route::get('salary-approval/{uuid}', [SalaryGenerateController::class, 'getSalaryApproval'])->name('salary.approval-form');
        Route::post('salary-approval/{uuid}', [SalaryGenerateController::class, 'salaryApproval'])->name('salary.approval');
        Route::resource('salary-setup', SalarySetupController::class);
    });

    Route::resource('employee-performances', EmployeePerformanceController::class);
    Route::resource('performance-criterias', EmployeePerformanceCriteriaController::class);

    Route::name('award.')->prefix('award')->group(function () {
        Route::resource('/', AwardController::class)->parameter('', 'award');
    });

    Route::name('candidate.')->prefix('recruitment')->group(function () {
        Route::resource('/', CandidateInformationController::class)->parameter('', 'candidate');
    });

    Route::name('shortlist.')->prefix('shortlist')->group(function () {
        Route::resource('/', CandidateShortlistController::class)->parameter('', 'shortlist');
    });

    Route::name('interview.')->prefix('interview')->group(function () {
        Route::resource('/', CandidateInterviewController::class)->parameter('', 'interview');
    });

    Route::get('get-position', 'CandidateInterviewController@getPosition')->name('interview.get-position');

    Route::name('selection.')->prefix('selection')->group(function () {
        Route::resource('/', CandidateSelectionController::class)->parameter('', 'selection');
    });

    Route::name('units.')->prefix('units')->group(function () {
        Route::resource('/', UnitController::class)->parameter('', 'units');
    });

    Route::name('committee.')->prefix('committee')->group(function () {
        Route::resource('/', ProcurementCommitteeController::class)->parameter('', 'committee');
    });

    Route::name('vendor.')->prefix('vendor')->group(function () {
        Route::resource('/', ProcurementVendorController::class)->parameter('', 'vendor');
    });

    Route::name('procurement_request.')->prefix('procurement_request')->group(function () {
        Route::resource('/', ProcurementRequestController::class)->parameter('', 'req');
    });

    Route::post('procurement_request/{id}/approve', 'ProcurementRequestController@approveRequest')
        ->name('procurement_request.approve');

    Route::name('quotation.')->prefix('quotation')->group(function () {
        Route::resource('/', ProcurementQuotationController::class)->parameter('', 'quotation')->except('create');
        Route::get('/create/{quotation}', [ProcurementQuotationController::class, 'create'])->name('create');
    });

    Route::name('bid.')->prefix('bid')->group(function () {
        Route::resource('/', ProcurementBidAnalysisController::class)->parameter('', 'bid');
    });

    Route::post('procurements/get-quotation-items', 'ProcurementBidAnalysisController@getQuotationItems')
        ->name('bid.get_quotation_items');

    Route::post('procurements/get-committee', 'ProcurementBidAnalysisController@getCommittee')
        ->name('bid.get_committee');

    Route::name('purchase.')->prefix('purchase')->group(function () {
        Route::resource('/', ProcurementPurchaseOrderController::class)->parameter('', 'purchase');
    });

    Route::post('purchase/get-quotation-items', 'ProcurementPurchaseOrderController@getQuotationItems')
        ->name('purchase.get_quotation_items');

    Route::post('purchase/get-quotation-info', 'ProcurementPurchaseOrderController@getQuotationInfo')
        ->name('purchase.get_quotation_info');

    Route::name('goods.')->prefix('goods')->group(function () {
        Route::resource('/', ProcurementGoodsReceivedController::class)->parameter('', 'goods');
    });

    Route::post('goods/get-purchase-items', 'ProcurementGoodsReceivedController@getPurchaseItems')
        ->name('goods.get_purchase_items');

    Route::post('goods/get-purchase-info', 'ProcurementGoodsReceivedController@getPurchaseInfo')
        ->name('goods.get_purchase_info');

    Route::name('bank.')->group(function () {
        Route::controller(BankController::class)->group(function () {
            Route::get('/banks/index', 'index')->name('index');
            Route::get('/banks/create', 'create')->name('create');
            Route::post('/banks/store', 'store')->name('store');
            Route::get('/banks/{bank}', 'show')->name('show');
            Route::get('/banks/{bank:uuid}/edit', 'edit')->name('edit');
            Route::put('/update/banks/{bank:uuid}', 'update')->name('update');
            Route::delete('delete/banks/{bank:uuid}', 'destroy')->name('destroy');
        });
    });

    Route::name('holiday.')->group(function () {
        Route::controller(HolidayController::class)->group(function () {
            Route::get('/holidays/week/index', 'weekholiday')->name('weekholiday');
            Route::get('/holidays/index', 'index')->name('index');
            Route::get('/holidays/create', 'create')->name('create');
            Route::post('/holidays/store', 'store')->name('store');
            Route::get('/holidays/{holiday}', 'show')->name('show');
            Route::get('/holidays/{holiday:uuid}/edit', 'edit')->name('edit');
            Route::put('/update/holidays/{holiday:uuid}', 'update')->name('update');
            Route::delete('delete/holidays/{holiday:uuid}', 'destroy')->name('destroy');
        });
    });

    Route::name('leave.')->group(function () {
        Route::controller(LeaveController::class)->group(function () {
            Route::get('/leaves/type/week/index', 'weekleave')->name('weekleave');
            Route::get('/leaves/type/week/edit/{uuid}', 'weekleave_edit')->name('weekleave.edit');
            Route::post('/leaves/type/week/update/{weeklyholiday:uuid}', 'weekleave_update')->name('weekholidays.update');
            Route::get('/leaves/type/index', 'leaveTypeindex')->name('leaveTypeindex');
            Route::get('/leaves/generate/index', 'leaveGenerate')->name('leaveGenerate');
            Route::post('/leaves/generate/', 'generateLeave')->name('generateLeave');
            Route::get('/leaves/generate/detail/{yearid}', 'generateLeaveDetail')->name('generateLeaveDetail');
            Route::get('/leaves/index', 'index')->name('index');
            Route::get('/leaves/create', 'create')->name('create');
            Route::get('/leaves/edit/{id:id}', 'leaveApplicationEdit')->name('leave_application_edit');
            Route::post('/leaves/store', 'store')->name('store');
            Route::get('/leaves/{leave:uuid}/edit', 'edit')->name('edit');
            Route::put('/update/leaves/{leave:uuid}', 'update')->name('update');
            Route::get('/leaves/approved/{leave:uuid}', 'showApproveLeaveApplication')->name('show_approve_leave_application');
            Route::put('/update/leaves/approved/{leave:uuid}', 'approved')->name('approved');
            Route::delete('delete/leaves/{leave:uuid}', 'destroy')->name('destroy');

            Route::get('/leaves/approvals', 'leaveApproval')->name('approval');
            Route::put('leaves/approvals/{uuid}', 'ApprovedByManager')->name('approved-by-manager');
        });
    });

    Route::resource('leave-types', LeaveTypeController::class);

    Route::name('attendances.')->group(function () {
        Route::controller(ManualAttendanceController::class)->group(function () {
            Route::get('/attendances/index', 'index')->name('index');
            Route::post('/attendances/store', 'store')->name('store');
            Route::get('/attendances/create', 'create')->name('create');
            Route::post('/attendances/bulk/store', 'bulk')->name('bulk');
            Route::get('/attendances/monthly/create', 'monthlyCreate')->name('monthlyCreate');
            Route::post('/attendances/monthly/store', 'monthlyStore')->name('monthlyStore');
            Route::get('/attendances/missing-attendance', 'missingAttendance')->name('missingAttendance');
            Route::post('/attendances/missing-attendance', 'missingAttendanceStore')->name('missingAttendance.store');
            Route::post('/attendances/monthly-attendance-bulk-import', 'monthlyAttendanceBulkImport')->name('monthly_attendance_bulk_import');
            Route::get('/attendances/{attendance}', 'show')->name('show');
            Route::get('/attendances/edit/{attendance}', 'edit')->name('edit');
            Route::put('/update/attendances/{attendance}', 'update')->name('update');
            Route::delete('/attendances/delete/{attendance}', 'destroy')->name('destroy');
        });
    });

    Route::name('idprint.')->group(function () {
        Route::controller(IdcardController::class)->group(function () {
            Route::get('/id/print/student/index', 'studentindex')->name('studentindex');
            Route::get('/id/print/employee/index', 'employeeindex')->name('employeeindex');
            Route::get('/id/show/student/{idprint:uuid}', 'studentshow')->name('studentshow');
            Route::get('/id/show/employee/{idprint:uuid}', 'employeeshow')->name('employeeshow');
        });
    });

    Route::name('reports.')->group(function () {
        Route::controller(ReportController::class)->group(function () {
            Route::get('reports/employee', 'employeeReport')->name('employee');
            Route::get('reports/staff-attendance', 'staffAttendanceReport')->name('staff-attendance');
            Route::get('reports/lateness-closing-attendance', 'latenessClosingAttendanceReport')->name('lateness-closing-attendance');
            Route::get('reports/attendance-log', 'attendanceLogReport')->name('attendance-log');
            Route::get('reports/daily-present', 'dailyPresentReport')->name('daily-present');
            Route::get('reports/monthly', 'monthlyReport')->name('monthly');
            Route::get('reports/monthly-report', 'monthlyReportShow')->name('monthly-report');
            Route::get('reports/attendance-log/{employee}', 'attendanceLogEmployeeDetails')->name('attendance-log-details');
            Route::get('reports/staff-attendance/detail/{id}', 'staffAttendanceDetailReport')->name('staff-attendance-detail');
            Route::get('reports/job-card', 'jobCardReport')->name('job-card');
            Route::get('reports/job-card-reports', 'jobCardReportShow')->name('job_card_reports');
            Route::get('reports/attendance-summery', 'attendanceSummery')->name('attendance-summery');
            Route::get('reports/contract-renewal', 'contractRenewalReport')->name('contract-renewal');
            Route::get('reports/allowance', 'allowanceReport')->name('allowance');
            Route::get('reports/deduction', 'deductionReport')->name('deduction');
            Route::get('reports/leave', 'leaveReport')->name('leave');
            Route::get('reports/salary-advance', 'salaryAdvanceReport')->name('salary-advance');
            Route::get('reports/adhoc-advance', 'adhocAdvanceReport')->name('adhoc-advance');
            Route::post('reports/adhoc-advance', 'adhocAdvanceReportShow')->name('adhoc-advance-show');

            // Payroll Reports
            Route::get('reports/payroll', 'npf3SocSecTaxReport')->name('npf3-soc-sec-tax-report');
            Route::get('reports/payroll/npf3_soc_sec_tax_report', 'npf3SocSecTaxReportShow')->name('npf3-soc-sec-tax-report-show');
            Route::get('reports/payroll/npf3_soc_sec_tax/{id}/pdf', 'npf3SocSecTaxPdf')->name('npf3-soc-sec-tax-pdf');
            Route::get('reports/payroll/iicf3_contribution', 'iicf3Contribution')->name('iicf3-contribution');
            Route::get('reports/payroll/iicf3_contribution_report', 'iicf3ContributionShow')->name('iicf3-contribution-report-show');
            Route::get('reports/payroll/iicf3_contribution_pdf/{id}/pdf', 'iicf3ContributionPdf')->name('iicf3-contribution-pdf');
            Route::get('reports/payroll/social_security_npf_icf', 'socialSecurityNpfIcfReport')->name('social-security-npf-icf');
            Route::get('reports/payroll/social_security_npf_icf_report', 'socialSecurityNpfIcfShow')->name('social-security-npf-icf-show');
            Route::get('reports/payroll/social_security_npf_icf_pdf/{id}/pdf', 'socialSecurityNpfIcfPdf')->name('social-security-npf-icf-pdf');
            Route::get('reports/payroll/gra_ret_5_report', 'graRet5ReportReport')->name('gra-ret-5-report');
            Route::get('reports/payroll/gra_ret_5_report_report', 'graRet5ReportReportShow')->name('gra-ret-5-show');
            Route::get('reports/payroll/gra_ret_5_report_pdf/{id}/pdf', 'graRet5ReportReportPdf')->name('gra-ret-5-pdf');
            Route::get('reports/payroll/sate_income_tax', 'sateIncomeTaxReport')->name('sate-income-tax');
            Route::get('reports/payroll/sate_income_tax_report', 'sateIncomeTaxReportShow')->name('sate-income-tax-show');
            Route::get('reports/payroll/sate_income_tax_pdf/{id}/pdf', 'sateIncomeTaxReportPdf')->name('sate-income-tax5-pdf');
            Route::get('reports/payroll/salary_confirmation_form', 'salaryConfirmationForm')->name('salary-confirmation-form');
            Route::get('reports/payroll/salary_confirmation_form_report', 'salaryConfirmationFormShow')->name('salary-confirmation-show');
            Route::get('reports/payroll/salary_confirmation_form_pdf/{id}/pdf', 'salaryConfirmationFormPdf')->name('salary-confirmation-pdf');
        });
    });

    Route::get('reports/employee-wise-attendance-summery', [ReportController::class, 'employeeWiseAttendanceSummery'])->name('reports.employee_wise_attendance_summery');
    Route::get('reports/employee-wise-attendance-summery-reports', [ReportController::class, 'employeeWiseAttendanceSummeryReports'])->name('reports.employee_wise_attendance_summery_reports');
});

/*Routes for Notices */
Route::group(['prefix' => 'notice', 'middleware' => ['auth']], function () {

    Route::get('/notices', 'NoticeController@index')->name('notice.index');

    Route::controller(NoticeController::class)->name('notice.')->group(function () {

        Route::get('/notices/create', 'create')->name('create');
        Route::post('/notices/store', 'store')->name('store');
        Route::put('/update/notices/{notice:uuid}', 'update')->name('update');
        Route::delete('delete/notices/{notice:uuid}', 'destroy')->name('destroy');
    });
});

/*Routes for Message */
Route::group(['prefix' => 'message', 'middleware' => ['auth']], function () {

    Route::get('/new', 'MessageController@index')->name('message.index');
    Route::get('/sent', 'MessageController@sent')->name('message.sent');
    Route::get('/inbox', 'MessageController@inbox')->name('message.inbox');

    Route::controller(MessageController::class)->name('message.')->group(function () {

        Route::post('/messages/store', 'store')->name('store');
        Route::put('/update/messages/{message:uuid}', 'update')->name('update');
        Route::delete('delete/messages/{message:uuid}', 'destroy')->name('destroy');

        Route::post('/update/view-update', 'viewUpdate')->name('view-update');
    });
});

/*Routes for Reward Points */
Route::group(['prefix' => 'reward', 'middleware' => ['auth']], function () {

    Route::controller(RewardPointController::class)->name('reward.')->group(function () {

        Route::get('/point-settings', 'index')->name('index');
        Route::post('/point-settings-store', 'store')->name('store');

        Route::get('/point-categories', 'pointCategories')->name('point-categories');
        Route::post('/point-category-store', 'pointCategoryStore')->name('point-category-store');
        Route::put('/point-category-update/{uid:uuid}', 'update')->name('update');
        Route::delete('point-category-delete/{pointcat:uuid}', 'destroy')->name('destroy');

        /*Management Points*/
        Route::get('/management-points', 'managementPoints')->name('management-points');
        Route::post('/point-management-store', 'pointManagementStore')->name('point-management-store');
        Route::delete('/point-management-delete/{id}', 'pointManagementDelete')->name('point-management-delete');

        /*Collaborative Points*/
        Route::get('/collaborative-points', 'collaborativePoints')->name('collaborative-points');
        Route::post('/point-collaborative-store', 'pointCollaborativeStore')->name('point-collaborative-store');

        /*Collaborative Points*/
        Route::get('/attendance-points', 'attendancePoints')->name('attendance-points');

        /*Employee Points*/
        Route::get('/employee-points', 'employeePoints')->name('employee-points');
    });
});

/*Routes for Reward Points */
Route::group(['prefix' => 'project', 'middleware' => ['auth']], function () {

    Route::controller(ProjectManagementController::class)->name('project.')->group(function () {

        // Clients Routes
        Route::get('/clients', 'index')->name('index');
        Route::get('/clients/show', 'create')->name('create');
        Route::post('/clients/store', 'store')->name('store');
        Route::get('/clients/edit/{client:id}', 'edit')->name('edit');
        Route::put('/clients/update/{id}', 'update')->name('update');
        Route::delete('clients/delete/{client:uuid}', 'destroy')->name('destroy');

        // Projects
        Route::get('/projects', 'projectLists')->name('project-lists');
        Route::get('/projects/show', 'projectCreate')->name('project-create');
        Route::post('/projects/store', 'projectStore')->name('project-store');
        Route::get('/projects/edit/{project:id}', 'projectEdit')->name('project-edit');
        Route::put('/projects/update/{id}', 'projectUpdate')->name('project-update');
        Route::delete('projects/delete/{project:uuid}', 'projectDestroy')->name('project-destroy');
        // Backlogs
        Route::get('/get_backlogs', 'getBacklogs')->name('get-backlogs');
        Route::get('/backlogs', 'backLogs')->name('backlogs');
        Route::get('/backlogs/show', 'backlogTaskCreate')->name('backlog-create');
        Route::post('/backlogs/store/{project_id}', 'backlogStore')->name('backlog-store');
        Route::get('/backlogs/edit/{backlog:id}', 'backlogEdit')->name('backlog-edit');
        Route::put('/backlogs/update/{id}', 'backlogUpdate')->name('backlog-update');
        Route::delete('backlogs/delete/{backlog:uuid}', 'backlogDestroy')->name('backlog-destroy');
    });

    Route::controller(ProjectTasksController::class)->name('project.')->group(function () {
        // Backlogs Transfer
        Route::get('/transfer_tasks', 'transferTasks')->name('transfer-tasks');
        Route::post('/transfer_tasks/store', 'transferTasksStore')->name('transfer-tasks-store');
        // Manage Tasks
        Route::get('/manage_tasks', 'manageTasks')->name('manage-tasks');

        Route::get('/project_tasks/{project_id}', 'projectTasks')->name('project-tasks');
        Route::get('/project_tasks/show/{project_id}', 'projectTaskCreate')->name('project-task-create');
        Route::post('/project_tasks/store/{project_id}', 'projectTaskStore')->name('project-task-store');
        Route::get('/project_tasks/edit/{task:id}', 'projectTaskEdit')->name('project-task-edit');
        Route::put('/project_tasks/update/{task:id}', 'projectTaskUpdate')->name('project-task-update');

        // Project lead projects and tasks , sprints
        Route::get('/projects_lead_tasks/{emp_id}', 'projectsLeadTasks')->name('projects-lead-tasks');
        Route::get('/pm_project_all_tasks/{project_id}', 'pmProjectAllTasks')->name('pm-project-all-tasks');
        Route::get('/pm_project_all_tasks/show/{project_id}', 'pmProjectTaskCreate')->name('pm-project-task-create');
        Route::post('/pm_project_all_tasks/store/{project_id}', 'pmProjectTaskStore')->name('pm-project-task-store');
        Route::get('/pm_project_all_tasks/edit/{task:id}', 'pmProjectTaskEdit')->name('pm-project-task-edit');
        Route::put('/pm_project_all_tasks/update/{task:id}', 'pmProjectTaskUpdate')->name('pm-project-task-update');

        // Employee projects and tasks , sprints
        Route::get('/employee_projects/{emp_id}', 'employeeProjects')->name('employee-projects');
        Route::get('/empl_project_tasks/{project_id}', 'employeeProjectTasks')->name('employee-project-tasks');
        Route::get('/project_all_tasks/{project_id}', 'projectAllTasks')->name('project-all-tasks');
        Route::get('/empl_project_tasks/show/{project_id}', 'employeeProjectTaskCreate')->name('employee-project-task-create');
        Route::post('/empl_project_tasks/store/{project_id}', 'employeeProjectTaskStore')->name('employee-project-task-store');
        Route::get('/empl_project_tasks/edit/{task:id}', 'employeeProjectTaskEdit')->name('employee-project-task-edit');
        Route::put('/empl_project_tasks/update/{task:id}', 'employeeProjectTaskUpdate')->name('employee-project-task-update');

        // Employee Sprints
        Route::get('/empl_project_sprints/{project_id}', 'emplProjectSprints')->name('empl-project-sprints');
        Route::get('/empl_sprint_own_tasks/{sprint_id}', 'emplSprintOwnTasks')->name('empl-sprint-own-tasks');
        Route::get('/empl_kanban_board/{sprint_id}', 'emplKanbanBoard')->name('empl-kanban-board');
        Route::get('/empl_kanban_task_update', 'emplKanbanTaskUpdate')->name('empl-kanban-task-update');
        Route::get('/empl_sprint_all_tasks/{sprint_id}', 'emplSprintAllTasks')->name('empl-sprint-all-tasks');

        Route::get('/project_sprints/{project_id}', 'projectSprints')->name('project-sprints');
        Route::get('/sprint_tasks/{sprint_id}', 'sprintTasks')->name('sprint-tasks');

        // PM Sprints
        Route::get('/pm_project_sprints/{project_id}', 'pmProjectSprints')->name('pm-project-sprints');
        Route::get('/pm_sprint_tasks/{sprint_id}', 'pmSprintTasks')->name('pm-sprint-tasks');
        Route::get('/pm_kanban_board/{sprint_id}', 'pmKanbanBoard')->name('pm-kanban-board');
        Route::get('/kanban_task_update', 'kanbanTaskUpdate')->name('kanban-task-update');
    });

    Route::controller(ProjectSprintsController::class)->name('project.')->group(function () {
        // Sprints
        Route::post('/get_sprints', 'getSprints')->name('get-sprints');
        Route::get('/sprints', 'sprints')->name('sprints');
        Route::get('/sprints/show', 'sprintCreate')->name('sprint-create');
        Route::post('/sprints/store/{project_id}', 'sprintStore')->name('sprint-store');

        Route::get('/sprints/edit/{sprint:id}', 'sprintEdit')->name('sprint-edit');
        Route::post('/get_sprint_undone_tasks', 'getSprintUndoneTasks')->name('get-sprint-undone-tasks');
        Route::put('/sprint_update/{sprint_id}', 'sprintUpdate')->name('sprint-update');
        Route::delete('sprints/delete/{sprint:uuid}', 'sprintDestroy')->name('sprint-destroy');

        // Transfer sprint tasks to Backlogs
        Route::get('/transfer_sprint_tasks/{sprint_id}', 'transferSprintTasks')->name('transfer-sprint-tasks');
        Route::post('/transfer_sprint_tasks/store/{sprint_id}', 'transferSprintTasksStore')->name('transfer-sprint-tasks-store');
    });

    Route::controller(ProjectReportsController::class)->name('project.')->group(function () {
        // Reports
        Route::get('/reports', 'reports')->name('reports');
        Route::get('/reports/project_wise_report/{project_id}', 'projectWiseReport')->name('project-wise-report');

        Route::get('/reports/project_remaining_completed', 'projectRemainingCompleted')->name('project-remaining-completed');

        Route::get('/reports/project_all_employees_name', 'projectAllEmployeesName')->name('project-all-employees-name');
        Route::get('/reports/task_to_do_by_employee', 'taskToDoByEmployee')->name('task-to-do-by-employee');
        Route::get('/reports/task_in_progress_by_employee', 'taskInProgressByEmployee')->name('task-in-progress-by-employee');
        Route::get('/reports/task_done_by_employee', 'taskDoneByEmployee')->name('task-done-by-employee');

        Route::get('/reports/project_various_status_tasks', 'projectVariousStatusTasks')->name('project-various-status-tasks');

        Route::get('/reports/employee_project_lists', 'employeeProjectLists')->name('employee-projects-list');
        Route::get('/team_member_search', 'teamMemberSearch')->name('team-member-search');
        Route::get('/reports/get_employee_projects/{employee_id}', 'getEmployeeProjects')->name('get-employee-projects');
        Route::post('/reports/get_employee_project_tasks', 'getEmployeeProjectTasks')->name('get-employee-project-tasks');
    });
});

// Routes for Payroll
Route::group(['prefix' => 'payroll', 'middleware' => ['auth']], function () {

    Route::resource('salary-advance', SalaryAdvanceController::class);
    Route::get('salary-generate', [SalaryGenerateController::class, 'salaryGenerateForm'])->name('salary.generate-form');
    Route::post('salary-generate', [SalaryGenerateController::class, 'salaryGenerate'])->name('salary.generate');
    Route::get('manage-salaries', [SalaryGenerateController::class, 'employeeSalary'])->name('employee.salary');
    Route::get('empl/payslip/{uuid}', [SalaryGenerateController::class, 'employeePayslip'])->name('employee.payslip');
    Route::get('empl/payslip/{uuid}/pdf', [SalaryGenerateController::class, 'downloadPayslip'])->name('employee.payslip-pdf');
    Route::get('salary-chart/{uuid}', [SalaryGenerateController::class, 'salaryChart'])->name('salary.chart');
    Route::post('salary-approval/{uuid}', [SalaryGenerateController::class, 'salaryApproval'])->name('salary.approval');
    Route::delete('salary-sheet/{uuid}', [SalaryGenerateController::class, 'destroy'])->name('salary-sheet.destroy');

});

Route::group(['prefix' => 'tax-setup', 'middleware' => ['auth']], function () {
    Route::get('/', [TaxCalculationController::class, 'index'])->name('tax-setup.index');
    Route::post('/', [TaxCalculationController::class, 'store'])->name('tax-setup.store');
});
