@extends('backend.layouts.app')
@section('title', localize('contra_voucher'))
@push('css')
    <link href="{{ asset('backend/assets/custom.css" rel="stylesheet') }}">
@endpush
@section('content')
    @include('accounts::vouchers_header')
    @include('backend.layouts.common.validation')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('contra_voucher') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('read_contra_voucher')
                            <a href="{{ route('contra-vouchers.index') }}" class="btn btn-success btn-sm"><i
                                    class="fa fa-list"></i>&nbsp;{{ localize('contra_voucher_list') }}</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="leadForm" action="{{ route('contra-vouchers.update', $contra_voucher->uuid) }}" method="POST">
                @method('PATCH')
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        @input(['input_name' => 'voucher_type', 'value' => 'CT', 'custom_string' => 'readonly', 'required' => false])
                        <div class="form-group mb-2 mx-0 row">
                            <label for="acc_coa_id"
                                class="col-sm-3 col-form-label ps-0">{{ localize('credit_account_head') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select id="acc_coa_id" name="acc_coa_id" class="form-select">
                                    <option value="">{{ localize('select_one') }}</option>
                                    @foreach ($accounts as $key => $account)
                                        <option value="{{ $account->id }}"
                                            data-isbanknature="{{ $account->is_bank_nature }}"
                                            {{ $account->id == $contra_voucher->reverse_code ? 'selected' : '' }}>
                                            {{ $account->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('acc_coa_id'))
                                    <div class="error text-danger text-start">{{ $errors->first('acc_coa_id') }}</div>
                                @endif
                            </div>
                        </div>
                        <div id="bank_nature_field"
                            class="{{ $contra_voucher->acc_coa_reverse->is_bank_nature == 1 ? '' : 'd-none' }}">
                            @input(['input_name' => 'cheque_no', 'required' => false, 'value' => $contra_voucher->cheque_no])
                            @input(['input_name' => 'cheque_date', 'type' => 'date', 'value' => \Carbon\Carbon::parse($contra_voucher->cheque_date)->format('Y-m-d'), 'required' => false])
                            <div class="form-group mb-2 mx-0 row">
                                <label class="col-sm-3 col-form-label ps-0 "
                                    for="is_honour">{{ localize('is_honors') }}</label>
                                <div class="col-lg-9">
                                    <input class="form-check-input" type="checkbox" id="is_honour" name="is_honour"
                                        value="1" {{ $contra_voucher->is_honour == 1 ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                        @input(['input_name' => 'date', 'type' => 'date', 'value' => \Carbon\Carbon::parse($contra_voucher->date)->format('Y-m-d')])
                        @textarea(['input_name' => 'remarks', 'value' => $contra_voucher->narration, 'required' => false])
                    </div>
                    <table class="table table-bordered table-hover" id="debtAccVoucher">
                        <thead>
                            <tr>
                                <th width="25%" class="text-center">{{ localize('account_name') }}</th>
                                <th width="20%" class="text-center">{{ localize('ledger_comment') }}</th>
                                <th width="20%" class="text-center">{{ localize('debit') }}</th>
                                <th width="20%" class="text-center">{{ localize('credit') }}</th>
                            </tr>
                        </thead>
                        <tbody id="contravoucher">
                            <tr>
                                <td>
                                    <select name="coa_id" id="cmbCode_1" class="form-control"
                                        onchange="load_subtypeOpen(this.value,1)">
                                        <option selected disabled>{{ localize('select_amount') }}</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ $contra_voucher->reverse_code == $account->id ? 'selected' : '' }}>
                                                {{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="ledger_comment"
                                        value="{{ $contra_voucher->ledger_comment }}"
                                        class="form-control total_dprice text-end" id="txtCredit_1" autocomplete="off">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="debit"
                                        value="{{ $contra_voucher->debit }}" class="form-control total_cprice text-end"
                                        id="txtCredit_1" onkeyup="calculationCreditOpen(1)" autocomplete="off">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="credit"
                                        value="{{ $contra_voucher->credit }}" class="form-control total_cprice text-end"
                                        id="txtCredit_1" onkeyup="calculationCreditOpen(1)" autocomplete="off">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="update_submit">{{ localize('update') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ module_asset('Accounts/js/account-ledger.js') }}"></script>
@endpush
