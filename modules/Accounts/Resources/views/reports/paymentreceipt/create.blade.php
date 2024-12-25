@extends('backend.layouts.app')
@section('title', localize('receipt_payment_report'))
@push('css')
    <link href="{{ module_asset('Accounts/css/dailog.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('accounts::reports_header')

    <div class="fixed-tab-body">
        @include('backend.layouts.common.validation')
        @include('backend.layouts.common.message')

        <div class="card mb-4 d-print-none ">

            <div class="card-body ">
                <form id="leadForm" action="{{ route('reports.receiptpaymentGenerate') }}" method="POST"
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


                        <div class="col-md-3 col-xl-3 col-12">

                            <label for="bed_no">{{ localize('ledger_type') }}</label>
                            <div class="p-2">
                                <input class="form-check-input " type="radio" name="cash_bank_nature_id" value="cash"
                                    id="is_cash" {{ $cash_bank_nature_id == 'cash' ? 'checked' : '' }}>
                                <label class="form-check-label " for="is_cash">
                                    {{ localize('cash_basis') }}
                                </label>

                                <input class="form-check-input " type="radio" name="cash_bank_nature_id" value="bank"
                                    id="is_accrual" {{ $cash_bank_nature_id == 'bank' ? 'checked' : '' }}>
                                <label class="form-check-label " for="is_accrual">
                                    {{ localize('accrual_basis') }}
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
        <div class="card mb-4 font-arial" id="print-table">
            <div class="card-header">
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
                            {{ localize('receipt_payment_report') }}
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

            <div class="card-body">
                <div class="table-responsive">
                    <table id="" class="table display table-bordered table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th><strong>{{ localize('particulars') }}</strong></th>
                                <th class="text-end"><strong>{{ localize('balance') }} ({{ currency() }})</strong>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td>{{ localize('opening_balance') }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="padding-left: 100px"> <span class=""> {{ $cashParent?->account_name }}
                                    </span>
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
                                <td>{{ localize('receipt') }}</td>
                                <td></td>
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
                                <td>{{ localize('payments') }}</td>
                                <td></td>
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
                                <td>{{ localize('closing_balance') }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="padding-left: 100px"> <span class=""> {{ $cashParent?->account_name }}
                                    </span>
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
            <div class="card-body pt-0">
                {{-- pdf download link from --}}
                <form id="download_pdf" action="{{ route('reports.receiptpaymentPdf') }}" method="POST">
                    @csrf
                    <span class="d-print-none print-none export-receipt-payment"></span>
                    <button type="button" class="btn btn-success d-print-none print-none"
                        onclick="accountReportPrintDetails()">{{ localize('print') }}</button>
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="pdf" value="1">
                    <input type="hidden" name="ledger_name" id="ledger_name">
                    <input type="hidden" name="cash_bank_nature_id" value="{{ $cash_bank_nature_id }}">
                    <button type="submit" class="btn btn-success d-print-none print-none">{{ localize('download_pdf') }}
                    </button>
                </form>
            </div>


        </div>
    </div>

@endsection
@push('js')
    <script src="{{ module_asset('Accounts/js/customeacc.js') }}"></script>
@endpush
