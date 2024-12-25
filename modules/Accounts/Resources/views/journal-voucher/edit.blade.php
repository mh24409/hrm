@extends('backend.layouts.app')
@section('title', localize('journal_voucher'))
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
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('edit_journal_voucher') }}</h6>
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
            <form id="leadForm" action="{{ route('journal-vouchers.update', $journal_voucher->uuid) }}" method="POST">
                @method('PATCH')
                @csrf
                <div class="row">

                    <div class="col-md-6">
                        @input(['input_name' => 'voucher_type', 'value' => 'Journal', 'custom_string' => 'readonly', 'required' => false])
                        @input(['input_name' => 'date', 'type' => 'date', 'value' => \Carbon\Carbon::parse($journal_voucher->date)->format('Y-m-d')])
                        @textarea(['input_name' => 'remarks', 'required' => false, 'value' => $journal_voucher->narration])
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
                            @php
                                $i = 1;
                                $reverse_code = '';
                                $debit = 0;
                                $credit = 0;
                            @endphp
                            @foreach (getVouchersByNo($journal_voucher->voucher_no) as $key => $voucher)
                                @php
                                    $i++;
                                    $reverse_code = $voucher->reverse_code;
                                    $debit += $voucher->debit;
                                    $credit += $voucher->credit;
                                @endphp
                                <tr>
                                    <td>
                                        <select name="debits[{{ $key + 1 }}][coa_id]"
                                            id="cmbCode_{{ $key + 1 }}" class="form-control account_name"
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
                                        <select name="debits[{{ $key + 1 }}][subcode_id]"
                                            id="subtype_{{ $key + 1 }}" class="form-control">
                                            @if ($voucher->acc_subtype_id)
                                                <option value="{{ $voucher->acc_subcode_id }}">
                                                    {{ $voucher->subcode ? $voucher->subcode->name : '' }}</option>
                                            @endif
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="debits[{{ $key + 1 }}][ledger_comment]"
                                            value="{{ $voucher->ledger_comment }}" class="form-control text-end"
                                            id="ledger_comment" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="debits[{{ $key + 1 }}][debit]"
                                            value="{{ $voucher->debit }}" class="form-control total_dprice text-end"
                                            id="txtDebit_{{ $key + 1 }}"
                                            onkeyup="calculationDebtOpen({{ $key + 1 }})" autocomplete="off">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="debits[{{ $key + 1 }}][credit]"
                                            value="{{ $voucher->credit }}" class="form-control total_cprice text-end"
                                            id="txtCredit_{{ $key + 1 }}"
                                            onkeyup="calculationCreditOpen({{ $key + 1 }})" autocomplete="off">
                                        <input type="hidden" step="0.01"
                                            name="debits[{{ $key + 1 }}][acc_voucher_id]" value="{{ $voucher->id }}"
                                            autocomplete="off">
                                    </td>
                                    <td>
                                        <button class="btn btn-danger-soft btn-sm" type="button" value="Delete"
                                            onclick="deleteRowDebtOpen(this, '{{ $voucher->id }}')" autocomplete="off"><i
                                                class="fa fa-trash"></i></button>
                                    </td>
                                    <input type="hidden" name="reversehead_code[]" class="form-control reversehead_code"
                                        id="reversehead_code_{{ $key + 1 }}" value="{{ $voucher->acc_coa_id }}"
                                        readonly="">
                                </tr>
                            @endforeach
                            @php
                                if ($debit > 0 && $credit == 0) {
                                    $credit = $debit;
                                    $debit = 0.0;
                                } else {
                                    $debit = $credit;
                                    $credit = 0.0;
                                }
                            @endphp
                            <tr>
                                <td>
                                    <select name="debits[{{ $i }}][coa_id]" id="cmbCode_{{ $i }}"
                                        class="form-control account_name"
                                        onchange="load_subtypeOpen(this.value,{{ $i }})">
                                        <option value="">{{ localize('select_amount') }}</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}"
                                                {{ $account->id == $reverse_code ? 'selected' : '' }}>
                                                {{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select disabled name="debits[{{ $i }}][subcode_id]"
                                        id="subtype_{{ $i }}" class="form-control">
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="debits[{{ $i }}][ledger_comment]"
                                        value="{{ $voucher->ledger_comment }}" class="form-control text-end"
                                        id="ledger_comment" autocomplete="off">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="debits[{{ $i }}][debit]"
                                        value="{{ $debit }}" class="form-control total_dprice text-end"
                                        id="txtDebit_{{ $i }}"
                                        onkeyup="calculationDebtOpen({{ $i }})" autocomplete="off">
                                </td>
                                <td>
                                    <input type="number" step="0.01" name="debits[{{ $i }}][credit]"
                                        value="{{ $credit }}" class="form-control total_cprice text-end"
                                        id="txtCredit_{{ $i }}"
                                        onkeyup="calculationCreditOpen({{ $i }})" autocomplete="off">
                                    <input type="hidden" step="0.01"
                                        name="debits[{{ $i }}][acc_voucher_id]" value="{{ $voucher->id }}"
                                        autocomplete="off">
                                </td>
                                <td>
                                    <button class="btn btn-danger-soft btn-sm" type="button" value="Delete"
                                        onclick="deleteRowDebtOpen(this)" autocomplete="off"><i
                                            class="fa fa-trash"></i></button>
                                </td>
                                <input type="hidden" name="reversehead_code[]" class="form-control reversehead_code"
                                    id="reversehead_code_{{ $i }}" value="{{ $reverse_code }}"
                                    readonly="">
                            </tr>
                            <input type="hidden" id="rev_code" name="rev_code" value="{{ $reverse_code }}">
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
                                        name="grand_totald" value="{{ number_format($debit > 0 ? $debit : $credit, 2) }}"
                                        readonly="readonly" autocomplete="off">
                                </td>
                                <td class="text-end">
                                    <input type="text" id="grandTotalc" class="form-control text-end "
                                        name="grand_totalc"
                                        value="{{ number_format($credit > 0 ? $credit : $debit, 2) }}"
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
    <script src="{{ module_asset('Accounts/js/jv-row-more.js') }}"></script>
    <script src="{{ module_asset('Accounts/js/account-ledger.js') }}"></script>
@endpush
