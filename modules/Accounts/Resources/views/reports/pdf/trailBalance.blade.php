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

                        <div class="text-center fs-18">{{ localize('trial_balance') }}
                        </div>
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
                        @endphp
                        <div class="fs-12">{{ $col1 }} <br> {{ $col2 }}</div>
                    </div>
                </td>
            </tr>
        </table>
        <div class="table-responsive">
            <table id="" class="table display table-border table-striped table-hover fs-10 vertical-align">
                <thead class="align-middle">
                    <tr>
                        @if ($trail_balnce_type == 2)
                            <th rowspan="2" height="25">Code</th>
                            <th rowspan="2" class="">{{ localize('account_name') }}</th>
                            <th class="text-center p-top-2-bottom-2" colspan="2">
                                {{ localize('opening_balance') }}</th>
                            <th class="text-center p-top-2-bottom-2" colspan="2">
                                {{ localize('transactional_balance') }}</th>
                            <th class="text-center p-top-2-bottom-2" colspan="2">
                                {{ localize('closing_balance') }}</th>
                        @elseif ($trail_balnce_type == 1)
                            <th rowspan="2" height="25">Code</th>
                            <th rowspan="2">{{ localize('account_name') }}</th>
                            <th class="text-center p-top-2-bottom-2" colspan="2">
                                {{ localize('transactional_balance') }}</th>
                        @else
                            <th rowspan="2" height="25">Code</th>
                            <th rowspan="2" class="">{{ localize('account_name') }}</th>
                            <th class="text-center p-top-2-bottom-2" colspan="2">
                                {{ localize('closing_balance') }}</th>
                        @endif
                    </tr>
                    <tr>
                        @if ($trail_balnce_type == 2)
                            <th class="text-end p-top-2-bottom-2">{{ localize('debit') }} ({{ currency() }})
                            </th>
                            <th class="text-end p-top-2-bottom-2">{{ localize('credit') }} ({{ currency() }})
                            </th>
                            <th class="text-end p-top-2-bottom-2">{{ localize('debit') }} ({{ currency() }})
                            </th>
                            <th class="text-end p-top-2-bottom-2">{{ localize('credit') }} ({{ currency() }})
                            </th>
                            <th class="text-end p-top-2-bottom-2">{{ localize('debit') }} ({{ currency() }})
                            </th>
                            <th class="text-end p-top-2-bottom-2">{{ localize('credit') }} ({{ currency() }})
                            </th>
                        @elseif ($trail_balnce_type == 1)
                            <th class="text-end p-top-2-bottom-2">{{ localize('debit') }} ({{ currency() }})
                            </th>
                            <th class="text-end p-top-2-bottom-2">{{ localize('credit') }} ({{ currency() }})
                            </th>
                        @else
                            <th class="text-end p-top-2-bottom-2">{{ localize('debit') }} ({{ currency() }})
                            </th>
                            <th class="text-end p-top-2-bottom-2">{{ localize('credit') }} ({{ currency() }})
                            </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($trailBalnce as $key => $data)
                        <tr>
                            @if ($trail_balnce_type == 2)
                                <td>{{ $data['account_code'] }}</td>
                                @if ($data['head_level'] == 1)
                                    <td><strong>{{ $data['account_name'] }}</strong></td>
                                @elseif($data['head_level'] == 2)
                                    <td style="padding-left: 50px;">{{ $data['account_name'] }}</td>
                                @elseif($data['head_level'] == 3)
                                    <td style="padding-left: 100px;">{{ $data['account_name'] }}</td>
                                @elseif($data['head_level'] == 4)
                                    <td style="padding-left: 150px;">{{ $data['account_name'] }}</td>
                                @endif
                                <td class="text-end">{{ bt_number_format($data['opening_balance_debit']) }}</td>
                                <td class="text-end">{{ bt_number_format($data['opening_balance_credit']) }}</td>

                                <td class="text-end">{{ bt_number_format($data['tran_blance_debit']) }}</td>
                                <td class="text-end">{{ bt_number_format($data['tran_blance_credit']) }}</td>

                                <td class="text-end">{{ bt_number_format($data['closing_balance_debit']) }}</td>
                                <td class="text-end">{{ bt_number_format($data['closing_balance_credit']) }}</td>
                            @elseif($trail_balnce_type == 1)
                                <td>{{ $data['account_code'] }}</td>
                                @if ($data['head_level'] == 1)
                                    <td><strong>{{ $data['account_name'] }}</strong></td>
                                @elseif($data['head_level'] == 2)
                                    <td style="padding-left: 50px;">{{ $data['account_name'] }}</td>
                                @elseif($data['head_level'] == 3)
                                    <td style="padding-left: 100px;">{{ $data['account_name'] }}</td>
                                @elseif($data['head_level'] == 4)
                                    <td style="padding-left: 150px;">{{ $data['account_name'] }}</td>
                                @endif
                                <td class="text-end">{{ bt_number_format($data['tran_blance_debit']) }}</td>
                                <td class="text-end">{{ bt_number_format($data['tran_blance_credit']) }}</td>
                            @else
                                <td>{{ $data['account_code'] }}</td>
                                @if ($data['head_level'] == 1)
                                    <td><strong>{{ $data['account_name'] }}</strong></td>
                                @elseif($data['head_level'] == 2)
                                    <td style="padding-left: 50px;">{{ $data['account_name'] }}</td>
                                @elseif($data['head_level'] == 3)
                                    <td style="padding-left: 100px;">{{ $data['account_name'] }}</td>
                                @elseif($data['head_level'] == 4)
                                    <td style="padding-left: 150px;">{{ $data['account_name'] }}</td>
                                @endif
                                <td class="text-end">{{ bt_number_format($data['closing_balance_debit']) }}</td>
                                <td class="text-end">{{ bt_number_format($data['closing_balance_credit']) }}</td>
                            @endif
                        </tr>



                    @empty

                    @endforelse

                </tbody>

                <tfoot>
                    <tr class="table_data">

                        @if ($trail_balnce_type == 2)
                            <td class="text-end" colspan="2"><strong
                                    class="text-dark">{{ localize('total') }}</strong></td>

                            <td class="text-end"><strong
                                    class="text-dark">{{ bt_number_format($trilbalCollection->sum('opening_balance_debit')) }}</strong>
                            </td>
                            <td class="text-end"><strong
                                    class="text-dark">{{ bt_number_format($trilbalCollection->sum('opening_balance_credit')) }}</strong>
                            </td>
                            <td class="text-end"><strong
                                    class="text-dark">{{ bt_number_format($trilbalCollection->sum('tran_blance_debit')) }}</strong>
                            </td>
                            <td class="text-end"><strong
                                    class="text-dark">{{ bt_number_format($trilbalCollection->sum('tran_blance_credit')) }}</strong>
                            </td>
                            <td class="text-end"><strong
                                    class="text-dark">{{ bt_number_format($trilbalCollection->sum('closing_balance_debit')) }}</strong>
                            </td>
                            <td class="text-end"><strong
                                    class="text-dark">{{ bt_number_format($trilbalCollection->sum('closing_balance_credit')) }}</strong>
                            </td>
                        @elseif($trail_balnce_type == 1)
                            <td class="text-end" colspan="2"><strong
                                    class="text-dark">{{ localize('total') }}</strong></td>

                            <td class="text-end"><strong
                                    class="text-dark">{{ bt_number_format($trilbalCollection->sum('tran_blance_debit')) }}</strong>
                            </td>
                            <td class="text-end"><strong
                                    class="text-dark">{{ bt_number_format($trilbalCollection->sum('tran_blance_credit')) }}</strong>
                            </td>
                        @else
                            <td class="text-end" colspan="2"><strong
                                    class="text-dark">{{ localize('total') }}</strong></td>

                            <td class="text-end"><strong
                                    class="text-dark">{{ bt_number_format($trilbalCollection->sum('opening_balance_debit')) }}</strong>
                            </td>
                            <td class="text-end"><strong
                                    class="text-dark">{{ bt_number_format($trilbalCollection->sum('opening_balance_credit')) }}</strong>
                            </td>
                        @endif

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    </div>
</body>

</html>
