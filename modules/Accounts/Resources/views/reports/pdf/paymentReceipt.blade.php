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
                            Receipt & Payment Report
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
                        <th><strong>{{ localize('particulars') }}</strong></th>
                        <th class="text-end"><strong>Balance ({{ currency() }})</strong></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2">
                            {{ localize('opening_balance') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 100px"> <span class=""> {{ $cashParent?->account_name }} </span>
                        </td>
                        <td class="text-end">{{ bt_number_format($cashParent?->totalOpening) }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 100px">{{ $bankParent?->account_name }}</td>
                        <td class="text-end">{{ bt_number_format($bankParent?->totalOpening) }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 100px">{{ $adVanceLedger?->account_name }}</td>
                        <td class="text-end">{{ bt_number_format($adVanceLedger?->totalOpening) }}</td>
                    </tr>


                </tbody>

                <tbody>
                    <tr>
                        <td colspan="2">
                            Receipt
                        </td>
                    </tr>
                    @foreach ($receiptThirdLevelDetail as $thirdLValue)
                        <tr>
                            <td style="padding-left: 100px">{{ $thirdLValue?->account_name }}</td>
                            <td></td>
                        </tr>
                        @foreach ($receiptfourthLavelFinal->where('parent_id', $thirdLValue?->id) as $fourthLableValue)
                            <tr>
                                <td style="padding-left: 200px">{{ $fourthLableValue['account_name'] }}</td>
                                <td class="text-end">{{ bt_number_format($fourthLableValue['credit']) }}</td>

                            </tr>
                        @endforeach
                    @endforeach





                    <tr class="table_data">
                        <td class="text-end"><b>{{ localize('total') }}</b></td>
                        <td class="text-end"><b>{{ $receiptfourthLavelFinal->sum('credit') }}</b></td>
                    </tr>
                    <tr class="table_data">
                        <td class="text-end">Grand Total</td>
                        <td class="text-end"><b>
                                {{ bt_number_format((float) $receiptfourthLavelFinal->sum('credit') + (float) $cashParent?->totalOpening + (float) $bankParent?->totalOpening + (float) $adVanceLedger?->totalOpening) }}</b>
                        </td>
                    </tr>

                </tbody>

                <tbody>
                    <tr>
                        <td colspan="2">
                            Payments
                        </td>
                    </tr>
                    @foreach ($paymentThirdLevelDetail as $pthirdLValue)
                        <tr>
                            <td style="padding-left: 100px">{{ $pthirdLValue?->account_name }}</td>
                            <td></td>
                        </tr>
                        @foreach ($paymentfourthLavelFinal->where('parent_id', $pthirdLValue?->id) as $fpourthLableValue)
                            <tr>
                                <td style="padding-left: 200px">{{ $fpourthLableValue['account_name'] }}</td>
                                <td class="text-end">{{ bt_number_format($fpourthLableValue['credit']) }}</td>
                            </tr>
                        @endforeach
                    @endforeach


                    <tr class="table_data">
                        <td class="text-end"><b>{{ localize('total') }}</b></td>
                        <td class="text-end"><b>{{ bt_number_format($paymentfourthLavelFinal->sum('credit')) }}</b>
                        </td>
                    </tr>

                </tbody>


                <tbody>
                    <tr>
                        <td colspan="2">
                            {{ localize('closing_balance') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 100px"> <span class=""> {{ $cashParent?->account_name }} </span>
                        </td>
                        <td class="text-end">{{ bt_number_format($cashParent?->totalClosing) }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 100px">{{ $bankParent?->account_name }}</td>
                        <td class="text-end">{{ bt_number_format($bankParent?->totalClosing) }}</td>
                    </tr>
                    <tr>
                        <td style="padding-left: 100px">{{ $adVanceLedger?->account_name }}</td>
                        <td class="text-end">{{ bt_number_format($adVanceLedger?->totalClosing) }}</td>
                    </tr>

                    <tr class="table_data">
                        <td class="text-end"><b>{{ localize('grand_total') }}</b></td>

                        <td class="text-end"><b>
                                {{ bt_number_format((float) $paymentfourthLavelFinal->sum('credit') + (float) $cashParent?->totalClosing + (float) $bankParent?->totalClosing + (float) $adVanceLedger?->totalClosing) }}
                            </b></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>
</body>

</html>
