@extends('backend.layouts.app')
@section('title', localize('receipt_payment_report'))
@push('css')
    <link href="{{ module_asset('Accounts/css/dailog.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('accounts::reports_header')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4 d-print-none fixed-tab-body">
        <div class="card-body ">
            <form id="leadForm" action="{{ route('reports.receiptpaymentGenerate') }}" method="POST"
                enctype="multipart/form-data">
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
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ module_asset('Accounts/js/customeacc.js') }}"></script>
@endpush
