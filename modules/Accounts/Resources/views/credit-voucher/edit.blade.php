@extends('backend.layouts.app')
@section('title', localize('credit_voucher'))
@push('css')
    <link href="{{ asset('backend/assets/custom.css') }}" rel="stylesheet">
@endpush
@section('content')

    @include('accounts::vouchers_header')
    @include('backend.layouts.common.validation')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('edit_credit_voucher') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_credit_voucher')
                            <a href="{{ route('credit-vouchers.index') }}" class="btn btn-success btn-sm"><i
                                    class="fa fa-list"></i>&nbsp;{{ localize('credit_voucher_list') }}</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="leadForm" action="{{ route('credit-vouchers.update', $credit_voucher->uuid) }}" method="POST">
                @method('PATCH')
                @csrf
                <div class="row">

                    <div class="col-md-6">
                        @input(['input_name' => 'voucher_type', 'value' => 'Credit', 'custom_string' => 'readonly', 'required' => false])
                        <div class="form-group mb-2 mx-0 row">
                            <label for="acc_coa_id"
                                class="col-sm-3 col-form-label ps-0">{{ localize('credit_account_head') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select id="acc_coa_id" name="acc_coa_id" class="form-select">
                                    <option value="">{{ localize('select_one') }}</option>
                                    @foreach ($credit_accounts as $key => $account)
                                        <option value="{{ $account->id }}"
                                            data-isbanknature="{{ $account->is_bank_nature }}"
                                            {{ $account->id == $credit_voucher->reverse_code ? 'selected' : '' }}>
                                            {{ $account->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('acc_coa_id'))
                                    <div class="error text-danger text-start">{{ $errors->first('acc_coa_id') }}</div>
                                @endif
                            </div>
                        </div>
                        <div id="bank_nature_field"
                            class="{{ $credit_voucher->acc_coa_reverse->is_bank_nature == 1 ? '' : 'd-none' }}">
                            @input(['input_name' => 'cheque_no', 'required' => false, 'value' => $credit_voucher->cheque_no])
                            @input(['input_name' => 'cheque_date', 'type' => 'date', 'value' => \Carbon\Carbon::parse($credit_voucher->cheque_date)->format('Y-m-d'), 'required' => false])
                            <div class="form-group mb-2 mx-0 row">
                                <label class="col-sm-3 col-form-label ps-0 "
                                    for="is_honour">{{ localize('is_honors') }}</label>
                                <div class="col-lg-9">
                                    <input class="form-check-input" type="checkbox" id="is_honour" name="is_honour"
                                        value="1" {{ $credit_voucher->is_honour == 1 ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                        @input(['input_name' => 'date', 'type' => 'date', 'value' => \Carbon\Carbon::parse($credit_voucher->date)->format('Y-m-d')])
                        @textarea(['input_name' => 'remarks', 'required' => false, 'value' => $credit_voucher->narration])
                    </div>

                    <table class="table table-bordered table-hover" id="debtAccVoucher">
                        <thead>
                            <tr>
                                <th width="25%" class="text-center">{{ localize('account_name') }}</th>
                                <th width="25%" class="text-center">{{ localize('subtype') }}</th>
                                <th width="20%" class="text-center">{{ localize('ledger_comment') }}</th>
                                <th width="20%" class="text-center">{{ localize('amount') }}</th>
                                <th width="10%" class="text-center">{{ localize('action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="creditvoucher">
                            @foreach (getVouchersByNo($credit_voucher->voucher_no) as $key => $voucher)
                                <tr>
                                    <td>
                                        <select name="credits[{{ $key + 1 }}][coa_id]"
                                            id="cmbCode_{{ $key + 1 }}" class="form-control"
                                            onchange="load_subtypeOpen(this.value,{{ $key + 1 }})">
                                            <option selected disabled>{{ localize('select_amount') }}</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}"
                                                    {{ $account->id == $voucher->acc_coa_id ? 'selected' : '' }}>
                                                    {{ $account->account_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="credits[{{ $key + 1 }}][subcode_id]"
                                            id="subtype_{{ $key + 1 }}" class="form-control">
                                            @if ($voucher->acc_subtype_id)
                                                <option value="{{ $voucher->acc_subcode_id }}">
                                                    {{ $voucher->subcode ? $voucher->subcode->name : '' }}</option>
                                            @endif
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="credits[{{ $key + 1 }}][ledger_comment]"
                                            value="{{ $voucher->ledger_comment }}" class="form-control"
                                            id="txtCredit_{{ $key + 1 }}" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="credits[{{ $key + 1 }}][amount]"
                                            value="{{ $voucher->credit }}" class="form-control total_cprice text-end"
                                            id="txtCredit_{{ $key + 1 }}" onkeyup="calculationCreditOpen(1)"
                                            autocomplete="off">

                                        <input type="hidden" step="0.01"
                                            name="credits[{{ $key + 1 }}][acc_voucher_id]"
                                            value="{{ $voucher->id }}" autocomplete="off">
                                    </td>
                                    <td>
                                        <button class="btn btn-danger-soft btn-sm" type="button" value="Delete"
                                            onclick="deleteRowDebtOpen(this, '{{ $voucher->id }}')" autocomplete="off"><i
                                                class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>
                                    <input type="button" id="add_more" class="btn btn-success" name="add_more"
                                        onclick="addaccountOpen('creditvoucher');" value="Add More" autocomplete="off">
                                </td>
                                <td colspan="2" class="text-end">
                                    <label for="reason" class="  col-form-label">{{ localize('total') }}</label>
                                </td>

                                <td class="text-end">
                                    <input type="text" id="grandTotalc" class="form-control text-end "
                                        name="grand_totalc"
                                        value="{{ getVouchersByNo($credit_voucher->voucher_no)->sum('credit') }}"
                                        readonly="readonly" autocomplete="off">
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success submit_button"
                            id="create_submit">{{ localize('update') }}</button>
                        <input type="hidden" name="" id="headoption"
                            value="<option value=''> Please select</option><?php foreach ($accounts as $acc2) {?><option value='<?php echo $acc2->id; ?>'><?php echo $acc2->account_name; ?></option><?php }?>">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ module_asset('Accounts/js/cv-row-more.js') }}"></script>
    <script src="{{ module_asset('Accounts/js/account-ledger.js') }}"></script>
@endpush
