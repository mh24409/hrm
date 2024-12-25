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
                            Control Ledger Report
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
                        @endphp
                        <div class="fs-12">{{ $col1 }} <br> {{ $col2 }}</div>
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="padding-top-20 fs-12 padding-bottom-10">

                </td>
            </tr>
        </table>
        <div class="table-responsive">
            <table id="" class="table display table-border table-striped table-hover fs-10 vertical-align">
                <thead>
                    <tr>
                        <th class="text-center">Head Name</th>
                        <th class="text-end">{{ localize('debit') }} ({{ currency() }})</th>
                        <th class="text-end">{{ localize('credit') }} ({{ currency() }})</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accForthHeadWithAmount as $key => $item)
                        <tr>
                            <td class="text-center">{{ $item['account_name'] }}</td>
                            <td class="text-end">{{ bt_number_format($item['debit']) }}</td>
                            <td class="text-end">{{ bt_number_format($item['credit']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>
</body>

</html>
