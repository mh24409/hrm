@extends('backend.layouts.app')
@section('title', localize('journal_voucher_list'))
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
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('journal_voucher') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('read_journal_voucher')
                            <a href="{{ route('journal-vouchers.index') }}" class="btn btn-success btn-sm"><i
                                    class="fa fa-list"></i>&nbsp;{{ localize('journal_voucher_list') }}</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="leadForm" action="{{ route('journal-vouchers.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="rev_code" name="rev_code">
                <div class="row">

                    <div class="col-md-6">
                        @input(['input_name' => 'voucher_type', 'value' => 'Journal', 'custom_string' => 'readonly', 'required' => false])

                        @input(['input_name' => 'date', 'type' => 'date', 'value' => app_setting()->fixed_date ? app_setting()->fixed_date : current_date()])
                        @textarea(['input_name' => 'remarks', 'required' => false])
                    </div>

                    <table class="table table-bordered table-hover" id="debtAccVoucher">
                        <thead>
                            <tr>
                                <th width="15%" class="text-center">{{ localize('account_name') }}</th>
                                <th width="15%" class="text-center">{{ localize('subtype') }}</th>
                                <th width="20%" class="text-center">{{ localize('ledger_comment') }}</th>
                                <th width="10%" class="text-center">{{ localize('debit') }}</th>
                                <th width="10%" class="text-center">{{ localize('credit') }}</th>
                                <th width="5%" class="text-center">{{ localize('action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="creditvoucher">
                            <tr>
                                <td>
                                    <select name="debits[1][coa_id]" id="cmbCode_1" required
                                        class="form-control select-basic-single account_name"
                                        onchange="load_subtypeOpen(this.value,1)">
                                        <option selected disabled>{{ localize('select_amount') }}</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="debits[1][subcode_id]" id="subtype_1" disabled
                                        class="form-control select-basic-single" disabled>
                                        <option value="">{{ localize('select_subtype') }}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="debits[1][ledger_comment]" value=""
                                        class="form-control text-end" id="ledger_comment" autocomplete="off">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="debits[1][debit]" value=""
                                        class="form-control total_dprice text-end" id="txtDebit_1"
                                        onkeyup="calculationDebtOpen(1)" autocomplete="off">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="debits[1][credit]" value=""
                                        class="form-control total_cprice text-end" id="txtCredit_1"
                                        onkeyup="calculationCreditOpen(1)" autocomplete="off">
                                </td>
                                <td>
                                    <button class="btn btn-danger-soft btn-sm" type="button" value="Delete"
                                        onclick="deleteRowDebtOpen(this)" autocomplete="off"><i
                                            class="fa fa-trash"></i></button>
                                </td>
                                <input type="hidden" name="reversehead_code[]" class="form-control reversehead_code"
                                    id="reversehead_code_1" readonly="">
                            </tr>

                            <tr>
                                <td>
                                    <select name="debits[2][coa_id]" id="cmbCode_2" required
                                        class="form-control select-basic-single account_name"
                                        onchange="load_subtypeOpen(this.value,2)">
                                        <option value="">{{ localize('select_amount') }}</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="debits[2][subcode_id]" disabled id="subtype_2"
                                        class="form-control select-basic-single">
                                        <option value="">{{ localize('select_subtype') }}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="debits[2][ledger_comment]" value=""
                                        class="form-control  text-end" id="ledger_comment" autocomplete="off">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="debits[2][debit]" value=""
                                        class="form-control total_dprice text-end" id="txtDebit_2"
                                        onkeyup="calculationDebtOpen(2)" autocomplete="off">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="debits[2][credit]" value=""
                                        class="form-control total_cprice text-end" id="txtCredit_2"
                                        onkeyup="calculationCreditOpen(2)" autocomplete="off">
                                </td>
                                <td>
                                    <button class="btn btn-danger-soft btn-sm" type="button" value="Delete"
                                        onclick="deleteRowDebtOpen(this)" autocomplete="off"><i
                                            class="fa fa-trash"></i></button>
                                </td>
                                <input type="hidden" name="reversehead_code[]" class="form-control reversehead_code"
                                    id="reversehead_code_2" readonly="">
                            </tr>

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
                                    <input type="text" id="grandTotald" class="form-control text-end "
                                        name="grand_totald" value="" readonly="readonly" autocomplete="off">
                                </td>
                                <td class="text-end">
                                    <input type="text" id="grandTotalc" class="form-control text-end "
                                        name="grand_totalc" value="" readonly="readonly" autocomplete="off">
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success submit_button"
                            id="create_submit">{{ localize('save') }}</button>
                        <input type="hidden" name="" id="headoption"
                            value="<option value=''> Please select</option><?php foreach ($accounts as $acc2) {?><option value='<?php echo $acc2->id; ?>'><?php echo $acc2->account_name; ?></option><?php }?>">
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('js')
    <script src="{{ module_asset('Accounts/js/jv-row-more.js') }}"></script>
    <script src="{{ module_asset('Accounts/js/account-ledger.js') }}"></script>
@endpush
