@extends('backend.layouts.app')
@section('title', localize('employee_payslip'))
@push('css')
    <style>
        @media print {
            body * {
                font-size: 12px;
            }

            td,
            th {
                margin-left: 2px;
            }
        }
    </style>
@endpush
@section('content')

    @include('humanresource::payroll_header')

    <div class="card fixed-tab-body">
        <div class="card-body">
            @include('backend.layouts.common.validation')
            <div id="viewData">
                <div class="px-5">
                    <table width="99%">
                        <tbody>
                            <tr>
                                <td width="30%"></td>
                                <td width="40%" class="text-center">
                                    <img src="{{ app_setting()->logo }}">
                                    <h4 class="mt-3">{{ app_setting()->title }} <span
                                            class="text-uppercase">({{ localize('payslip') }})</span> </h4>
                                </td>
                                <td width="30%"></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>

                    <div class="row">
                        <table width="99%" class="payrollDatatableReport table table-bordered">
                            <tbody>
                                <tr class="text-start">
                                    <th>{{ localize('employee_name') }}</th>
                                    <td>{{ $salary_info->employee ? $salary_info->employee->full_name : '' }}</td>
                                    <th>{{ localize('month') }}</th>
                                    <td>{{ \Carbon\Carbon::parse($salary_info->salary_month_year)->format('F, Y') }}</td>
                                </tr>
                                <tr class="text-start">
                                    <th>{{ localize('position') }}</th>
                                    <td>{{ $salary_info->employee ? ($salary_info->employee->position ? $salary_info->employee->position->position_name : '') : '' }}
                                    </td>
                                    <th>{{ localize('from') }}</th>
                                    <td>{{ $salary_sheet->start_date }}</td>
                                </tr>
                                <tr class="text-start">
                                    <th>{{ localize('contact') }}</th>
                                    <td>{{ $salary_info->employee ? $salary_info->employee->phone : '' }}</td>
                                    <th>{{ localize('to') }}</th>
                                    <td>{{ $salary_sheet->end_date }}</td>
                                </tr>
                                <tr class="text-start">
                                    <th>{{ localize('address') }}</th>
                                    <td>{{ $employee_info->present_address ? $employee_info->present_address : '' }}</td>
                                    <th>{{ localize('recruitment_date') }}</th>
                                    <td>{{ $employee_info->hire_date ? $employee_info->hire_date : '' }}</td>
                                </tr>
                                <tr>
                                    <th>{{ localize('total_working_hours') }}</th>
                                    <td>{{ $salary_info->normal_working_hrs_month }}</td>
                                    <th>{{ localize('worked_hours') }}</th>
                                    <td>{{ $salary_info->total_count }}</td>
                                </tr>
                                <tr class="text-start">
                                    <th>{{ localize('staff_id') }}</th>
                                    <td>#{{ $salary_info->employee?->employee_id }}</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    @php
                        $total_benefits = 0.0;
                        $total_benefits = floatval($salary_info->medical_benefit) + floatval($salary_info->family_benefit) + floatval($salary_info->transportation_benefit) + floatval($salary_info->other_benefit);
                    @endphp

                    <div class="row">
                        <table width="95%" class="payrollDatatableReport table table-striped table-bordered table-hover">
                            <thead>
                                <tr class="alert-warning text-start">
                                    <th>{{ localize('description') }}</th>
                                    <th>{{ localize('amount') }} ({{ app_setting()->currency?->symbol }})</th>
                                    <th>{{ localize('rate') }} ({{ app_setting()->currency?->symbol }})</th>
                                    <th>#{{ localize('value') }} ({{ app_setting()->currency?->symbol }})</th>
                                    <th>{{ localize('deduction') }} ({{ app_setting()->currency?->symbol }})</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-start">
                                    <td>{{ localize('basic_salary') }}</td>
                                    <td>{{ number_format($salary_info->basic, 2) }}
                                    </td>
                                    <td></td>
                                    <td>{{ number_format($salary_info->basic_salary_pro_rated, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="text-start">
                                    <td>{{ localize('transport_allowance') }}</td>
                                    <td>{{ number_format($salary_info->transport, 2) }}
                                    </td>
                                    <td></td>
                                    <td>{{ number_format($salary_info->transport_allowance_pro_rated, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="text-start">
                                    <td>{{ localize('total_benefit') }}</td>
                                    <td>
                                    </td>
                                    <td></td>
                                    <td>{{ number_format($total_benefits, 2) }}
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="text-start">
                                    <td>{{ localize('overtime') }}</td>
                                    <td>
                                    </td>
                                    <td></td>
                                    <td>
                                    </td>
                                    <td></td>
                                </tr>
                                @foreach ($allowances as $allowance)
                                    <tr class="text-start">
                                        <td>{{ $allowance->setup_rule?->name }}</td>
                                        <td>{{ number_format($allowance->amount ?? 0.0, 2) }}</td>
                                        <td></td>
                                        <td>{{ number_format($allowance->amount ?? 0.0, 2) }}</td>
                                        <td></td>
                                    </tr>
                                @endforeach



                                <tr class="text-start">
                                    <th>{{ localize('gross_salary') }}</th>
                                    <th></th>
                                    <th></th>
                                    <th>
                                        {{ number_format($salary_info->gross_salary, 2) }}</th>
                                    <th></th>

                                </tr>

                                <tr class="text-start">
                                    <td>{{ localize('state_income_tax') }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ number_format($salary_info->income_tax, 2) }}</td>
                                </tr>

                                <tr class="text-start">
                                    <td>{{ localize('social_security') }}</td>
                                    <td></td>
                                    <td>{{ app_setting()->soc_sec_npf_tax.' %' }}</td>
                                    <td></td>
                                    <td>{{ number_format($salary_info->soc_sec_npf_tax, 2) }}</td>
                                </tr>

                                <tr class="text-start">
                                    <td>{{ localize('loan_deduction') }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        {{ number_format($salary_info->loan_deduct, 2) }}</td>
                                </tr>
                                <tr class="text-start">
                                    <td>{{ localize('salary_advance') }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        {{ number_format($salary_info->salary_advance, 2) }}</td>
                                </tr>
                                @foreach ($deductions as $deduction)
                                    <tr class="text-start">
                                        <td>{{ $deduction->setup_rule?->name }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            {{ number_format($deduction->amount ?? 0.0, 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="text-start">
                                    <th class="text-start">{{ localize('total_deductions') }}</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th class="text-start">
                                        {{ number_format($salary_info->total_deduction, 2) }}</th>
                                </tr>
                                <tr class="text-start alert-warning">
                                    <th colspan="4">{{ localize('net_salary') }}</th>
                                    <th class="text-start">{{ number_format($salary_info->net_salary, 2) }}</th>
                                </tr>
                            </tbody>
                        </table>
                        <table width="100%" class="mt-5">
                            <tbody>
                                <tr>
                                    <td class="border-0" width="33%">
                                        <div class="border-top mx-5 text-center">{{ localize('prepared_by') }}:
                                            <b>{{ auth()->user()->full_name }}</b>
                                    </td>
                                    <td width="33%">
                                        <div class="border-top mx-5 text-center">{{ localize('checked_by') }}</div>
                                    </td>
                                    <td width="33%">
                                        <div class="border-top mx-5 text-center">{{ localize('authorized_by') }}</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        </div>
        <div class="card-footer d-print-none">
            <div class="text-end px-5" id="print">
                <button type="button" class="btn btn-warning-soft" id="btnPrint" onclick="printPage()"><i
                        class="fa fa-print"></i></button>
                <a class="btn btn-success-soft" href="{{ route('employee.payslip-pdf', $salary_info->uuid) }}"
                    title="download Payslip as PDF"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                    {{ localize('download_as_pdf') }} </a>
            </div>
        </div>
    </div>

@endsection
