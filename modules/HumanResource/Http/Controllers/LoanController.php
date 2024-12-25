<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Modules\HumanResource\Entities\Loan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use Modules\HumanResource\DataTables\LoanDataTable;
use Modules\HumanResource\DataTables\LoanDisburseReportDataTable;
use Modules\HumanResource\Entities\Employee;

class LoanController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:read_loan')->only(['index']);
        $this->middleware('permission:create_loan', ['only' => ['store']]);
        $this->middleware('permission:update_loan', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_loan', ['only' => ['destroy']]);
        $this->middleware('permission:read_loan_disburse_report', ['only' => ['loan_disburse_report']]);
        $this->middleware('permission:read_employee_wise_loan', ['only' => ['employeeLoan', 'employeeLoanReport']]);
        $this->middleware('permission:read_loan_report', ['only' => ['loanReportForm']]);
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(LoanDataTable $dataTable)
    {
        $employees = Employee::where('is_active', true)->get();
        $supervisors = Employee::where('is_supervisor', true)->get();
        return $dataTable->render('humanresource::loan.index', compact('employees', 'supervisors'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'employee_id' => 'required',
                'amount'      => 'required',
                'approved_date' => 'required',
                'repayment_start_date' => 'required',
                'interest_rate' => 'required',
                'installment_period' => 'required',
                'repayment_amount' => 'required',
                'installment' => 'required',
                'is_active' => 'required',
            ],
            [
                'employee_id.required' => 'The employee field is required.',
                'amount.required' => 'The amount field is required.',
                'approved_date.required' => 'The approved date field is required.',
                'repayment_start_date.required' => 'The repayment start date field is required.',
                'interest_rate.required' => 'The interest rate field is required.',
                'installment_period.required' => 'The installment period field is required.',
                'repayment_amount.required' => 'The repayment amount field is required.',
                'installment.required' => 'The installment field is required.',
                'is_active.required' => 'The status field is required.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $loan = new Loan();
        $loan->fill($request->all());
        $loan->save();
        Toastr::success('Loan added successfully :)', 'Success');
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($uuid)
    {
        $loan      = Loan::where('uuid', $uuid)->firstOrFail();
        $employees = Employee::where('is_active', true)->get();
        $supervisors = Employee::where('is_supervisor', true)->get();

        return view('humanresource::loan.edit', [
            'employees' => $employees,
            'supervisors' => $supervisors,
            'loan'      => $loan,
        ]);
    }

    public function update(Request $request, $uuid)
    {
        $loan = Loan::where('uuid', $uuid)->firstOrFail();
        $loan->fill($request->all());
        $loan->update();

        Toastr::success('Loan Updated successfully :)', 'Success');
        return redirect()->back();
    }

    public function destroy($uuid)
    {
        Loan::where('uuid', $uuid)->delete();
        Toastr::success('Loan Deleted successfully :)', 'Success');
        return response()->json(['success' => 'success']);
    }

    public function loanReportForm(Request $request)
    {

        $loans = Loan::whereNotNull('id');
        if ($request->employee_id) {
            $loans = $loans->where('employee_id', $request->employee_id);
        }
        if ($request->start_date && $request->end_date) {
            $loans->whereBetween('approved_date', [$request->start_date, $request->end_date]);
        }

        $employees = Employee::where('is_active', true)->get();
        $supervisors = Employee::where('is_supervisor', true)->get();
        $loans = $loans->get();
        $total_loans = $loans->sum('amount');
        $total_amount = $loans->sum('repayment_amount');

        return view('humanresource::loan.report', [
            'employees' => $employees,
            'supervisors' => $supervisors,
            'loans' => $loans,
            'total_loans' => $total_loans,
            'total_amount' => $total_amount,
            'request' => $request,
        ]);
    }

    public function loan_disburse_report(LoanDisburseReportDataTable $dataTable)
    {
        $employees = Employee::where('is_active', true)->get();
        $supervisors = Employee::where('is_supervisor', true)->get();
        return $dataTable->render('humanresource::loan.reports.loan_disburse', compact('employees', 'supervisors'));
    }

    public function employeeLoan()
    {
        $employees = Employee::where('is_active', true)->where('is_left', false)->get();

        return view('humanresource::loan.employee', [
            'employees' => $employees,
        ]);
    }

    public function employeeLoanReport(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
        ]);

        $string = explode(' - ', $request->date);
        $fromDate = $string[0];
        $toDate = $string[1];
        $fromDate = Carbon::createFromFormat('m/d/Y', $fromDate)->format('Y-m-d');
        $toDate = Carbon::createFromFormat('m/d/Y', $toDate)->format('Y-m-d');
        $loans = Loan::where('employee_id', $request->employee_id)->whereBetween('approved_date', [$fromDate, $toDate])->get();
        $employee = Employee::find($request->employee_id);
        return view('humanresource::loan.reports.employee-report', compact('loans', 'employee', 'toDate', 'fromDate'));
    }
}
