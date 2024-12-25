<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\AccPredefineAccount;
use Modules\Accounts\Entities\AccSubcode;
use Modules\Accounts\Entities\FinancialYear;
use Modules\Accounts\Entities\AccTransaction;
use Modules\Accounts\Entities\AccVoucher;
use Modules\Accounts\Entities\BudgetAllocation;
use Modules\Accounts\Http\Traits\AccReportTrait;
use Modules\HumanResource\Entities\SalaryGenerate;
use Modules\HumanResource\Entities\SalarySheetGenerate;

class AccountsController extends Controller {

    use AccReportTrait;

    public function accountantIndex(Request $request) {

        $start_date = $request->start_date ? $request->start_date : date('01-01-Y');
        $end_date   = $request->end_date ? $request->end_date : date('d-m-Y');

        //get all income
        $level_four_incomes = $this->getCoa(3, 4);
        $incomeBalance      = 0;
        foreach ($level_four_incomes as $income) {
            $incomeBalance += $this->getClosingBalance($start_date, $end_date, $income->id);
        }

        //get all expencess
        $level_four_expences = $this->getCoa(2, 4);
        $expenseBalance      = 0;
        foreach ($level_four_expences as $expence) {
            $expenseBalance += $this->getClosingBalance($start_date, $end_date, $expence->id);
        }

        //get all payroll
        $salary_sheets = SalarySheetGenerate::whereNotNull('id');
        if($request->start_date){
            $salary_sheets = $salary_sheets->whereBetween('start_date', [$request->start_date , $request->end_date]);
        }
        $salary_sheets = $salary_sheets->where('is_approved', true)->get();
        $payroll       = 0;
        foreach($salary_sheets as $salary_sheet) {
            $payroll += SalaryGenerate::where('salary_month_year', $salary_sheet->name)->sum('net_salary');
        }

        //total budget and used
        $fYear = FinancialYear::latest()->where('status', true)->first();
        $budget_allocations = BudgetAllocation::where('financial_year_id', $fYear->id)->with('acc_quarter')->get();
        foreach ($budget_allocations as $key => $allocation) {
            $vouchers = AccTransaction::where('acc_coa_id', $allocation->acc_coa_id)->where('financial_year_id', $fYear->id)->get();
            $expence  = $vouchers->sum('debit') - $vouchers->sum('credit');
            $allocation->setAttribute('expence', $expence);
            $allocation->setAttribute('verience', ($allocation->budget_amount - $expence));
        }

        $budgetChatData = [];
        foreach($budget_allocations->groupBy('acc_quarter_id') as $allocate){
            $budgetChatData[] = [
                'Quarter' => $allocate[0]->acc_quarter?->quarter,
                'total_budget' => $allocate->sum('budget_amount'),
                'used_budget' => $allocate->sum('expence'),
            ];
        };

        //get acc recievable amount
        $predefine_acc = AccPredefineAccount::first();
        $acc_recieveable_coa = AccCoa::where('id', $predefine_acc->sales_code)->first();
        $account_recieveables = AccCoa::where('parent_id', $acc_recieveable_coa->parent_id)->get();
        $recievable_balance = 0;
        foreach ($account_recieveables as $recievable) {
            $recievable_balance += $this->getClosingBalance($start_date, $end_date, $recievable->id);
        }

        //get acc payable amount
        $acc_payable_coa = AccCoa::where('id', $predefine_acc->supplier_code)->first();
        $account_payables = AccCoa::where('parent_id', $acc_payable_coa->parent_id)->get();
        $payable_balance = 0;
        foreach ($account_payables as $payable) {
            $payable_balance += $this->getClosingBalance($start_date, $end_date, $payable->id);
        }
        $contra = AccVoucher::whereNotNull('id');
        if($request->start_date) {
            $contra = $contra->whereBetween('voucher_date', [$request->start_date, $request->end_date]);
        }
        $contra = $contra->where('voucher_type', 3)->count();

        return view('accounts::home', compact('incomeBalance', 'expenseBalance', 'request', 'payroll', 'budgetChatData', 'recievable_balance', 'payable_balance', 'contra'));

    }

    /**
     * Get the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function getSubtypeByCode($id) {
        $htm     = '';
        $account = AccCoa::where('id', $id)->whereNotNull('subtype_id')->first();

        if ($account) {
            $subcodes = AccSubcode::where('acc_subtype_id', $account->subtype_id)->get();

            foreach ($subcodes as $sc) {
                $htm .= '<option value="' . $sc->id . '" >' . $sc->name . '</option>';
            }

        }

        return response()->json($htm);
    }

    /**
     * Get the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function getSubtypeById($id) {
        $debitvcode = AccCoa::where('id', $id)->first();
        $data       = ['subType' => $debitvcode->subtype_id];
        return response()->json($data);
    }

}
