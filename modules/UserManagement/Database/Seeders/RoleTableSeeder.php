<?php

namespace Modules\UserManagement\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\UserManagement\Entities\PerMenu;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $superAdmin = Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $employee = Role::create([
            'name' => 'Employee',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $permissions = [
            'Dashboard' => [
                'read_dashboard',
            ],
            'Human Resource' => [
                'read_human_resource_menu',
            ],
            'Department' => [
                'create_department',
                'read_department',
                'update_department',
                'delete_department',
            ],
            'Employee' => [
                'create_employee',
                'read_employee',
                'update_employee',
                'delete_employee',
                'Employee Status' => [
                    'create_employee_status',
                    'read_employee_status',
                    'update_employee_status',
                    'delete_employee_status',
                ],
            ],
            'Payroll' => [
                'create_payroll',
                'read_payroll',
                'update_payroll',
                'delete_payroll',
            ],
            'Loan' => [
                'create_loan',
                'read_loan',
                'update_loan',
                'delete_loan',
            ],
            'Bank' => [
                'create_bank',
                'read_bank',
                'update_bank',
                'delete_bank',
            ],
            'Setup Rules' => [
                'create_setup_rules',
                'read_setup_rules',
                'update_setup_rules',
                'delete_setup_rules',
            ],
            'Leave' => [
                'create_leave',
                'read_leave',
                'update_leave',
                'delete_leave',
            ],
            'Attendance' => [
                'create_attendance',
                'read_attendance',
                'update_attendance',
                'delete_attendance',
                'Manage' => [
                    'attendance_management',
                ]
            ],
            'Award' => [
                'create_award',
                'read_award',
                'update_award',
                'delete_award',
            ],
            'Id Card' => [
                'create_id_card',
                'read_id_card',
                'update_id_card',
                'delete_id_card',
            ],
            'Reports' => [
                'create_reports',
                'read_reports',
                'update_reports',
            ],
            'Activity Log' => [
                'create_activity_log',
                'read_activity_log',
                'update_activity_log',
                'delete_activity_log',
            ],
            'Attendance Report' => [
                'create_attendance_report',
                'read_attendance_report',
                'update_attendance_report',
                'delete_attendance_report',
                'Attendance Summary' => [
                    'create_attendance_summary',
                    'read_attendance_summary',
                    'update_attendance_summary',
                    'delete_attendance_summary',
                ],
            ],
            'Employee Report' => [
                'create_employee_report',
                'read_employee_report',
                'update_employee_report',
                'delete_employee_report',
            ],
            'Job Card Report' => [
                'create_job_card_report',
                'read_job_card_report',
                'update_job_card_report',
                'delete_job_card_report',
            ],
            'Contract Renewal Report' => [
                'create_contract_renewal_report',
                'read_contract_renewal_report',
                'update_contract_renewal_report',
                'delete_contract_renewal_report',
            ],
            'Allowance Report' => [
                'create_allowance_report',
                'read_allowance_report',
                'update_allowance_report',
                'delete_allowance_report',
            ],
            'Deduction Report' => [
                'create_deduction_report',
                'read_deduction_report',
                'update_deduction_report',
                'delete_deduction_report',
            ],
            'Leave Report' => [
                'create_leave_report',
                'read_leave_report',
                'update_leave_report',
                'delete_leave_report',
            ],
            'Payroll Report' => [
                'create_payroll_report',
                'read_payroll_report',
                'update_payroll_report',
                'delete_payroll_report',
            ],
            'Accounts' => [
                'create_accounts',
                'read_accounts',
                'update_accounts',
                'delete_accounts',
            ],
            'Financial Year' => [
                'create_financial_year',
                'read_financial_year',
                'update_financial_year',
                'delete_financial_year',
            ],
            'Opening Balance' => [
                'create_opening_balance',
                'read_opening_balance',
                'update_opening_balance',
                'delete_opening_balance',
            ],
            'Chart of Accounts' => [
                'create_chart_of_accounts',
                'read_chart_of_accounts',
                'update_chart_of_accounts',
                'delete_chart_of_accounts',
            ],
            'Predefine Accounts' => [
                'read_predefine_accounts',
                'create_predefine_accounts',
                'update_predefine_accounts',
                'delete_predefine_accounts',
            ],
            'Sub Account' => [
                'create_sub_account',
                'read_sub_account',
                'update_sub_account',
                'delete_sub_account',
            ],
            'Voucher' => [
                'create_voucher',
                'read_voucher',
                'update_voucher',
                'delete_voucher',
            ],
            'Voucher Approval' => [
                'create_voucher_approval',
                'read_voucher_approval',
                'update_voucher_approval',
                'delete_voucher_approval',
            ],
            'Account Report' => [
                'create_account_report',
                'read_account_report',
                'update_account_report',
                'delete_account_report',
            ],
            'Cash Account' => [
                'create_cash_book',
                'read_cash_book',
                'update_cash_book',
                'delete_cash_book',
            ],
            'Bank Account' => [
                'create_bank_book',
                'read_bank_book',
                'update_bank_book',
                'delete_bank_book',
            ],
            'Day Account' => [
                'create_day_book',
                'read_day_book',
                'update_day_book',
                'delete_day_book',
            ],
            'Control Ledger' => [
                'create_control_ledger',
                'read_control_ledger',
                'update_control_ledger',
                'delete_control_ledger',
            ],
            'General Ledger' => [
                'create_general_ledger',
                'read_general_ledger',
                'update_general_ledger',
                'delete_general_ledger',
            ],
            'Sub Ledger' => [
                'create_sub_ledger',
                'read_sub_ledger',
                'update_sub_ledger',
                'delete_sub_ledger',
            ],
            'Note Ledger' => [
                'create_note_ledger',
                'read_note_ledger',
                'update_note_ledger',
                'delete_note_ledger',
            ],
            'Receipt Payment' => [
                'create_receipt_payment',
                'read_receipt_payment',
                'update_receipt_payment',
                'delete_receipt_payment',
            ],
            'Trail Balance' => [
                'create_trail_balance',
                'read_trail_balance',
                'update_trail_balance',
                'delete_trail_balance',
            ],
            'Profit Loss' => [
                'create_profit_loss',
                'read_profit_loss',
                'update_profit_loss',
                'delete_profit_loss',
            ],
            'Balance Sheet' => [
                'create_balance_sheet',
                'read_balance_sheet',
                'update_balance_sheet',
                'delete_balance_sheet',
            ],
            'Report' => [
                'create_report',
                'read_report',
                'update_report',
                'delete_report',
            ],
            'Setting' => [
                'create_setting',
                'read_setting',
                'update_setting',
                'delete_setting',
            ],
            'Software Setup' => [
                'create_software_setup',
                'read_software_setup',
                'update_software_setup',
                'delete_software_setup',
            ],
            'Application' => [
                'create_application',
                'read_application',
                'update_application',
                'delete_application',
            ],
            'App Setting' => [
                'create_apps_setting',
                'read_apps_setting',
                'update_apps_setting',
                'delete_apps_setting',
            ],
            'Currency' => [
                'create_currency',
                'read_currency',
                'update_currency',
                'delete_currency',
            ],
            'Mail Setup' => [
                'create_mail_setup',
                'read_mail_setup',
                'update_mail_setup',
                'delete_mail_setup',
            ],
            'SMS Setup' => [
                'create_sms_setup',
                'read_sms_setup',
                'update_sms_setup',
                'delete_sms_setup',
            ],
            'Password Setting' => [
                'create_password_setting',
                'read_password_setting',
                'update_password_setting',
                'delete_password_setting',
            ],
            'Language' => [
                'create_language',
                'read_language',
                'update_language',
                'delete_language',
                'create_add_language',
                'read_add_language',
                'update_add_language',
                'delete_add_language',
            ],
            'Language List' => [
                'create_language_list',
                'read_language_list',
                'update_language_list',
                'delete_language_list',
            ],
            'Language Strings' => [
                'create_language_strings',
                'read_language_strings',
                'update_language_strings',
                'delete_language_strings',
            ],
            'User Management' => [
                'create_user_management',
                'read_user_management',
                'update_user_management',
                'delete_user_management',
            ],
            'Role List' => [
                'create_role_list',
                'read_role_list',
                'update_role_list',
                'delete_role_list',
            ],
            'User List' => [
                'create_user_list',
                'read_user_list',
                'update_user_list',
                'delete_user_list',
            ],
            'Inactive Employees List' => [
                'create_inactive_employees_list',
                'read_inactive_employees_list',
                'update_inactive_employees_list',
                'delete_inactive_employees_list',
            ],
            'Salary Setup' => [
                'create_salary_setup',
                'read_salary_setup',
                'update_salary_setup',
                'delete_salary_setup',
            ],
            'Salary Advance' => [
                'create_salary_advance',
                'read_salary_advance',
                'update_salary_advance',
                'delete_salary_advance',
            ],
            'Salary Generate' => [
                'create_salary_generate',
                'read_salary_generate',
                'update_salary_generate',
                'delete_salary_generate',
            ],
            'Manage Employee Salary' => [
                'create_manage_employee_salary',
                'read_manage_employee_salary',
                'update_manage_employee_salary',
                'delete_manage_employee_salary',
            ],
            'Positions' => [
                'create_positions',
                'read_positions',
                'update_positions',
                'delete_positions',
            ],
            'Weekly Holiday' => [
                'create_weekly_holiday',
                'read_weekly_holiday',
                'update_weekly_holiday',
                'delete_weekly_holiday',
            ],
            'Holiday' => [
                'create_holiday',
                'read_holiday',
                'update_holiday',
                'delete_holiday',
            ],
            'Leave Type' => [
                'create_leave_type',
                'read_leave_type',
                'update_leave_type',
                'delete_leave_type',
            ],
            'Leave Generate' => [
                'create_leave_generate',
                'read_leave_generate',
                'update_leave_generate',
                'delete_leave_generate',
            ],
            'Leave Approval' => [
                'create_leave_approval',
                'read_leave_approval',
                'update_leave_approval',
                'delete_leave_approval',
            ],
            'Leave Application' => [
                'create_leave_application',
                'read_leave_application',
                'update_leave_application',
                'delete_leave_application',
            ],
            'Monthly Attendance' => [
                'create_monthly_attendance',
                'read_monthly_attendance',
                'update_monthly_attendance',
                'delete_monthly_attendance',
            ],
            'Missing Attendance' => [
                'create_missing_attendance',
                'read_missing_attendance',
                'update_missing_attendance',
                'delete_missing_attendance',
            ],
            'Quarter' => [
                'create_quarter',
                'read_quarter',
                'update_quarter',
                'delete_quarter',
            ],
            'Subtype' => [
                'create_subtype',
                'read_subtype',
                'update_subtype',
                'delete_subtype',
            ],
            'Debit Voucher' => [
                'create_debit_voucher',
                'read_debit_voucher',
                'update_debit_voucher',
                'delete_debit_voucher',
                'Debit Voucher Reverse' => [
                    'create_debit_voucher_reverse',
                ],
            ],
            'Credit Voucher' => [
                'create_credit_voucher',
                'read_credit_voucher',
                'update_credit_voucher',
                'delete_credit_voucher',
                'Credit Voucher Reverse' => [
                    'create_credit_voucher_reverse',
                ],
            ],
            'Contra Voucher' => [
                'create_contra_voucher',
                'read_contra_voucher',
                'update_contra_voucher',
                'delete_contra_voucher',
                'Contra Voucher Reverse' => [
                    'create_contra_voucher_reverse',
                ],
            ],
            'Journal Voucher' => [
                'create_journal_voucher',
                'read_journal_voucher',
                'update_journal_voucher',
                'delete_journal_voucher',
                'Journal Voucher Reverse' => [
                    'create_journal_voucher_reverse',
                ],
            ],
            'Attendance Details Report' => [
                'create_attendance_details_report',
                'read_attendance_details_report',
                'update_attendance_details_report',
                'delete_attendance_details_report',
            ],
            'Budget Allocation' => [
                'create_budget_allocation',
                'read_budget_allocation',
                'update_budget_allocation',
                'delete_budget_allocation',
            ],
            'Budget Request' => [
                'create_budget_request',
                'read_budget_request',
                'update_budget_request',
                'delete_budget_request',
            ],
            'Budget Allocation Report' => [
                'create_budget_allocation_report',
                'read_budget_allocation_report',
                'update_budget_allocation_report',
                'delete_budget_allocation_report',
            ],
            'Budget Allocation Menu' => [
                'create_budget_allocation_menu',
                'read_budget_allocation_menu',
                'update_budget_allocation_menu',
                'delete_budget_allocation_menu',
            ],
            'Factory Reset' => [
                'create_factory_reset',
                'read_factory_reset',
                'update_factory_reset',
                'delete_factory_reset',
            ],
            'Stock Management' => [
                'create_stock_management',
                'read_stock_management',
                'update_stock_management',
                'delete_stock_management',
            ],
            'Opening Stock' => [
                'create_opening_stock',
                'read_opening_stock',
                'update_opening_stock',
                'delete_opening_stock',
            ],
            'Stock Adjustment' => [
                'create_stock_adjustment',
                'read_stock_adjustment',
                'update_stock_adjustment',
                'delete_stock_adjustment',
            ],
            'Stock Report' => [
                'create_stock_report',
                'read_stock_report',
                'update_stock_report',
                'delete_stock_report',
            ],
            'Supplier Wise Sale Profit' => [
                'create_supplier_wise_sale_profit',
                'read_supplier_wise_sale_profit',
                'update_supplier_wise_sale_profit',
                'delete_supplier_wise_sale_profit',
            ],
            'Journal Voucher Reverse' => [
                'create_journal_voucher_reverse',
                'read_journal_voucher_reverse',
                'update_journal_voucher_reverse',
                'delete_journal_voucher_reverse',
            ],
            'Credit Voucher Reverse' => [
                'create_credit_voucher_reverse',
                'read_credit_voucher_reverse',
                'update_credit_voucher_reverse',
                'delete_credit_voucher_reverse',
            ],
            'Debit Voucher Reverse' => [
                'create_debit_voucher_reverse',
                'read_debit_voucher_reverse',
                'update_debit_voucher_reverse',
                'delete_debit_voucher_reverse',
            ],
            'Contra Voucher Reverse' => [
                'create_contra_voucher_reverse',
                'read_contra_voucher_reverse',
                'update_contra_voucher_reverse',
                'delete_contra_voucher_reverse',
            ],
            'Sub Departments' => [
                'create_sub_departments',
                'read_sub_departments',
                'update_sub_departments',
                'delete_sub_departments',
            ],
            'Loan Disburse Report' => [
                'create_loan_disburse_report',
                'read_loan_disburse_report',
                'update_loan_disburse_report',
                'delete_loan_disburse_report',
            ],
            'Employee Loan' => [
                'create_employee_wise_loan',
                'read_employee_wise_loan',
                'update_employee_wise_loan',
                'delete_employee_wise_loan',
            ],
            'Employee Attendance' => [
                'create_employee_wise_attendance',
                'read_employee_wise_attendance',
                'update_employee_wise_attendance',
                'delete_employee_wise_attendance',
            ],
            'HRM Setup' => [
                'create_hrm_setup',
                'read_hrm_setup',
                'update_hrm_setup',
                'delete_hrm_setup',
            ],
            'Backup And Reset' => [
                'create_backup_and_reset',
                'read_backup_and_reset',
                'update_backup_and_reset',
                'delete_backup_and_reset',
            ],
            'Notice' => [
                'create_notice',
                'read_notice',
                'update_notice',
                'delete_notice',
            ],

            'Reward Points' => [
                'create_reward_points',
                'read_reward_points',
                'update_reward_points',
                'delete_reward_points',
                'Point Settings' => [
                    'create_point_settings',
                    'read_point_settings',
                    'update_point_settings',
                    'delete_point_settings',
                ],
                'Point Categories' => [
                    'create_point_categories',
                    'read_point_categories',
                    'update_point_categories',
                    'delete_point_categories',
                ],
                'Management Points' => [
                    'create_management_points',
                    'read_management_points',
                    'update_management_points',
                    'delete_management_points',
                ],
                'Collaborative Points' => [
                    'create_collaborative_points',
                    'read_collaborative_points',
                    'update_collaborative_points',
                    'delete_collaborative_points',
                ],
                'Employee Points' => [
                    'create_employee_points',
                    'read_employee_points',
                    'update_employee_points',
                    'delete_employee_points',
                ],
                'Attendance Points' => [
                    'create_attendance_points',
                    'read_attendance_points',
                    'update_attendance_points',
                    'delete_attendance_points',
                ],
            ],
            'Recruitment' => [
                'create_recruitment',
                'read_recruitment',
                'update_recruitment',
                'delete_recruitment',
                'Candidate List' => [
                    'create_candidate_list',
                    'read_candidate_list',
                    'update_candidate_list',
                    'delete_candidate_list',
                ],
                'Candidate Shortlist' => [
                    'create_candidate_shortlist',
                    'read_candidate_shortlist',
                    'update_candidate_shortlist',
                    'delete_candidate_shortlist',
                ],
                'Interview' => [
                    'create_interview',
                    'read_interview',
                    'update_interview',
                    'delete_interview',
                ],
                'Candidate Selection' => [
                    'create_candidate_selection',
                    'read_candidate_selection',
                    'update_candidate_selection',
                    'delete_candidate_selection',
                ],
            ],
            'Project Management' => [
                'create_project_management',
                'read_project_management',
                'update_project_management',
                'delete_project_management',
                'Clients' => [
                    'create_clients',
                    'read_clients',
                    'update_clients',
                    'delete_clients',
                ],
                'Projects' => [
                    'create_projects',
                    'read_projects',
                    'update_projects',
                    'delete_projects',
                ],
                'Task' => [
                    'create_task',
                    'read_task',
                    'update_task',
                    'delete_task',
                ],
                'Sprint' => [
                    'create_sprint',
                    'read_sprint',
                    'update_sprint',
                    'delete_sprint',
                ],
                'Manage Tasks' => [
                    'create_manage_masks',
                    'read_manage_masks',
                    'update_manage_masks',
                    'delete_manage_masks',
                ],
                'Reports' => [
                    'create_project_reports',
                    'read_project_reports',
                    'update_project_reports',
                    'delete_project_reports',
                ],
                'Project Lists' => [
                    'create_project_lists',
                    'read_project_lists',
                    'update_project_lists',
                    'delete_project_lists',
                ],
                'Team Member' => [
                    'create_team_member',
                    'read_team_member',
                    'update_team_member',
                    'delete_team_member',
                ],
            ],
            'Procurement' => [
                'create_procurement',
                'read_procurement',
                'update_procurement',
                'delete_procurement',
                'Request' => [
                    'create_request',
                    'read_request',
                    'update_request',
                    'delete_request',
                ],
                'Quotation' => [
                    'create_quotation',
                    'read_quotation',
                    'update_quotation',
                    'delete_quotation',
                ],
                'Bid Analysis' => [
                    'create_bid_analysis',
                    'read_bid_analysis',
                    'update_bid_analysis',
                    'delete_bid_analysis',
                ],
                'Purchase Order' => [
                    'create_purchase_order',
                    'read_purchase_order',
                    'update_purchase_order',
                    'delete_purchase_order',
                ],
                'Good Received' => [
                    'create_goods_received',
                    'read_goods_received',
                    'update_goods_received',
                    'delete_goods_received',
                ],
                'Vendors' => [
                    'create_vendors',
                    'read_vendors',
                    'update_vendors',
                    'delete_vendors',
                ],
                'Committees' => [
                    'create_committees',
                    'read_committees',
                    'update_committees',
                    'delete_committees',
                ],
                'Units' => [
                    'create_units',
                    'read_units',
                    'update_units',
                    'delete_units',
                ],
            ],
            'Adhoc Report' => [
                'create_adhoc_report',
                'read_adhoc_report',
                'update_adhoc_report',
                'delete_adhoc_report',
            ],
            'Employee Performance' => [
                'create_employee_performance',
                'read_employee_performance',
                'update_employee_performance',
                'delete_employee_performance',
            ],
            'Messages' => [
                'create_messages',
                'read_messages',
                'update_messages',
                'delete_messages',
            ],
        ];

        foreach ($permissions as $menu => $sub_permissions) {
            $menuModel = PerMenu::firstOrCreate([
                'menu_name' => $menu,
            ]);

            if (is_array($sub_permissions)) {
                foreach ($sub_permissions as $key => $permission) {
                    // Check if $permission is an array (indicating nested sub-permissions)
                    if (is_array($permission)) {
                        // If it's an array, treat it as a submenu with multiple permissions
                        $subPerMenu = PerMenu::firstOrCreate([
                            'parentmenu_id' => $menuModel->id,
                            'menu_name' => $key,
                        ]);

                        // Loop through the sub-permissions
                        foreach ($permission as $subPermission) {
                            Permission::firstOrCreate([
                                'name' => $subPermission,
                                'per_menu_id' => $subPerMenu->id,
                            ])->assignRole($superAdmin);
                        }
                    } else {
                        // If it's not an array, treat it as a single permission
                        Permission::firstOrCreate([
                            'name' => $permission,
                            'per_menu_id' => $menuModel->id,
                        ])->assignRole($superAdmin);
                    }
                }
            } else {
                // If $sub_permissions is not an array, treat it as a single permission
                Permission::firstOrCreate([
                    'name' => $sub_permissions,
                    'per_menu_id' => $menuModel->id,
                ])->assignRole($superAdmin);
            }
        }
    }
}
