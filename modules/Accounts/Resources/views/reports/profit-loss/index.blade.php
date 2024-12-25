@extends('backend.layouts.app')
@section('title', localize('profit_loss'))
@push('css')
    <link href="{{ module_asset('Accounts/css/dailog.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('accounts::reports_header')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')

    <div class="card mb-4 d-print-none fixed-tab-body">
        <div class="card-body">
            <div class="filter-form">
                <form class="row g-3" action="" method="GET">
                    <input type="hidden" name="pdf" value="0">
                    @csrf
                    <div class="col-md-3 col-xl-3 col-12">
                        <label for="date">{{ localize('date') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control account-date-range" id="date" name="date"
                            value="{{ $date }}" required>

                        @if ($errors->has('date'))
                            <div class="error text-danger m-2">{{ $errors->first('date') }}</div>
                        @endif
                    </div>
                    <div class="col-md-2 col-xl-2 col-12">
                        <label for="profit_loss_type">Type <span class="text-danger">*</span></label>
                        <div class="p-2"> <input type="radio" id="ason" name="profit_loss_type"
                                class="form-check-input align-middle" value="0"
                                {{ $request->profit_loss_type == 0 ? 'checked' : '' }}>
                            <label class="radio-inline form-check-label align-middle me-1 pt-1" for="ason">
                                As ON <i class="fa fa-info-circle text-secondary"
                                    title="As on means you can see the current year's transactions from the previous starting year."></i>
                            </label>
                            <input type="radio" id="periodic" name="profit_loss_type"
                                class="form-check-input align-middle" value="1"
                                {{ $request->profit_loss_type == 1 ? 'checked' : '' }}>
                            <label class="radio-inline form-check-label align-middle pt-1" for="periodic">
                                Periodic <i class="fa fa-info-circle text-secondary"
                                    title="By periodic you can see the transactions of the current year."></i>
                            </label>
                            @if ($errors->has('profit_loss_type'))
                                <div class="error text-danger text-start">
                                    {{ $errors->first('profit_loss_type') }}</div>
                            @endif
                        </div>

                    </div>
                    <div class="col-md-2 align-self-end">
                        <button type="submit" name="filter" id="filter"
                            class="btn btn-success">{{ localize('find') }}</button>
                        <button type="reset" class="btn btn-danger page-reload">{{ localize('reset') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="card mb-4 font-arial" id="print-table">
        <div class="card-header border-bottom-0 pb-0">
            <div class="row">
                <div class="col-12 col-6">
                    <div class="fs-10 text-start pb-3">
                        {{ localize('print_date') }}: {{ \Carbon\Carbon::now()->format('d-m-Y h:i:sa') }} ,
                        {{ localize('user') }}:
                        {{ auth()->user()->full_name }}
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
                        {{ localize('profit_and_loss_report') }}
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
                    @endphp
                    <div class="fs-14">{{ $col1 }} <br> {{ $col2 }}</div>
                </div>
            </div>
        </div>

        <div class="card-body card-body-customize">
            <div class="table-responsive">
                <table id="profit-loss-table" class="table display table-bordered table-striped w-100 align-middle">
                    <thead>
                        <tr>
                            <td width="50%"><strong>Particulers</strong></td>
                            <td width="25%" class="text-end">
                                <strong>{{ localize('amount') }} ({{ currency() }})</strong>
                            </td>
                            <td width="25%" class="text-end">
                                <strong>{{ localize('amount') }}({{ currency() }})</strong>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ localize('income') }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        @foreach ($level_two_incomes as $level_two_income)
                            <tr>
                                <td style="padding-left:50px;">{{ $level_two_income->account_name }}</td>
                                <td class="text-end"></td>
                                <td class="text-end"> {{ bt_number_format($level_two_income->balance) }}</td>
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

                        <tr>
                            <th class="text-end">{{ localize('total_income') }}</th>
                            <th class="text-end"> {{ bt_number_format($incomeBalance) }}</th>
                            <th></th>
                        </tr>
                        @if (
                            ($netLoss > 0 ? $netLoss + $incomeBalance : $incomeBalance) < 0 &&
                                ($netProfit > 0 ? $netProfit + $expenceBalance : $expenceBalance) -
                                    ($netLoss > 0 ? $netLoss + $incomeBalance : $incomeBalance) >
                                    0)
                            <tr>
                                <th class="text-end">{{ localize('net_profit') }}</th>
                                <th class="text-end">
                                    {{ bt_number_format(($netProfit > 0 ? $netProfit + $expenceBalance : $expenceBalance) + ($netLoss > 0 ? $netLoss + $incomeBalance : $incomeBalance)) }}
                                </th>
                                <th></th>
                            </tr>

                            @php
                                $newProfit =
                                    ($netProfit > 0 ? $netProfit + $expenceBalance : $expenceBalance) +
                                    ($netLoss > 0 ? $netLoss + $incomeBalance : $incomeBalance);
                            @endphp
                        @else
                            @php
                                $newProfit = 0;
                            @endphp
                        @endif
                        @if ($netLoss >= 0)
                            <tr>
                                <th class="text-end">{{ localize('net_loss') }}</th>
                                <th class="text-end"> {{ bt_number_format($netLoss) }}</th>
                                <th></th>
                            </tr>
                        @endif

                        <tr>
                            <th class="text-end">{{ localize('total') }}</th>
                            <th class="text-end">
                                {{ bt_number_format(($netLoss > 0 ? $netLoss + $incomeBalance : $incomeBalance) + $newProfit) }}
                            </th>
                            <th></th>
                        </tr>

                        <tr>
                            <td>{{ localize('expenses') }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                        @foreach ($level_two_expences as $level_two_expence)
                            <tr>
                                <td style="padding-left:50px;">{{ $level_two_expence->account_name }}</td>
                                <td class="text-end"></td>
                                <td class="text-end"> {{ bt_number_format($level_two_expence->balance) }}</td>
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
                        <tr>
                            <th class="text-end">{{ localize('total_expenses') }}</th>
                            <th class="text-end"> {{ bt_number_format($expenceBalance) }}</th>
                            <th></th>
                        </tr>
                        @if ($netProfit >= 0)
                            <tr>
                                <th class="text-end">{{ localize('net_profit') }}</th>
                                <th class="text-end"> {{ bt_number_format($netProfit) }}</th>
                                <th></th>
                            </tr>
                        @endif


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
        <div class="card-body pt-0">
            {{-- pdf download link from --}}
            <form id="download_pdf" action="{{ route('reports.profitlossPdf') }}" method="POST">
                @csrf
                <span class="d-print-none print-none export-profit-loss"></span>
                <button type="button" class="btn btn-success d-print-none print-none"
                    onclick="accountReportPrintDetails()">{{ localize('print') }}</button>
                <input type="hidden" name="date" value="{{ $date }}">
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
