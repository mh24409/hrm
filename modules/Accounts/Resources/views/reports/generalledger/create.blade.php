@extends('backend.layouts.app')
@section('title', localize('general_ledger'))
@push('css')
    <link href="{{ module_asset('Accounts/css/dailog.css') }}" rel="stylesheet">
@endpush
@section('content')


    @include('accounts::reports_header')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')

    <div class="card mb-4 d-print-none fixed-tab-body">

        <div class="card-body ">
            <form id="leadForm" action="{{ route('reports.ledgergeneralGenerate') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-3 col-xl-3 col-12">
                        <label for="acc_coa_id">{{ localize('ledger_name') }}<span class="text-danger">*</span></label>
                        <select name="acc_coa_id" id="acc_coa_id" class="select-basic-single" required>
                            <option value="" selected disabled>{{ localize('select_one') }}</option>
                            @foreach ($accDropdown as $accvalue)
                                <option value="{{ $accvalue->id }}" {{ $acc_coa_id == $accvalue->id ? 'selected' : '' }}>
                                    {{ $accvalue->account_name }}</option>
                            @endforeach

                        </select>

                        @if ($errors->has('acc_coa_id'))
                            <div class="error text-danger m-2">{{ $errors->first('acc_coa_id') }}</div>
                        @endif
                    </div>
                    <div class="col-md-3 col-xl-3 col-12">
                        <label for="date">{{ localize('date') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control account-date-range" id="date" name="date"
                            value="{{ $date }}" required>

                        @if ($errors->has('date'))
                            <div class="error text-danger m-2">{{ $errors->first('date') }}</div>
                        @endif
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
    <div class="card mb-4 font-arial" id="print-table">
        <div class="card-header border-bottom-0 pb-0">

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
                    <h5 class="text-center">
                        {{ localize('general_ledger_report') }}
                    </h5>
                    <div class="text-center" id="ledgerName"></div>
                    <div class="text-center">
                        {{ localize('from') }} : {{ $fromDate ?? null }} {{ localize('to') }}
                        {{ $toDate ?? null }}
                    </div>

                </div>
                <div class="col text-end">
                    @php
                        $len = strlen(app_setting()->address);
                        $space = strrpos(app_setting()->address, ' ', -$len / 2);
                        $col1 = substr(app_setting()->address, 0, $space);
                        $col2 = substr(app_setting()->address, $space);
                        $totalbalance = 0;
                    @endphp
                    <div class="fs-14">{{ $col1 }} <br> {{ $col2 }}</div>
                </div>
            </div>
        </div>


        <div class="card-body card-body-customize">
            <hr class="m-1">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-middle-6 pb-2">
                            <h6><strong>{{ localize('opening_balance') }} : </strong>
                                {{ empty($getBalanceOpening) ? bt_number_format(0, 2) : bt_number_format($getBalanceOpening) }}
                            </h6>
                            <h6><strong>{{ localize('total_balance') }} : </strong>
                                {{ bt_number_format($tablefooter['totalBalance'], 2) ?? bt_number_format(0, 2) }}</h6>
                        </div>
                        <div class="col-middle-6 text-end"></div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="general-ledger-table" class="table display table-bordered align-middle">
                    <thead>
                        <tr>

                            <th class="wp-3">{{ localize('sl') }}</th>
                            <th class="text-start wp-11">{{ localize('date') }}</th>
                            <th class="text-start wp-11">{{ localize('voucher_no') }}</th>
                            <th class="text-start wp-11">{{ localize('head_name') }}</th>
                            <th class="text-start wp-14">{{ localize('ledger_comment') }}</th>
                            <th class="text-end wp-14">{{ localize('debit') }} ({{ currency() }})</th>
                            <th class="text-end wp-14">{{ localize('credit') }} ({{ currency() }})</th>
                            <th class="text-end wp-14">{{ localize('balance') }} ({{ currency() }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($getTransactionList as $key => $data)
                            <tr>
                                <td> {{ (int) $key + 1 }}</td>
                                <td>{{ $data->voucher_date }}</td>
                                <td>
                                    <div class="showVoucher"
                                        data-url="{{ route('reports.showVoucher', $data->voucher_no) }}"
                                        title="Show Voucher">{{ $data->voucher_no }}</div>
                                </td>
                                <td>{{ $data->accCoa?->account_name }}
                                    @if ($data->accSubcode)
                                        -( {{ $data->accSubcode?->name }} )
                                    @endif
                                </td>
                                <td>{{ $data->ledger_comment }}</td>
                                <td class="text-end">{{ bt_number_format($data->debit) }} </td>
                                <td class="text-end">{{ bt_number_format($data->credit) }}</td>
                                <td class="text-end">{{ bt_number_format($data->balance) }}</td>
                            </tr>


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
                                    class="text-dark">{{ bt_number_format($tablefooter['totalDebit']) ?? bt_number_format(0, 2) }}</strong>
                            </td>
                            <td align="right"><strong
                                    class="text-dark">{{ bt_number_format($tablefooter['totalCradit']) ?? bt_number_format(0, 2) }}</strong>
                            </td>
                            <td align="right"><strong
                                    class="text-dark">{{ bt_number_format($tablefooter['totalBalance']) ?? bt_number_format(0, 2) }}</strong>
                            </td>

                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="card-body pt-0">

            {{-- pdf download link from --}}
            <form id="download_pdf" action="{{ route('reports.ledgergeneralPdf') }}" method="POST">
                @csrf
                <span class="d-print-none print-none export-general-ledger"></span>
                <button type="button" class="btn btn-success d-print-none print-none"
                    onclick="accountReportPrintDetails()">{{ localize('print') }}</button>
                <input type="hidden" name="date" value="{{ $date }}">
                <input type="hidden" name="pdf" value="1">
                <input type="hidden" name="acc_coa_id" value="{{ $acc_coa_id }}">
                <input type="hidden" name="ledger_name" id="ledger_name">
                <button type="submit" class="btn btn-success d-print-none print-none">{{ localize('download_pdf') }}
                </button>
            </form>
        </div>


    </div>

    @include('accounts::reports.voucher.voucher')
@endsection
@push('js')
    <script src="{{ module_asset('Accounts/js/customeacc.js') }}"></script>
    <script src="{{ module_asset('Accounts/js/general-ledger.js') }}"></script>
@endpush
