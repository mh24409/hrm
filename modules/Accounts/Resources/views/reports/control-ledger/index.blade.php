@extends('backend.layouts.app')
@section('title', localize('control_ledger'))
@push('css')
    <link href="{{ module_asset('Accounts/css/dailog.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('accounts::reports_header')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4 d-print-none fixed-tab-body">
        <div class="card-body ">
            <form id="leadForm" action="{{ route('reports.controlLedger') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="pdf" value="0">
                <div class="row">
                    <div class="col-md-3 col-xl-3 col-12">
                        <label for="acc_coa_id">{{ localize('ledger_name') }} <span class="text-danger">*</span></label>

                        <select name="acc_coa_id" id="acc_coa_id" class="select-basic-single" required>
                            <option value="" selected disabled>{{ localize('select_one') }}</option>
                            @foreach ($accLevelThreeDropdown as $accvalue)
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
                    <h4 class="text-center">
                        {{ localize('control_ledger_report') }}
                    </h4>
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
                    @endphp
                    <div class="fs-14">{{ $col1 }} <br> {{ $col2 }}</div>
                </div>
            </div>
        </div>


        <div class="card-body card-body-customize">

            <div class="table-responsive">
                <table id="control-ledger-table" class="table display table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>{{ localize('head_name') }}</th>
                            <th class="text-end">{{ localize('debit') }} ({{ currency() }})</th>
                            <th class="text-end">{{ localize('credit') }} ({{ currency() }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accForthHeadWithAmount as $key => $item)
                            <tr>
                                <td>{{ $item['account_name'] }}</td>
                                <td class="text-end">{{ bt_number_format($item['debit']) }}</td>
                                <td class="text-end">{{ bt_number_format($item['credit']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-body pt-0 ps-0">

                {{-- pdf download link from --}}
                <form id="download_pdf" action="{{ route('reports.controlLedgerPdf') }}" method="POST">
                    @csrf
                    <span class="d-print-none print-none export-control-ledger"></span>
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
    </div>



@endsection
@push('js')
    <script src="{{ module_asset('Accounts/js/customeacc.js') }}"></script>
    <script src="{{ module_asset('Accounts/js/cash-book.js') }}"></script>
@endpush
