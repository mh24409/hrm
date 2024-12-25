<?php

namespace Modules\Accounts\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Accounts\Entities\AccCoa;
use Modules\Accounts\Entities\AccOpeningBalance;
use Modules\Accounts\Entities\AccPredefineAccount;
use Modules\Accounts\Entities\FinancialYear;
use Modules\Accounts\Http\Traits\AccReportTrait;

class FinancialYearController extends Controller
{
    use AccReportTrait;

    // Apply middleware for permissions based on actions
    public function __construct()
    {
        $this->middleware('permission:read_financial_year')->only('index', 'show');
        $this->middleware('permission:create_financial_year')->only(['create', 'store']);
        $this->middleware('permission:update_financial_year')->only(['edit', 'update']);
        $this->middleware('permission:delete_financial_year')->only('destroy');
    }

    // Display a listing of the resource.
    public function index()
    {
        return view('accounts::financial-year.index', [
            'financialYears' => FinancialYear::paginate(10),
            'activeYears' => FinancialYear::where('status', 1)->get(),
        ]);
    }

    // Show the form for creating a new resource.
    public function store(Request $request)
    {
        $request->validate([
            'financial_year' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|after:start_date',
            'status' => 'required',
        ]);

        $financial_year = new FinancialYear();
        $financial_year->financial_year = $request->financial_year;
        $financial_year->start_date = $request->start_date;
        $financial_year->end_date = $request->end_date;
        $financial_year->status = $request->status;
        $financial_year->is_close = $request->status == 1 ? 0 : 1;
        $financial_year->save();
        Toastr::success('FinancialYear added successfully :)', 'Success');
        return redirect()->route('financial-years.index');
    }

    // Show the form for editing the specified resource.
    public function update(Request $request, $uuid)
    {
        $request->validate([
            'financial_year' => 'required',
            'status' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|after:start_date',
        ]);

        $financial_year = FinancialYear::where('uuid', $uuid)->firstOrFail();
        $financial_year->financial_year = $request->financial_year;
        $financial_year->start_date = $request->start_date;
        $financial_year->end_date = $request->end_date;
        $financial_year->status = $request->status;
        $financial_year->is_close = $request->status == 1 ? 0 : 1;
        $financial_year->save();
        Toastr::success('FinancialYear updated successfully :)', 'Success');
        return redirect()->route('financial-years.index');
    }

    // Remove the specified resource from storage.
    public function destroy($uuid)
    {
        // inactive last active year
        $last_active_year = FinancialYear::where('status', 0)->where('is_close', 1)->first();
        if ($last_active_year) {
            $last_active_year->status = 1;
            $last_active_year->is_close = 0;
            $last_active_year->save();
        }

        // delete opening balance
        $opening_balance = AccOpeningBalance::where('financial_year_id', $last_active_year?->id)->get();
        if ($opening_balance) {
            foreach ($opening_balance as $balance) {
                $balance->delete();
            }
        }

        FinancialYear::where('uuid', $uuid)->delete();
        Toastr::success('FinancialYear deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }

    // status change
    public function statusChange($uuid)
    {
        $financial_year = FinancialYear::where('uuid', $uuid)->firstOrFail();
        $financial_year->status = $financial_year->status == 1 ? 0 : 1;
        $financial_year->save();
        Toastr::success('FinancialYear status changed successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }

    //close financial year
    public function closeFinancialYear(Request $request)
    {

        $request->validate([
            'financial_year_id' => 'required',
        ]);

        $old_financial_year = FinancialYear::where('id', $request->financial_year_id)->first();

        $old_financial_year->is_close = 1;
        $old_financial_year->status = 0;
        $old_financial_year->save();

        return redirect()->back()->with('success', localize('Financial Year Closed Successfully'));
    }

    // reverse financial year
    public function reverseFinancialYear($id)
    {
        //current financial year
        $financial_year = FinancialYear::where('is_close', 0)->where('status', 1)->first();
        $financial_year->is_close = 1;
        $financial_year->status = 0;
        $financial_year->save();

        // old financial year
        $financial_year = FinancialYear::where('id', $id)->first();
        $financial_year->is_close = 0;
        $financial_year->status = 1;
        $financial_year->save();

        $opening_balances = AccOpeningBalance::where('financial_year_id', $id)->get();
        foreach ($opening_balances as $opening_balance) {
            $opening_balance->delete();
        }

        Toastr::success('Financial Year Reversed Successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }

    private function saveOpeningBalance($data, $type, $financial_year_id, $old_year_end_date, $balance)
    {
        if ($balance != 0) {
            $opening_balance = new AccOpeningBalance();
            $opening_balance->financial_year_id = $financial_year_id;
            $opening_balance->acc_coa_id = $data->id;
            $opening_balance->acc_subtype_id = null;
            $opening_balance->acc_subcode_id = null;
            $opening_balance->debit = $type == 1 ? $balance : 0;
            $opening_balance->credit = $type == 2 ? $balance : 0;
            $opening_balance->open_date = $old_year_end_date;
            $opening_balance->created_by = Auth::user()->id;
            $opening_balance->updated_by = Auth::user()->id;
            $opening_balance->save();
        }
    }

    // Get Opening Balance By Year
    private function getOpeningBalanceByYear($year_id, $coa_id)
    {
        $getOpeningBalance = AccOpeningBalance::where('financial_year_id', $year_id)->where('acc_coa_id', $coa_id)->get();

        $coaDetail = AccCoa::findOrFail($coa_id);
        $balanceResult = [];

        if (($coaDetail->acc_type_id == 1) || ($coaDetail->acc_type_id == 2)) {

            foreach ($getOpeningBalance as $key => $openingValue) {
                $debit = $openingValue->debit;
                $credit = $openingValue->credit;
                (float) $getResult = (float) $debit - (float) $credit;
                array_push($balanceResult, $getResult);
            }
        }

        if (($coaDetail->acc_type_id == 3) || ($coaDetail->acc_type_id == 4) || ($coaDetail->acc_type_id == 5)) {

            foreach ($getOpeningBalance as $key => $openingValue) {
                $debit = $openingValue->debit;
                $credit = $openingValue->credit;
                (float) $getResult = (float) $credit - (float) $debit;
                array_push($balanceResult, $getResult);
            }
        }

        $openingBalance = array_sum($balanceResult);
        return $openingBalance;
    }

    // Get Head Wise Data
    private function headWiseData($fromDate, $toDate)
    {
        $current_year = FinancialYear::where('status', true)->where('is_close', false)->first();
        $last_three_years = FinancialYear::where('status', false)->limit(3)->get();

        $level_two_assets = AccCoa::where('parent_id', 1)->where('is_active', true)->get();
        $level_three_assets = $this->getCoa(1, 3);
        $level_four_assets = $this->getCoa(1, 4);

        foreach ($level_four_assets as $asset) {
            $balance = number_format($this->getClosingBalance($fromDate, $toDate, $asset->id), 2, '.', '');
            $asset->setAttribute('balance', $balance);

            foreach ($last_three_years as $key => $year) {
                $year_balance = number_format($this->getOpeningBalanceByYear($year->id, $asset->id), 2, '.', '');
                $asset->setAttribute('yearbalance' . $key, $year_balance);
            }
        }

        // set sum balance on level2 and level 3 for showing blade
        foreach ($level_two_assets as $asset2) {
            $level2AssetBalance = 0;
            foreach ($level_three_assets as $asset3) {
                if ($asset3->parent_id == $asset2->id) {
                    $levelThreeBalance = number_format($level_four_assets->where('parent_id', $asset3->id)->sum('balance'), 2, '.', '');
                    $asset3->setAttribute('balance', $levelThreeBalance);

                    if ($asset2->parent_id == 1) {
                        $level2AssetBalance += $levelThreeBalance;
                        $asset2->setAttribute('balance', $level2AssetBalance);
                    }
                }
            }
        }

        // set sum of previous three years balance on level2 and level 3 for showing blade
        foreach ($last_three_years as $key => $year) {
            foreach ($level_two_assets as $asset2) {
                $level2YearBalance = 0;
                foreach ($level_three_assets as $asset3) {
                    if ($asset3->parent_id == $asset2->id) {
                        $levelThreeBalance = number_format($level_four_assets->where('parent_id', $asset3->id)->sum('yearbalance' . $key), 2, '.', '');
                        $asset3->setAttribute('year_balance' . $key, $levelThreeBalance);

                        if ($asset2->parent_id == 1) {
                            $level2YearBalance += $levelThreeBalance;
                            $asset2->setAttribute('year_balance' . $key, $level2YearBalance);
                        }
                    }
                }
            }
        }

        // calculation start for Liability
        $level_two_liabilities = AccCoa::where('parent_id', 4)->where('is_active', true)->get();
        $level_three_liabilities = $this->getCoa(4, 3);
        $level_four_liabilities = $this->getCoa(4, 4);

        foreach ($level_four_liabilities as $liability) {
            $balance = number_format($this->getClosingBalance($fromDate, $toDate, $liability->id), 2, '.', '');
            $liability->setAttribute('balance', $balance);

            foreach ($last_three_years as $key => $year) {
                $year_balance = number_format($this->getOpeningBalanceByYear($year->id, $liability->id), 2, '.', '');
                $liability->setAttribute('yearbalance' . $key, $year_balance);
            }
        }

        // set sum of liability on balance for level2 and level 3 to showing blade template
        foreach ($level_two_liabilities as $liability2) {
            $levelTwoBalance = 0;
            foreach ($level_three_liabilities as $liability3) {

                if ($liability3->parent_id == $liability2->id) {
                    $levelThreeBalance = number_format($level_four_liabilities->where('parent_id', $liability3->id)->sum('balance'), 2, '.', '');
                    $liability3->setAttribute('balance', $levelThreeBalance);

                    if ($liability2->parent_id == 4) {
                        $levelTwoBalance += $levelThreeBalance;
                        $liability2->setAttribute('balance', $levelTwoBalance);
                    }
                }
            }
        }

        $predefined_acc = AccPredefineAccount::first();
        $lastYearProfitLoss = AccCoa::find($predefined_acc->last_year_profit_loss_code);
        $currentYearProfitLoss = AccCoa::find($predefined_acc->current_year_profit_loss_code);

        $current_year_profit_loss = number_format($this->currentYearProfitLoss(), 2, '.', '');

        foreach ($last_three_years as $key => $year) {
            foreach ($level_two_liabilities as $liability2) {

                $level2YearBalance = 0;
                foreach ($level_three_liabilities as $liability3) {

                    if ($liability3->parent_id == $liability2->id) {
                        $levelThreeBalance = number_format($level_four_liabilities->where('parent_id', $liability3->id)->sum('yearbalance' . $key), 2, '.', '');
                        $liability3->setAttribute('year_balance' . $key, $levelThreeBalance);

                        if ($liability2->parent_id == 4) {
                            $level2YearBalance += $levelThreeBalance;
                            $liability2->setAttribute('year_balance' . $key, $level2YearBalance);
                        }
                    }
                }
            }
        }

        $level_four_liabilities->map(function ($item) use ($lastYearProfitLoss, $current_year_profit_loss) {
            if ($item->id == $lastYearProfitLoss->id) {
                $item->balance = $current_year_profit_loss;
            }
        });

        $liability_two_id = null;
        foreach ($level_three_liabilities as $liability3) {
            if ($liability3->id == $currentYearProfitLoss->parent_id) {
                $liability3->balance = $current_year_profit_loss;
                $liability_two_id = $liability3->parent_id;
            }
        }

        foreach ($level_two_liabilities as $liability2) {
            if ($liability2->id == $liability_two_id) {
                $liability2->balance += $current_year_profit_loss;
            }
        }

        $level_two_equities = AccCoa::where('parent_id', 5)->where('is_active', true)->get();
        $level_three_equities = $this->getCoa(5, 3);
        $level_four_equities = $this->getCoa(5, 4);

        foreach ($level_four_equities as $equity) {

            if ($predefined_acc->current_year_profit_loss_code == $equity->id) {
                $balance = number_format($this->getCurrentYearProfitLoss(), 2, '.', '');
            } else

            if ($predefined_acc->last_year_profit_loss_code == $equity->id) {
                $balance = number_format($this->getLastYearProfitLoss($predefined_acc->last_year_profit_loss_code), 2, '.', '');
            } else {
                $balance = number_format($this->getClosingBalance($fromDate, $toDate, $equity->id), 2, '.', '');
            }

            $equity->setAttribute('balance', $balance);

            foreach ($last_three_years as $key => $year) {
                $year_balance = number_format($this->getOpeningBalanceByYear($year->id, $equity->id), 2, '.', '');
                $equity->setAttribute('yearbalance' . $key, $year_balance);
            }
        }

        foreach ($level_two_equities as $equity2) {
            $levelTwoBalance = 0;

            foreach ($level_three_equities as $equity3) {

                if ($equity3->parent_id == $equity2->id) {
                    $levelThreeBalance = number_format($level_four_equities->where('parent_id', $equity3->id)->sum('balance'), 2, '.', '');
                    $equity3->setAttribute('balance', $levelThreeBalance);

                    if ($equity2->parent_id == 5) {
                        $levelTwoBalance += $levelThreeBalance;
                        $equity2->setAttribute('balance', $levelTwoBalance);
                    }
                }
            }
        }

        // set sum of previous three years balance on level2 and level 3 for showing blade
        foreach ($last_three_years as $key => $year) {
            foreach ($level_two_equities as $equity2) {
                $level2YearBalance = 0;
                foreach ($level_three_equities as $equity3) {
                    if ($equity3->parent_id == $equity2->id) {
                        $levelThreeBalance = number_format($level_four_equities->where('parent_id', $equity3->id)->sum('yearbalance' . $key), 2, '.', '');
                        $equity3->setAttribute('year_balance' . $key, $levelThreeBalance);

                        if ($equity2->parent_id == 5) {
                            $level2YearBalance += $levelThreeBalance;
                            $equity2->setAttribute('year_balance' . $key, $level2YearBalance);
                        }
                    }
                }
            }
        }
        return [
            'level_two_assets' => $level_two_assets,
            'level_three_assets' => $level_three_assets,
            'level_four_assets' => $level_four_assets,
            'level_two_liabilities' => $level_two_liabilities,
            'level_three_liabilities' => $level_three_liabilities,
            'level_four_liabilities' => $level_four_liabilities,
            'level_two_equities' => $level_two_equities,
            'level_three_equities' => $level_three_equities,
            'level_four_equities' => $level_four_equities,
            'current_year_profit_loss' => $current_year_profit_loss,
            'current_year' => $current_year,
            'last_three_years' => $last_three_years,
        ];
    }

    // get current year profit and loss
    private function getCurrentYearProfitLoss()
    {
        $current_year = FinancialYear::where('status', true)->where('is_close', false)->first();
        $level_four_incomes = $this->getCoa(3, 4);

        foreach ($level_four_incomes as $income) {
            $balance = $this->getPeriodicClosingBalance($current_year->start_date, $current_year->end_date, $income->id);
            $income->setAttribute('balance', $balance);
        }

        $total_income = $level_four_incomes->sum('balance');

        $level_four_expenses = $this->getCoa(2, 4);

        foreach ($level_four_expenses as $expenses) {
            $balance = $this->getPeriodicClosingBalance($current_year->start_date, $current_year->end_date, $expenses->id);
            $expenses->setAttribute('balance', $balance);
        }

        $total_expenses = $level_four_expenses->sum('balance');
        $profit_loss = $total_income - $total_expenses;
        return $profit_loss;
    }

    // get last year profit and loss
    private function getLastYearProfitAndLoss()
    {
        $last_year = FinancialYear::where('status', false)->where('is_close', true)->orderBy('id', 'desc')->first();

        $level_four_incomes = $this->getCoa(3, 4);

        foreach ($level_four_incomes as $income) {
            $balance = $this->getPeriodicClosingBalance($last_year->start_date, $last_year->end_date, $income->id);
            $income->setAttribute('balance', $balance);
        }

        $total_income = $level_four_incomes->sum('balance');

        $level_four_expenses = $this->getCoa(2, 4);

        foreach ($level_four_expenses as $expenses) {
            $balance = $this->getPeriodicClosingBalance($last_year->start_date, $last_year->end_date, $expenses->id);
            $expenses->setAttribute('balance', $balance);
        }

        $total_expenses = $level_four_expenses->sum('balance');
        $profit_loss = $total_income - $total_expenses;
        return $profit_loss;
    }

    // get last year profit and loss
    private function getLastYearProfitLoss($coa_id)
    {

        $openingBalance = AccOpeningBalance::where('acc_coa_id', $coa_id)->get();

        $coaDetail = AccCoa::find($coa_id);

        if (($coaDetail->acc_type_id == 1) || ($coaDetail->acc_type_id == 2)) {
            return $openingBalance->sum('debit') - $openingBalance->sum('credit');
        }

        if (($coaDetail->acc_type_id == 3) || ($coaDetail->acc_type_id == 4) || ($coaDetail->acc_type_id == 5)) {
            return $openingBalance->sum('credit') - $openingBalance->sum('debit');
        }
    }

    // calculate current year profit and loss
    public function currentYearProfitLoss()
    {
        $fiscalYear = FinancialYear::where('status', true)->where('is_close', false)->first();
        $start_date = $fiscalYear->start_date;
        $end_date = $fiscalYear->end_date;

        // calculation for income
        $level_two_incomes = AccCoa::where('parent_id', 3)->where('is_active', true)->get();
        $level_three_incomes = $this->getCoa(3, 3);
        $level_four_incomes = $this->getCoa(3, 4);

        $stockValuation = $this->stockValuation();
        $add_one_array_data_to_level_two = [
            "id" => 100000000000000000000000000,
            "uuid" => "af684dd7-1c7c-4013-8c8a-4a1e1694fe27",
            "account_code" => "0001",
            "account_name" => "Stock Valuation",
            "head_level" => 2,
            "parent_id" => 3,
            "acc_type_id" => 3,
            "is_cash_nature" => 0,
            "is_bank_nature" => 0,
            "is_budget" => 0,
            "is_depreciation" => 0,
            "depreciation_rate" => null,
            "is_subtype" => 0,
            "subtype_id" => null,
            "is_stock" => 0,
            "is_fixed_asset_schedule" => 0,
            "note_no" => null,
            "asset_code" => null,
            "dep_code" => null,
            "is_active" => 1,
            "created_by" => 39,
            "updated_by" => 39,
            "deleted_at" => null,
            "balance" => $stockValuation,
        ];

        $add_one_array_data_to_level_two = (object) $add_one_array_data_to_level_two;
        $level_two_incomes->push($add_one_array_data_to_level_two);

        foreach ($level_four_incomes as $income) {
            $balance = $this->getPeriodicClosingBalance($start_date, $end_date, $income->id);
            $income->setAttribute('balance', $balance);
        }

        $incomeBalance = 0;

        foreach ($level_two_incomes as $income2) {
            $level2IncomeBalance = 0;

            foreach ($level_three_incomes as $income3) {

                if ($income3->parent_id == $income2->id) {
                    $levelThreeBalance = $level_four_incomes->where('parent_id', $income3->id)->sum('balance');
                    $income3->setAttribute('balance', $levelThreeBalance);

                    if ($income2->parent_id == 3) {
                        $level2IncomeBalance += $levelThreeBalance;
                        $income2->setAttribute('balance', $level2IncomeBalance);
                    }
                }
            }

            $incomeBalance += $level2IncomeBalance;
        }

        // calculation for Expenses
        $level_two_expenses = AccCoa::where('parent_id', 2)->where('is_active', true)->get();
        $level_three_expenses = $this->getCoa(2, 3);
        $level_four_expenses = $this->getCoa(2, 4);

        foreach ($level_four_expenses as $expence) {

            $balance = $this->getClosingBalance($start_date, $end_date, $expence->id);

            $expence->setAttribute('balance', $balance);
        }

        $expenseBalance = 0;

        foreach ($level_two_expenses as $expense2) {
            $level2IncomeBalance = 0;

            foreach ($level_three_expenses as $expense3) {

                if ($expense3->parent_id == $expense2->id) {
                    $levelThreeBalance = $level_four_expenses->where('parent_id', $expense3->id)->sum('balance');
                    $expense3->setAttribute('balance', $levelThreeBalance);

                    if ($expense2->parent_id == 2) {
                        $level2IncomeBalance += $levelThreeBalance;
                        $expense2->setAttribute('balance', $level2IncomeBalance);
                    }
                }
            }

            $expenseBalance += $level2IncomeBalance;
        }

        $netProfit = ($incomeBalance + $stockValuation) - $expenseBalance;
        return $netProfit;
    }
}
