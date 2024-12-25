<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="{{ asset('backend/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ module_asset('Accounts/css/acc-pdf.css?v_' . date('h_i')) }}" rel="stylesheet">
</head>

<body>
    <div class="tile">
        <div class="fs-10 text-start pb-3">
            {{ localize('print_date') }}: {{ \Carbon\Carbon::now()->format('d-m-Y h:i:sa') }} ,
            {{ localize('user') }}: {{ auth()->user()->full_name }}
        </div>
        <table style="width:100%">
            <tr>
                <td style="">
                    <img class="img-fluid" src="{{ app_setting()->logo }}">
                </td>
                <td>
                    <div class="text-center">
                        <div class="text-center fs-18">
                            Profit Loss
                        </div>
                        @if ($fromDate != null && $toDate != null)
                            <div class="text-center fs-8">
                                {{ localize('from') }} : {{ $fromDate ?? null }} {{ localize('to') }}
                                {{ $toDate ?? null }}
                            </div>
                        @endif

                    </div>
                </td>
                <td>
                    <div class="text-end">
                        @php
                            $len = strlen(app_setting()->address);
                            $space = strrpos(app_setting()->address, ' ', -$len / 2);
                            $col1 = substr(app_setting()->address, 0, $space);
                            $col2 = substr(app_setting()->address, $space);
                        @endphp
                        <div class="fs-12">{{ $col1 }} <br> {{ $col2 }}</div>
                    </div>
                </td>
            </tr>
        </table>
        <div class="table-responsive">
            <table id="" class="table display table-border table-striped table-hover fs-10 vertical-align">
                <thead>
                    <tr>
                        <td width="50%"><strong>Particulers</strong></td>
                        <td width="25%" class="text-end">
                            <strong>{{ localize('amount') }}({{ currency() }})</strong>
                        </td>
                        <td width="25%" class="text-end">
                            <strong>{{ localize('amount') }}({{ currency() }})</strong>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ localize('income') }}</td>
                        <td colspan="2"></td>
                    </tr>
                    @foreach ($level_two_incomes as $level_two_income)
                        <tr>
                            <td style="padding-left:50px;">{{ $level_two_income->account_name }}</td>
                            <td class="text-end"></td>
                            <td class="text-end">{{ bt_number_format($level_two_income->balance) }}</td>
                        </tr>
                        @foreach ($level_three_incomes as $level_three_income)
                            @if ($level_three_income->parent_id == $level_two_income->id)
                                <tr>
                                    <td style="padding-left:100px;">{{ $level_three_income->account_name }}</td>
                                    <td class="text-end">
                                        {{ bt_number_format($level_four_incomes->where('parent_id', $level_three_income->id)->sum('balance')) }}
                                    </td>
                                    <td class="text-end"></td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach

                    @if ($netLoss > 0)
                        <tr>
                            <th class="text-end">Net Loss</th>
                            <th class="text-end">{{ bt_number_format($netLoss) }}</th>
                            <th></th>
                        </tr>
                    @endif
                    <tr>
                        <th class="text-end">Total Income</th>
                        <th class="text-end">{{ bt_number_format($incomeBalance) }}</th>
                        <th></th>
                    </tr>

                    <tr>
                        <th class="text-end">{{ localize('total') }}</th>
                        <th class="text-end">
                            {{ bt_number_format($netLoss > 0 ? $netLoss + $incomeBalance : $incomeBalance) }}</th>
                        <th></th>
                    </tr>

                    <tr>
                        <td>Expenses</td>
                        <td colspan="2"></td>
                    </tr>
                    @foreach ($level_two_expences as $level_two_expence)
                        <tr>
                            <td style="padding-left:50px;">{{ $level_two_expence->account_name }}</td>
                            <td class="text-end"></td>
                            <td class="text-end">{{ bt_number_format($level_two_expence->balance) }}</td>
                        </tr>
                        @foreach ($level_three_expences as $level_three_expence)
                            @if ($level_three_expence->parent_id == $level_two_expence->id)
                                <tr>
                                    <td style="padding-left:100px;">{{ $level_three_expence->account_name }}</td>
                                    <td class="text-end">
                                        {{ bt_number_format($level_four_expences->where('parent_id', $level_three_expence->id)->sum('balance')) }}
                                    </td>
                                    <td class="text-end"></td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                    @if ($netProfit >= 0)
                        <tr>
                            <th class="text-end">Net Profit</th>
                            <th class="text-end">{{ bt_number_format($netProfit) }}</th>
                            <th></th>
                        </tr>
                    @endif
                    <tr>
                        <th class="text-end">Total Expenses</th>
                        <th class="text-end">{{ bt_number_format($expenceBalance) }}</th>
                        <th></th>
                    </tr>


                    <tr>
                        <th class="text-end">{{ localize('total') }}</th>
                        <th class="text-end">
                            {{ bt_number_format($netProfit > 0 ? $netProfit + $expenceBalance : $expenceBalance) }}
                        </th>
                        <th></th>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
    </div>
</body>

</html>
