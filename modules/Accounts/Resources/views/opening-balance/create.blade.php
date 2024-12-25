@extends('backend.layouts.app')
@section('title', localize('opening_balance'))
@push('css')
    <link href="{{ asset('backend/assets/custom.css') }}" rel="stylesheet">
@endpush
@section('content')


    @include('backend.layouts.common.validation')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('opening_balance') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('read_opening_balance')
                            <a href="{{ route('opening-balances.index') }}" class="btn btn-success btn-sm"><i
                                    class="fa fa-list"></i>&nbsp;{{ localize('balance_list') }}</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="leadForm" action="{{ route('opening-balances.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group mb-2 mx-0 row">
                            <label for="year" class="col-sm-3 col-form-label ps-0">{{ localize('financial_year') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="financial_year_id" class="select-basic-single">
                                    <option value=""> {{ localize('select_financial_year') }}</option>
                                    @foreach ($financial_years as $key => $year)
                                        <option value="{{ $year->id }}">{{ $year->financial_year }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('financial_year_id'))
                                    <div class="error text-danger text-start">{{ $errors->first('financial_year_id') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        @input(['input_name' => 'date', 'type' => 'date'])
                    </div>

                    <table class="table table-bordered table-hover" id="debtAccVoucher">
                        <thead>
                            <tr>
                                <th width="25%" class="text-center">{{ localize('account_name') }}</th>
                                <th width="25%" class="text-center">{{ localize('subtype') }}</th>
                                <th width="20%" class="text-center">{{ localize('debit') }}</th>
                                <th width="20%" class="text-center">{{ localize('credit') }}</th>
                                <th width="10%" class="text-center">{{ localize('action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="debitvoucher">
                            <tr>
                                <td>
                                    <select name="opening_balances[1][coa_id]" id="cmbCode_1" class="select-basic-single"
                                        onchange="load_subtypeOpen(this.value,1)">
                                        <option value="">{{ localize('select_amount') }}</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="opening_balances[1][subcode_id]" id="subtype_1"
                                        class="select-basic-single">
                                        <option value="">{{ localize('select_subtype') }}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="opening_balances[1][debit]" value=""
                                        class="form-control total_dprice text-right" id="txtDebit_1"
                                        onkeyup="calculationDebtOpen(1)" autocomplete="off">
                                </td>
                                <td>
                                    <input type="number" name="opening_balances[1][credit]" value=""
                                        class="form-control total_cprice text-right" id="txtCredit_1"
                                        onkeyup="calculationCreditOpen(1)" autocomplete="off">
                                    <input type="hidden" name="opening_balances[1][is_subtype]" id="isSubtype_1"
                                        value="1" autocomplete="off">
                                </td>
                                <td>
                                    <button class="btn btn-danger-soft btn-sm" type="button" value="Delete"
                                        onclick="deleteRowDebtOpen(this)" autocomplete="off"><i
                                            class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>
                                    <input type="button" id="add_more" class="btn btn-success" name="add_more"
                                        onclick="addaccountOpen('debitvoucher');" value="Add More" autocomplete="off">
                                </td>
                                <td colspan="1" class="text-right"><label for="reason"
                                        class="  col-form-label">{{ localize('total') }}</label>
                                </td>

                                <td class="text-right">
                                    <input type="text" id="grandTotald" class="form-control text-right "
                                        name="grand_totald" value="" readonly="readonly" autocomplete="off">
                                </td>
                                <td class="text-right">
                                    <input type="text" id="grandTotalc" class="form-control text-right "
                                        name="grand_totalc" value="" readonly="readonly" autocomplete="off">
                                </td>
                            </tr>
                        </tfoot>
                    </table>


                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary submit_button"
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
    <script src="{{ module_asset('Accounts/js/opb-row-more.js') }}"></script>
    <script src="{{ module_asset('Accounts/js/account-ledger.js') }}"></script>
@endpush
