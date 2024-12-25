<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ localize('pay_slip') }}</title>

    <style>
        th,
        td {
            font-size: 12px !important;
            padding: 5px;
        }

        .border-top {
            border-top: 1px solid #000000;
        }

        .mx-5 {
            margin-left: 50px;
            margin-right: 50px;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body
    style="background-color:#fff;font-family: Open Sans, sans-serif;font-size:100%;font-weight:400;line-height:1.4;color:#000;">
    <div
        style="max-width:900px;margin:00px auto 10px;background-color:#fff;padding:0px;-webkit-border-radius:3px;-moz-border-radius:3px;border-radius:3px;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 2px rgba(0,0,0,.24);">
        <div style="padding: 10px; border-style: dotted; background: #a3753912;">
            <div style="text-align: center;">
                <table width="99%">
                    <tbody>
                        <tr>
                            <td width="30%"></td>
                            <td style="text-align: center" width="40%">

                                @php
                                    $path = app_setting()->logo?app_setting()->logo:asset('backend/assets/dist/img/logo.png');
                                    $type = pathinfo($path, PATHINFO_EXTENSION);
                                    $data = file_get_contents($path);
                                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                                @endphp

                                <img src="{{ $base64 }}"
                                    alt="logo">

                                <!-- <img src="{{ app_setting()->logo ? asset('storage/' . app_setting()->logo) : asset('backend/assets/dist/img/logo.png') }}"
                                    alt="logo"> -->
                                <h4>{{ app_setting()->title ?? 'Four Nine ERP' }}</h4>
                            </td>
                            <td width="30%"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <table width="99%">
                <thead>
                    <tr style="height: 40px;background-color: #ff980033;">
                        <th class="text-center fs-20" style="text-transform:uppercase">{{ localize('payslip') }}</th>
                    </tr>
                </thead>
            </table>
            <div class="row">
                <table width="99%" class="payrollDatatableReport table table-striped table-bordered table-hover"
                    id="DataTables_Table_0">
                    <tbody>
                        <tr style="text-align: left;background-color: #E7E0EE;">
                            <th>{{ localize('employee_name') }}</th>
                            <td>{{ $salary_info->employee ? $salary_info->employee->full_name : '' }}</td>
                            <th>{{ localize('month') }}</th>
                            <td>{{ \Carbon\Carbon::parse($salary_info->salary_month_year)->format('F, Y') }}</td>
                        </tr>
                        <tr class="text-end">
                            <th>{{ localize('position') }}</th>
                            <td>{{ $salary_info->employee ? ($salary_info->employee->position ? $salary_info->employee->position->position_name : '') : '' }}
                            </td>
                            <th>{{ localize('from') }}</th>
                            <td>{{ $salary_sheet->start_date }}</td>
                        </tr>
                        <tr class="text-end">
                            <th>{{ localize('contact') }}</th>
                            <td>{{ $salary_info->employee ? $salary_info->employee->phone : '' }}</td>
                            <th>To</th>
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
                        <tr class="text-end">
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
                <table width="99%" class="payrollDatatableReport table table-striped table-bordered table-hover">
                    <thead>
                        <tr style="text-align: left;background-color: #ff980033;">
                            <th>{{ localize('description') }}</th>
                            <th>{{ localize('gross_amount') }}</th>
                            <th>{{ localize('rate') }}</th>
                            <th>#{{ localize('value') }}</th>
                            <th>{{ localize('deduction') }}</th>
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
                            <tr class="text-end">
                                <td>{{ $allowance->setup_rule?->name }}</td>
                                <td>{{ $allowance->amount }}</td>
                                <td></td>
                                <td>{{ $allowance->amount }}</td>
                                <td></td>
                            </tr>
                        @endforeach

                        <tr style="text-align:left;">
                            <th>{{ localize('gross_salary') }}</th>
                            <th></th>
                            <th></th>
                            <th>{{ $salary_info->gross_salary }}</th>
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

                        <tr class="text-end">
                            <td>{{ localize('loan_deduction') }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ number_format($salary_info->loan_deduct, 2) }}</td>
                        </tr>
                        <tr class="text-end">
                            <td>{{ localize('salary_advance') }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>{{ number_format($salary_info->salary_advance, 2) }}</td>
                        </tr>
                        @foreach ($deductions as $deduction)
                            <tr class="text-end">
                                <td>{{ $deduction->setup_rule?->name }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{ $deduction->amount }}</td>
                            </tr>
                        @endforeach
                        <tr class="text-end">
                            <th align="left">{{ localize('total_deductions') }}</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th align="left">{{ number_format($salary_info->total_deduction, 2) }}</th>
                        </tr>

                        <tr style="text-align: left; background-color: #ff980033;">
                            <th colspan="4">{{ localize('net_salary') }}</th>
                            <th align="left">{{ number_format($salary_info->net_salary, 2) }}</th>
                        </tr>
                    </tbody>
                </table>
                <table width="100%" style="margin-top:50px;">
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

</body>

</html>
