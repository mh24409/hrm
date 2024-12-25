@extends('backend.layouts.app')
@section('title', localize('employee_salary_chart'))
@section('content')
    @include('humanresource::payroll_header')

    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('employee_salary_chart') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        <button class="btn btn-success btn-sm" onclick="salaryChart()"><i
                                class="fa fa-print"></i>&nbsp;{{ localize('print') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            @include('backend.layouts.common.validation')

            <div id="print-table">
                <div class="row mb-10">
                    <table class="border-0" width="99%">
                        <thead>
                            <tr>
                                <td class="text-center" style="border:none;text-align: center;padding-bottom: 10px;">
                                    <img src="{{ app_setting()->logo }}">
                                </td>
                            </tr>
                            <tr>
                                <th class="text-center fs-20" style="border:none;text-align: center;padding-bottom: 30px;">
                                    {{ localize('employee_salary_chart_for') }}
                                    {{ \Carbon\Carbon::parse($salary_sheet->name)->format('F, Y') }}
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="form-inline">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-striped table-bordered table-sm salary-chart-table" role="grid"
                                style="width: 99%;">
                                <thead>
                                    <tr role="row" class="text-center bg-soft-warning">
                                        <th class="small" width="3%">{{ localize('sl') }}</th>
                                        <th class="small" width="9%">{{ localize('name') }}</th>
                                        <th class="small" width="7%">
                                            {{ localize('basic_salary') }}({{ app_setting()->currency?->symbol }})</th>

                                        <th class="small" width="5%">{{ localize('total_benefit') }}({{ app_setting()->currency?->symbol }})</th>
                                        <th class="small" width="5%">{{ localize('transport_allowance') }}({{ app_setting()->currency?->symbol }})</th>

                                        <th class="small" width="8%">
                                            {{ localize('gross_salary') }}({{ app_setting()->currency?->symbol }})</th>

                                        <th class="small" width="7%">
                                            {{ localize('state_income_tax') }}({{ app_setting()->currency?->symbol }})
                                        </th>
                                        <th class="small" width="12%">
                                            {{ localize('social_security_npf') }}({{ app_setting()->currency?->symbol }})
                                        </th>
                                        <th class="small" width="7%">
                                            {{ localize('employer_contribution') }}({{ app_setting()->currency?->symbol }})
                                        </th>
                                        <th class="small" width="7%">
                                            {{ localize('loan_deduction') }}({{ app_setting()->currency?->symbol }})
                                        </th>
                                        <th class="small" width="8%">
                                            {{ localize('salary_advance') }}({{ app_setting()->currency?->symbol }})</th>
                                        <th class="small" width="8%">
                                            {{ localize('total_deductions') }}({{ app_setting()->currency?->symbol }})
                                        </th>
                                        <th class="small" width="8%">
                                            {{ localize('net_salary') }}({{ app_setting()->currency?->symbol }})</th>
                                    </tr>
                                </thead>

                                <tbody class="employee_salary_chart">
                                    @foreach ($salary_infos as $key => $salary_info)

                                        @php
                                            $total_benefits = floatval($salary_info->medical_benefit) + floatval($salary_info->family_benefit) + floatval($salary_info->transportation_benefit) + floatval($salary_info->other_benefit);
                                            $total_deductions = floatval($salary_info->income_tax) + floatval($salary_info->soc_sec_npf_tax) + floatval($salary_info->loan_deduct) + floatval($salary_info->salary_advance);
                                        @endphp

                                        <tr class="text-center small">
                                            <td tabindex="0">{{ $key + 1 }}</td>
                                            <td>{{ $salary_info->employee ? $salary_info->employee->full_name : '' }}</td>
                                            <td>{{ $salary_info->basic_salary_pro_rated }}</td>

                                            <td>{{ $total_benefits}}</td>
                                            <td>{{ $salary_info->transport_allowance_pro_rated}}</td>

                                            <td>{{ $salary_info->gross_salary }}</td>

                                            <td>{{ $salary_info->income_tax }}</td>
                                            <td>{{ $salary_info->soc_sec_npf_tax }}</td>

                                            <td>{{ $salary_info->employer_contribution }}</td>
                                            <td>{{ $salary_info->loan_deduct }}</td>

                                            <td>{{ $salary_info->salary_advance }}</td>
                                            <td>{{ $total_deductions }}</td>
                                            <td>{{ $salary_info->net_salary }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="row pt-5">
                        <table class="border-0">
                            <tbody>
                                <tr>
                                    <td class="border-0" width="30%">
                                        <div class="border-0">{{ localize('prepared_by') }}:
                                            <b>{{ $salary_sheet->generate_by ? $salary_sheet->generate_by->full_name : '' }}</b>
                                        </div>
                                    </td>
                                    <td class="noborder" width="30%">
                                        <div class="border-0">{{ localize('checked_by') }}</div>
                                    </td>
                                    <td class="noborder" width="20%">
                                        <div class="border-0">{{ localize('authorized_by') }}</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
