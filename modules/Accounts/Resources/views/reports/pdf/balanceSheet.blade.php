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
                            Balance Sheet
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


        <div>
            <div>
                @if ($request->shape_type == 2)
                    <table width="100%" class="table display table-border table-striped table-hover fs-10">
                        <thead>
                            <tr>
                                <td><strong>Particulers</strong></td>
                                <td class="text-end">
                                    <strong>{{ $current_year->financial_year }}
                                        ({{ currency() }})</strong>
                                </td>
                                @foreach ($last_three_years as $year)
                                    <td class="text-end">
                                        <strong>{{ $year->financial_year }}
                                            ({{ currency() }})
                                        </strong>
                                    </td>
                                @endforeach
                                @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                    <td class="text-end">
                                    </td>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5">Assets</td>
                            </tr>

                            @foreach ($level_two_assets as $level_two_asset)
                                <tr>
                                    <td style="padding-left:50px;">{{ $level_two_asset->account_name }}</td>
                                    <td class="text-end">{{ bt_number_format($level_two_asset->balance) }}</td>
                                    @foreach ($last_three_years as $key => $year)
                                        @php $attribute  = 'year_balance' . $key @endphp
                                        <td class="text-end">{{ bt_number_format($level_two_asset->$attribute) }}</td>
                                    @endforeach
                                    @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                        <td class="text-end">
                                        </td>
                                    @endfor
                                </tr>
                                @foreach ($level_three_assets as $level_three_asset)
                                    @if ($level_three_asset->parent_id == $level_two_asset->id)
                                        <tr>
                                            <td style="padding-left:100px;">{{ $level_three_asset->account_name }}</td>
                                            <td class="text-end">
                                                {{ bt_number_format($level_four_assets->where('parent_id', $level_three_asset->id)->sum('balance')) }}
                                            </td>
                                            @foreach ($last_three_years as $key => $year)
                                                @php $attribute  = 'year_balance' . $key @endphp
                                                <td class="text-end">
                                                    {{ bt_number_format($level_three_asset->$attribute) }}</td>
                                            @endforeach
                                            @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                                <td class="text-end">
                                                </td>
                                            @endfor
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                            <tr>
                                <th class="text-end">Total Assets</th>
                                <th class="text-end">{{ bt_number_format($level_two_assets->sum('balance')) }}</th>
                                @foreach ($last_three_years as $key => $year)
                                    @php $attribute  = 'year_balance' . $key @endphp
                                    <td class="text-end"> <strong>
                                            {{ bt_number_format($level_two_assets->sum($attribute)) }}</strong> </td>
                                @endforeach
                                @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                    <td class="text-end">
                                    </td>
                                @endfor
                            </tr>
                            <tr>
                                <td colspan="5">Liabilities</td>
                            </tr>
                            @foreach ($level_two_liabilities as $level_two_liability)
                                <tr>
                                    <td style="padding-left:50px;">{{ $level_two_liability->account_name }}</td>
                                    <td class="text-end">{{ bt_number_format($level_two_liability->balance) }}</td>
                                    @foreach ($last_three_years as $key => $year)
                                        @php $attribute  = 'year_balance' . $key @endphp
                                        <td class="text-end">{{ bt_number_format($level_two_liability->$attribute) }}
                                        </td>
                                    @endforeach
                                    @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                        <td class="text-end">
                                        </td>
                                    @endfor
                                </tr>
                                @foreach ($level_three_liabilities as $level_three_liability)
                                    @if ($level_three_liability->parent_id == $level_two_liability->id)
                                        <tr>
                                            <td style="padding-left:100px;">{{ $level_three_liability->account_name }}
                                            </td>
                                            <td class="text-end">
                                                {{ bt_number_format($level_four_liabilities->where('parent_id', $level_three_liability->id)->sum('balance')) }}
                                            </td>
                                            @foreach ($last_three_years as $key => $year)
                                                @php $attribute  = 'year_balance' . $key @endphp
                                                <td class="text-end">
                                                    {{ bt_number_format($level_three_liability->$attribute) }}</td>
                                            @endforeach
                                            @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                                <td class="text-end">
                                                </td>
                                            @endfor
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                            <tr>
                                <th class="text-end">Total Liabilities</th>
                                <th class="text-end">{{ bt_number_format($level_two_liabilities->sum('balance')) }}
                                </th>
                                @foreach ($last_three_years as $key => $year)
                                    @php $attribute  = 'year_balance' . $key @endphp
                                    <td class="text-end"> <strong>
                                            {{ bt_number_format($level_two_liabilities->sum($attribute)) }}</strong>
                                    </td>
                                @endforeach
                                @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                    <td class="text-end">
                                    </td>
                                @endfor
                            </tr>
                            <tr>
                                <td colspan="5">Shareholder's Equity</td>
                            </tr>
                            @foreach ($level_two_equities as $level_two_equity)
                                <tr>
                                    <td style="padding-left:50px;">{{ $level_two_equity->account_name }}</td>
                                    <td class="text-end">{{ bt_number_format($level_two_equity->balance) }}</td>
                                    @foreach ($last_three_years as $key => $year)
                                        @php $attribute  = 'year_balance' . $key @endphp
                                        <td class="text-end">{{ bt_number_format($level_two_equity->$attribute) }}</td>
                                    @endforeach
                                    @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                        <td class="text-end">
                                        </td>
                                    @endfor
                                </tr>
                                @foreach ($level_three_equities as $level_three_equity)
                                    @if ($level_three_equity->parent_id == $level_two_equity->id)
                                        <tr>
                                            <td style="padding-left:100px;">{{ $level_three_equity->account_name }}
                                            </td>
                                            <td class="text-end">
                                                {{ bt_number_format($level_four_equities->where('parent_id', $level_three_equity->id)->sum('balance')) }}
                                            </td>
                                            @foreach ($last_three_years as $key => $year)
                                                @php $attribute  = 'year_balance' . $key @endphp
                                                <td class="text-end">
                                                    {{ bt_number_format($level_three_equity->$attribute) }}</td>
                                            @endforeach

                                            @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                                <td class="text-end">
                                                </td>
                                            @endfor
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                            <tr>
                                <th class="text-end">Total Shareholder's Equity </th>
                                <th class="text-end">{{ bt_number_format($level_two_equities->sum('balance')) }}</th>
                                @foreach ($last_three_years as $key => $year)
                                    @php $attribute  = 'year_balance' . $key @endphp
                                    <td class="text-end"> <strong>
                                            {{ bt_number_format($level_two_equities->sum($attribute)) }}</strong> </td>
                                @endforeach
                                @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                    <td class="text-end">
                                    </td>
                                @endfor
                            </tr>
                            <tr>
                                <th class="text-end">Total Liabilities & Shareholder's Equity </th>
                                <th class="text-end">
                                    {{ bt_number_format($level_two_liabilities->sum('balance') + $level_two_equities->sum('balance')) }}
                                </th>
                                @foreach ($last_three_years as $key => $year)
                                    @php $attribute  = 'year_balance' . $key @endphp
                                    <td class="text-end"> <strong>
                                            {{ bt_number_format($level_two_liabilities->sum($attribute) + $level_two_equities->sum($attribute)) }}</strong>
                                    </td>
                                @endforeach
                                @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                    <td class="text-end">
                                    </td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                @else
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 50%;padding-right:10px;">
                                <table class="table display table-border table-striped table-hover fs-10">
                                    <thead>
                                        <tr>
                                            <td><strong>Particulers</strong></td>
                                            <td class="text-end" width="15%">
                                                <strong>{{ $current_year->financial_year }}
                                                    ({{ currency() }})</strong>
                                            </td>
                                            @foreach ($last_three_years as $year)
                                                <td class="text-end" width="15%">
                                                    <strong>{{ $year->financial_year }}
                                                        ({{ currency() }})
                                                    </strong>
                                                </td>
                                            @endforeach
                                            @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                                <td class="text-end">
                                                </td>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5">Liabilities</td>

                                        </tr>
                                        @foreach ($level_two_liabilities as $level_two_liability)
                                            <tr>
                                                <td style="padding-left:50px;">{{ $level_two_liability->account_name }}
                                                </td>
                                                <td class="text-end">
                                                    {{ bt_number_format($level_two_liability->balance) }}</td>
                                                @foreach ($last_three_years as $key => $year)
                                                    @php $attribute  = 'year_balance' . $key @endphp
                                                    <td class="text-end">
                                                        {{ bt_number_format($level_two_liability->$attribute) }}</td>
                                                @endforeach
                                                @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                                    <td class="text-end">
                                                    </td>
                                                @endfor
                                            </tr>
                                            @foreach ($level_three_liabilities as $level_three_liability)
                                                @if ($level_three_liability->parent_id == $level_two_liability->id)
                                                    <tr>
                                                        <td style="padding-left:100px;">
                                                            {{ $level_three_liability->account_name }}</td>

                                                        <td class="text-end">
                                                            {{ bt_number_format($level_four_liabilities->where('parent_id', $level_three_liability->id)->sum('balance')) }}
                                                        </td>
                                                        @foreach ($last_three_years as $key => $year)
                                                            @php $attribute  = 'year_balance' . $key @endphp
                                                            <td class="text-end">
                                                                {{ bt_number_format($level_three_liability->$attribute) }}
                                                            </td>
                                                        @endforeach
                                                        @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                                            <td class="text-end">
                                                            </td>
                                                        @endfor
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <th class="text-end">Total Liabilities</th>
                                            <th class="text-end">
                                                {{ bt_number_format($level_two_liabilities->sum('balance')) }}</th>
                                            @foreach ($last_three_years as $key => $year)
                                                @php $attribute  = 'year_balance' . $key @endphp
                                                <td class="text-end"> <strong>
                                                        {{ bt_number_format($level_two_liabilities->sum($attribute)) }}</strong>
                                                </td>
                                            @endforeach
                                            @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                                <td class="text-end">
                                                </td>
                                            @endfor
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="width: 50%;vertical-align: baseline; padding-left:10px;">
                                <table class="table display table-border table-striped table-hover fs-10">
                                    <thead>
                                        <tr>
                                            <td><strong>Particulers</strong></td>
                                            <td class="text-end" width="15%">
                                                <strong>{{ $current_year->financial_year }}
                                                    ({{ currency() }})</strong>
                                            </td>
                                            @foreach ($last_three_years as $year)
                                                <td class="text-end" width="15%">
                                                    <strong>{{ $year->financial_year }}
                                                        ({{ currency() }})
                                                    </strong>
                                                </td>
                                            @endforeach
                                            @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                                <td class="text-end">
                                                </td>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td colspan="5">Assets</td>
                                        </tr>

                                        @foreach ($level_two_assets as $level_two_asset)
                                            <tr>
                                                <td style="padding-left:50px;">{{ $level_two_asset->account_name }}
                                                </td>
                                                <td class="text-end">{{ bt_number_format($level_two_asset->balance) }}
                                                </td>
                                                @foreach ($last_three_years as $key => $year)
                                                    @php $attribute  = 'year_balance' . $key @endphp
                                                    <td class="text-end">
                                                        {{ bt_number_format($level_two_asset->$attribute) }}</td>
                                                @endforeach
                                                @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                                    <td class="text-end">
                                                    </td>
                                                @endfor
                                            </tr>
                                            @foreach ($level_three_assets as $level_three_asset)
                                                @if ($level_three_asset->parent_id == $level_two_asset->id)
                                                    <tr>
                                                        <td style="padding-left:100px;">
                                                            {{ $level_three_asset->account_name }}</td>
                                                        <td class="text-end">
                                                            {{ bt_number_format($level_four_assets->where('parent_id', $level_three_asset->id)->sum('balance')) }}
                                                        </td>
                                                        @foreach ($last_three_years as $key => $year)
                                                            @php $attribute  = 'year_balance' . $key @endphp
                                                            <td class="text-end">
                                                                {{ bt_number_format($level_three_asset->$attribute) }}
                                                            </td>
                                                        @endforeach
                                                        @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                                            <td class="text-end">
                                                            </td>
                                                        @endfor
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                        <tr>
                                            <th class="text-end">Total Assets</th>
                                            <th class="text-end">
                                                {{ bt_number_format($level_two_assets->sum('balance')) }}</th>
                                            @foreach ($last_three_years as $key => $year)
                                                @php $attribute  = 'year_balance' . $key @endphp
                                                <td class="text-end"> <strong>
                                                        {{ bt_number_format($level_two_assets->sum($attribute)) }}</strong>
                                                </td>
                                            @endforeach
                                            @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                                <td class="text-end">
                                                </td>
                                            @endfor
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>

                @endif
            </div>
        </div>
    </div>
</body>

</html>
