@extends('backend.layouts.app')
@section('title', localize('salary_approval'))
@section('content')
    @include('humanresource::payroll_header')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header d-print-none">
            <div class="text-center">
                <div>
                    <h4 class="fs-20 fw-semi-bold mb-0">{{ localize('payroll_posting_sheet_for') }}
                        {{ \Carbon\Carbon::parse($salary_sheet->name)->format('F, Y') }}</h4>
                    @if ($salary_sheet->is_approved == true)
                        <h3>(<span class="fw-semi-bold text-success">{{ localize('approved') }}</span>)</h3>
                    @else
                        <h3>(<span class="fw-semi-bold text-danger">Not {{ localize('approved') }}</span>)</h3>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('backend.layouts.common.validation')
            <div class="row">
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-secondary text-center fw-semibold text-white">
                            <td colspan="">{{ localize('description') }}</td>
                            <td colspan="2">{{ localize('amounts') }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="fw-semi-bold">{{ localize('debit') }}</td>
                            <td class="fw-semi-bold">{{ localize('credit') }}</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="text-align: center;">
                            <th style="text-align: center;">{{ localize('gross_salary') }}</th>
                            <td>{{ @app_setting()->currency?->symbol }} {{ number_format($gross_salary, 2) }}</td>
                            <td></td>
                        </tr>
                        <tr style="text-align: center;">
                            <th style="text-align: center;">{{ localize('net_salary') }}</th>
                            <td></td>
                            <td>{{ @app_setting()->currency?->symbol }} {{ number_format($net_salary, 2) }}</td>
                        </tr>
                        <tr style="text-align:center;">
                            <th style="text-align:center;">{{ localize('loans') }}</th>
                            <td></td>
                            <td>{{ @app_setting()->currency?->symbol }} {{ number_format($loan_deduct, 2) }}</td>
                        </tr>
                        <tr style="text-align:center;">
                            <th style="text-align:center;">{{ localize('salary_advance') }}</th>
                            <td></td>
                            <td>{{ @app_setting()->currency?->symbol }} {{ number_format($salary_advance, 2) }}</td>
                        </tr>
                        <tr style="text-align:center;">
                            <th style="text-align:center;">{{ localize('state_income_tax') }}</th>
                            <td></td>
                            <td>{{ @app_setting()->currency?->symbol }} {{ number_format($income_tax, 2) }}</td>
                        </tr>
                        <tr style="text-align:center;">
                            <th style="text-align:center;">{{ localize('employee_NPF_contribution') }}</th>
                            <td></td>
                            <td>{{ @app_setting()->currency?->symbol }} {{ number_format($soc_sec_npf_tax, 2) }}</td>
                        </tr>
                        <tr style="text-align:center;">
                            <th style="text-align:center;">{{ localize('employer_NPF_contribution') }}</th>
                            <td></td>
                            <td>{{ @app_setting()->currency?->symbol }} {{ number_format($employer_contribution, 2) }}
                            </td>
                        </tr>
                        <tr style="text-align:center;">
                            <th style="text-align:center;">{{ localize('IICF_contribution') }}</th>
                            <td></td>
                            <td>{{ @app_setting()->currency?->symbol }} {{ number_format($icf_amount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div class="row" id="payment_area">
                <form action="{{ route('salary.approval', $salary_sheet->uuid) }}" method="POST">
                    @csrf
                    <div class="row" style="">

                        <input type="hidden" id="payment_list" value="{{ json_encode($credit_accounts) }}" />

                        <input type="hidden" id="month_year" value="{{ $salary_sheet->name }}" />

                        <input type="hidden" id="gross_salary" value="{{ $gross_salary }}" />

                        @if ($salary_sheet->is_approved == false)
                            <div class="form-group form-group-margin text-end">
                                <button type="submit" class="btn btn-primary w-md m-b-5"
                                    id="submit">{{ localize('submit') }}</button>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ module_asset('HumanResource/js/salary-approval.js') }}"></script>
@endpush
