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
                            General Ledger Report
                        </div>
                        <div class="text-center fs-6">{{ $ledger_name }}</div>
                        <div class="text-center fs-12">
                            {{ localize('from') }} : {{ $fromDate ?? null }} {{ localize('to') }}
                            {{ $toDate ?? null }}
                        </div>

                    </div>
                </td>
                <td>
                    <div class="text-end">
                        @php
                            $len = strlen(app_setting()->address);
                            $space = strrpos(app_setting()->address, ' ', -$len / 2);
                            $col1 = substr(app_setting()->address, 0, $space);
                            $col2 = substr(app_setting()->address, $space);
                            $totalbalance = 0;
                        @endphp
                        <div class="fs-12">{{ $col1 }} <br> {{ $col2 }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="padding-top-20 fs-12 padding-bottom-10">
                    <div><strong>{{ localize('opening_balance') }} : </strong>
                        {{ empty($getBalanceOpening) ? bt_number_format(0) : bt_number_format($getBalanceOpening) }}
                    </div>
                    <div><strong>{{ localize('total_balance') }} : </strong>
                        {{ bt_number_format($tablefooter['totalBalance']) }}</div>
                </td>
            </tr>
        </table>
        <div class="table-responsive">
            <table id="" class="table display table-border table-striped table-hover fs-10 vertical-align">
                <thead class="vertical-align">
                    <tr class="vertical-align">
                        <th class="wp-3"><strong>{{ localize('sl') }}</strong></th>
                        <th class="text-start wp-11"><strong>{{ localize('date') }}</strong></th>
                        <th class="text-start wp-11"><strong>{{ localize('voucher_no') }}</strong></th>
                        <th class="text-start wp-11"><strong>{{ localize('head_name') }}</strong></th>
                        <th class="text-start wp-14"><strong>{{ localize('ledger_comment') }}</strong></th>
                        <th class="text-end wp-14"><strong>{{ localize('debit') }} ({{ currency() }})</strong>
                        </th>
                        <th class="text-end wp-14"><strong>{{ localize('credit') }} ({{ currency() }})</strong>
                        </th>
                        <th class="text-end wp-14"><strong>Balance ({{ currency() }})</strong></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($getTransactionList as $key => $data)
                        <tr>
                            <td>{{ (int) $key + 1 }}</td>
                            <td>{{ $data->voucher_date }}</td>
                            <td>{{ $data->voucher_no }}</td>
                            <td>{{ $data->accCoa?->account_name }}
                                @if ($data->accSubcode)
                                    -( {{ $data->accSubcode?->name }} )
                                @endif
                            </td>
                            <td>{{ $data->ledger_comment }}</td>
                            <td align="right">{{ bt_number_format($data->debit) }} </td>
                            <td align="right">{{ bt_number_format($data->credit) }}</td>
                            <td align="right">{{ bt_number_format($data->balance) }}</td>
                        </tr>
                        @php
                            $totalbalance = $data->balance;
                        @endphp
                    @empty
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table_data">
                        <td>&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="center">&nbsp;</td>
                        <td align="right"><strong class="text-dark">{{ localize('total') }}</strong></td>
                        <td align="right"><strong
                                class="text-dark">{{ bt_number_format($tablefooter['totalDebit']) }}</strong>
                        </td>
                        <td align="right"><strong
                                class="text-dark">{{ bt_number_format($tablefooter['totalCradit']) }}</strong>
                        </td>
                        <td align="right"><strong class="text-dark">{{ bt_number_format($totalbalance) }}</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    </div>
</body>

</html>
