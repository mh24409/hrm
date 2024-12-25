@extends('backend.layouts.app')
@section('title', localize('trial_balance'))
@push('css')
    <link href="{{ module_asset('Accounts/css/dailog.css') }}" rel="stylesheet">
@endpush
@section('content')

    @include('accounts::reports_header')

    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')

    <div class="card mb-4 d-print-none fixed-tab-body">

        <div class="card-body ">
            <form id="leadForm" action="{{ route('reports.trilbalancelGenerate') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="pdf" value="0">
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
                        <label for="trail_balnce_type">Type <span class="text-danger">*</span></label>
                        <div class="p-2">
                            <input type="radio" id="full" name="trail_balnce_type"
                                class="form-check-input align-middle" value="2"
                                {{ $trail_balnce_type == 2 ? 'checked' : '' }}>
                            <label class="radio-inline form-check-label align-middle" for="full">
                                {{ localize('full') }}
                            </label>
                            <input type="radio" id="ason" name="trail_balnce_type"
                                class="form-check-input align-middle" value="0"
                                {{ $trail_balnce_type == 0 ? 'checked' : '' }}>
                            <label class="radio-inline form-check-label align-middle" for="ason">
                                {{ localize('as_on') }}
                            </label>
                            <input type="radio" id="periodic" name="trail_balnce_type"
                                class="form-check-input align-middle" value="1"
                                {{ $trail_balnce_type == 1 ? 'checked' : '' }}>
                            <label class="radio-inline form-check-label align-middle" for="periodic">
                                {{ localize('periodic') }}
                            </label>
                            @if ($errors->has('trail_balnce_type'))
                                <div class="error text-danger text-start">{{ $errors->first('trail_balnce_type') }}</div>
                            @endif
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
                    <h5 class="text-center">
                        {{ localize('trial_balance') }}
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
                    @endphp
                    <div class="fs-14">{{ $col1 }} <br> {{ $col2 }}</div>
                </div>
            </div>
        </div>

        <div class="card-body card-body-customize">
            <div class="table-responsive">
                <table id="trail-balance-table"
                    class="table display table-bordered table-striped table-hover w-100 align-middle">
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

        <div class="card-body pt-0">
            {{-- pdf download link from --}}
            <form id="download_pdf" action="{{ route('reports.trilbalancelPdf') }}" method="POST">
                @csrf
                <span class="d-print-none print-none export-trail-balance"></span>
                <button type="button" class="btn btn-success d-print-none print-none"
                    onclick="accountReportPrintDetails()">{{ localize('print') }}</button>
                <input type="hidden" name="date" value="{{ $date }}">
                <input type="hidden" name="trail_balnce_type" value="{{ $trail_balnce_type }}">
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
