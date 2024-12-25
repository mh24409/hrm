@extends('backend.layouts.app')
@section('title', localize('balance_sheet'))
@push('css')
    <link href="{{ module_asset('Accounts/css/dailog.css') }}" rel="stylesheet">
    <link href="{{ module_asset('Accounts/css/landscape.css') }}" rel="stylesheet">
@endpush
@section('content')

    @include('accounts::reports_header')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')


    <div class="card mb-4 d-print-none fixed-tab-body">
        <div class="card-body">
            <div class="filter-form">
                <form class="g-3" action="" method="GET">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 col-xl-3 col-12">
                            <label for="date">{{ localize('date') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control account-date-range" id="date" name="date"
                                value="{{ $date }}" required>

                            @if ($errors->has('date'))
                                <div class="error text-danger m-2">{{ $errors->first('date') }}</div>
                            @endif
                        </div>

                        <div class="col-md-2 col-xl-2 col-12">
                            <label for="shape_type">{{ localize('result_type') }}</label>
                            <div class="p-2"> <input type="radio" id="l_type" name="shape_type"
                                    class="form-check-input align-middle" value="1"
                                    {{ $request->shape_type == 1 ? 'checked' : '' }}>
                                <label class="radio-inline form-check-label align-middle" for="l_type">
                                    T Shape
                                </label>
                                <input type="radio" id="t_type" name="shape_type" class="form-check-input align-middle"
                                    value="2" {{ $request->shape_type == 2 ? 'checked' : '' }}>
                                <label class="radio-inline form-check-label align-middle" for="t_type">
                                    {{ localize('l_shape') }}
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2 align-self-end">
                            <button type="submit" name="filter" id="filter"
                                class="btn btn-success">{{ localize('find') }}</button>
                            <button type="reset" class="btn btn-danger page-reload">{{ localize('reset') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card mb-4 font-arial w-100" id="print-table">
        <div class="card-header">
            <div class="row">
                <div class="col-12 col-6">
                    <div class="fs-10 text-start pb-3">
                        {{ localize('print_date') }}: {{ \Carbon\Carbon::now()->format('d-m-Y h:i:sa') }} ,
                        {{ localize('user') }}: {{ auth()->user()->full_name }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="logo">
                        <img class="img-fluid w-100" src="{{ app_setting()->logo }}">
                    </div>
                </div>
                <div class="col text-center">

                    <h4 class="text-center">
                        Balance Sheet
                    </h4>
                    <div class="text-center" id="ledgerName"></div>
                    @if ($fromDate != null && $toDate != null)
                        <div class="text-center">
                            {{ localize('from') }} : {{ $fromDate ?? null }} {{ localize('to') }}
                            {{ $toDate ?? null }}
                        </div>
                    @endif

                </div>
                <div class="col text-end">
                    @php
                        $len = strlen(app_setting()->address);
                        $space = strrpos(app_setting()->address, ' ', -$len / 2);
                        $col1 = substr(app_setting()->address, 0, $space);
                        $col2 = substr(app_setting()->address, $space);
                        $attribute = '';
                    @endphp
                    <div class="fs-14">{{ $col1 }} <br> {{ $col2 }}</div>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if ($request->shape_type == 2)
                <table id="balance-sheet-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <td width="40%"><strong>Particulers</strong></td>
                            <td width="15%" class="text-end">
                                <strong>{{ $current_year->financial_year }}
                                    ({{ currency() }})</strong>
                            </td>
                            @foreach ($last_three_years as $year)
                                <td width="15%" class="text-end">
                                    <strong>{{ $year->financial_year }} ({{ currency() }})</strong>
                                </td>
                            @endforeach
                            @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                <td width="15%" class="text-end">
                                </td>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Assets</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
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
                                            <td class="text-end">{{ bt_number_format($level_three_asset->$attribute) }}
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
                            <td>Liabilities</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @foreach ($level_two_liabilities as $level_two_liability)
                            <tr>
                                <td style="padding-left:50px;">{{ $level_two_liability->account_name }}</td>
                                {{-- one --}}
                                <td class="text-end">{{ bt_number_format($level_two_liability->balance) }} </td>
                                @foreach ($last_three_years as $key => $year)
                                    @php
                                        $attribute = 'year_balance' . $key;
                                    @endphp
                                    <td class="text-end">{{ bt_number_format($level_two_liability->$attribute) }}</td>
                                @endforeach
                                @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                    <td class="text-end">
                                    </td>
                                @endfor
                            </tr>
                            @foreach ($level_three_liabilities as $level_three_liability)
                                @if ($level_three_liability->parent_id == $level_two_liability->id)
                                    <tr>
                                        <td style="padding-left:100px;">{{ $level_three_liability->account_name }}</td>
                                        <td class="text-end">
                                            {{ bt_number_format($level_four_liabilities->where('parent_id', $level_three_liability->id)->sum('balance')) }}
                                        </td>
                                        @foreach ($last_three_years as $key => $year)
                                            @php
                                                $attribute = 'year_balance' . $key;
                                            @endphp
                                            <td class="text-end">{{ bt_number_format($level_three_liability->$attribute) }}
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
                            <th class="text-end">{{ bt_number_format($level_two_liabilities->sum('balance')) }}</th>
                            @foreach ($last_three_years as $key => $year)
                                @php
                                    $attribute = 'year_balance' . $key;
                                @endphp
                                <td class="text-end"> <strong>
                                        {{ bt_number_format($level_two_liabilities->sum($attribute)) }}</strong> </td>
                            @endforeach
                            @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                <td class="text-end">
                                </td>
                            @endfor
                        </tr>
                        <tr>
                            <td>Shareholder's Equity</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
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
                                        <td style="padding-left:100px;">{{ $level_three_equity->account_name }}</td>
                                        <td class="text-end">
                                            {{ bt_number_format($level_four_equities->where('parent_id', $level_three_equity->id)->sum('balance')) }}
                                        </td>
                                        @foreach ($last_three_years as $key => $year)
                                            @php $attribute  = 'year_balance' . $key @endphp
                                            <td class="text-end">{{ bt_number_format($level_three_equity->$attribute) }}
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
                <div class="row">
                    <div class="col-md-6 col-6">
                        <table id="liabilities-balance-sheet-table"
                            class="table display table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <td width="40%"><strong>Particulers</strong></td>
                                    <td width="15%" class="text-end">
                                        <strong>{{ $current_year->financial_year }}
                                            ({{ currency() }})</strong>
                                    </td>
                                    @foreach ($last_three_years as $year)
                                        <td width="15%" class="text-end">
                                            <strong>{{ $year->financial_year }}
                                                ({{ currency() }})
                                            </strong>
                                        </td>
                                    @endforeach
                                    @for ($i = 0; $i < 3 - count($last_three_years); $i++)
                                        <td width="15%" class="text-end">
                                        </td>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Liabilities</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @foreach ($level_two_liabilities as $level_two_liability)
                                    <tr>
                                        <td style="padding-left:50px;">{{ $level_two_liability->account_name }}</td>
                                        <td class="text-end">{{ bt_number_format($level_two_liability->balance) }}</td>

                                        @foreach ($last_three_years as $key => $year)
                                            @php
                                                $attribute = 'year_balance' . $key;
                                            @endphp
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
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6 col-6">
                        <table id="assets-balance-sheet-table"
                            class="table assets-balance-sheet-table display table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <td width="40%"><strong>Particulers</strong></td>
                                    <td width="15%" class="text-end">
                                        <strong>{{ $current_year->financial_year }}
                                            ({{ currency() }})</strong>
                                    </td>
                                    @foreach ($last_three_years as $year)
                                        <td width="15%" class="text-end">
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
                                    <td>Assets</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
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
                                                <td style="padding-left:100px;">{{ $level_three_asset->account_name }}
                                                </td>
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
                            </tbody>
                        </table>
                    </div>
                </div>

            @endif


        </div>
        <div class="card-body pt-0">
            {{-- pdf download link from --}}
            <form id="download_pdf" action="{{ route('reports.balancesheetPdf') }}" method="POST">
                @csrf

                @if ($request->shape_type == 2)
                    <span class="d-print-none print-none export-balance-sheet"></span>
                @endif
                <span class="d-print-none print-none export-liabilities-balance-sheet"></span>
                <span class="d-print-none print-none export-assets-balance-sheet"></span>

                <button type="button" class="btn btn-success d-print-none print-none"
                    onclick="accountReportPrintDetails()">{{ localize('print') }}</button>

                <input type="hidden" name="date" value="{{ $date }}">
                <input type="hidden" name="shape_type" value="{{ $request->shape_type }}">
                <input type="hidden" name="pdf" value="1">
                <input type="hidden" name="ledger_name" id="ledger_name">

                <button type="submit" class="btn btn-success d-print-none print-none">{{ localize('download_pdf') }}
                </button>
            </form>
        </div>
    </div>

@endsection
@push('js')
    <script src="{{ module_asset('Accounts/js/customeacc.js') }}"></script>
@endpush
