@extends('backend.layouts.app')
@section('title', localize('predefine_accounts'))
@section('content')


    @include('backend.layouts.common.validation')
    <div class="alert alert-warning text-center" role="alert">
        <h3>Warning: Please don't change any Predefined Account.</h3>
        <p>if you are not sure about your accounts otherwise you will get wrong accounting report in your system.
        </p>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('predefine_accounts') }}</h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form id="leadForm" action="{{ route('predefine-accounts.store') }}" method="POST">
                @csrf
                <div class="row">

                    <div class="col-lg-6">
                        <div class="form-group mb-2 mx-0 row">
                            <label for="year" class="col-sm-3 col-form-label ps-0">{{ localize('cash_code') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="cash_code" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->cash_code == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('cash_code'))
                                    <div class="error text-danger text-start">{{ $errors->first('cash_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="bank_code" class="col-sm-3 col-form-label ps-0">{{ localize('bank_code') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="bank_code" class="form-select">
                                    @foreach ($thirdLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->bank_code == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('bank_code'))
                                    <div class="error text-danger text-start">{{ $errors->first('bank_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="advance" class="col-sm-3 col-form-label ps-0">{{ localize('advance') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="advance" class="form-select">
                                    @foreach ($thirdLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->advance == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('advance'))
                                    <div class="error text-danger text-start">{{ $errors->first('advance') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="fixed_asset"
                                class="col-sm-3 col-form-label ps-0">{{ localize('fixed_asset') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="fixed_asset" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->fixed_asset == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('fixed_asset'))
                                    <div class="error text-danger text-start">{{ $errors->first('fixed_asset') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="purchase_code"
                                class="col-sm-3 col-form-label ps-0">{{ localize('purchase_code') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="purchase_code" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->purchase_code == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('purchase_code'))
                                    <div class="error text-danger text-start">{{ $errors->first('purchase_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="sales_code" class="col-sm-3 col-form-label ps-0">{{ localize('sales_code') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="sales_code" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->sales_code == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('sales_code'))
                                    <div class="error text-danger text-start">{{ $errors->first('sales_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="customer_code"
                                class="col-sm-3 col-form-label ps-0">{{ localize('customer_code') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="customer_code" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->customer_code == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('customer_code'))
                                    <div class="error text-danger text-start">{{ $errors->first('customer_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="supplier_code"
                                class="col-sm-3 col-form-label ps-0">{{ localize('supplier_code') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="supplier_code" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->supplier_code == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('supplier_code'))
                                    <div class="error text-danger text-start">{{ $errors->first('supplier_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="costs_of_good_solds"
                                class="col-sm-3 col-form-label ps-0">{{ localize('costs_of_good_solds') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="costs_of_good_solds" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->costs_of_good_solds == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('costs_of_good_solds'))
                                    <div class="error text-danger text-start">{{ $errors->first('costs_of_good_solds') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="vat" class="col-sm-3 col-form-label ps-0">{{ localize('vat') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="vat" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->vat == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('vat'))
                                    <div class="error text-danger text-start">{{ $errors->first('vat') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="purchase_discount"
                                class="col-sm-3 col-form-label ps-0">{{ localize('purchase_discount') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="purchase_discount" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->purchase_discount == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('purchase_discount'))
                                    <div class="error text-danger text-start">{{ $errors->first('purchase_discount') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="employee_salary_expense"
                                class="col-sm-3 col-form-label ps-0">{{ localize('employee_salary_expense') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="employee_salary_expense" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->employee_salary_expense == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('employee_salary_expense'))
                                    <div class="error text-danger text-start">
                                        {{ $errors->first('employee_salary_expense') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="emp_npf_contribution"
                                class="col-sm-3 col-form-label ps-0">{{ localize('emp_npf_contribution') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="emp_npf_contribution" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->emp_npf_contribution == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('emp_npf_contribution'))
                                    <div class="error text-danger text-start">{{ $errors->first('emp_npf_contribution') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="empr_npf_contribution"
                                class="col-sm-3 col-form-label ps-0">{{ localize('empr_npf_contribution') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="empr_npf_contribution" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->empr_npf_contribution == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('empr_npf_contribution'))
                                    <div class="error text-danger text-start">
                                        {{ $errors->first('empr_npf_contribution') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group mb-2 mx-0 row">
                            <label for="tax" class="col-sm-3 col-form-label ps-0">{{ localize('tax') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="tax" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->tax == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('tax'))
                                    <div class="error text-danger text-start">{{ $errors->first('tax') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="inventory_code"
                                class="col-sm-3 col-form-label ps-0">{{ localize('inventory_code') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="inventory_code" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->inventory_code == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('inventory_code'))
                                    <div class="error text-danger text-start">{{ $errors->first('inventory_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="current_year_profit_loss_code"
                                class="col-sm-3 col-form-label ps-0">{{ localize('current_year_profit_loss_code') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="current_year_profit_loss_code" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->current_year_profit_loss_code == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('current_year_profit_loss_code'))
                                    <div class="error text-danger text-start">
                                        {{ $errors->first('current_year_profit_loss_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="last_year_profit_loss_code"
                                class="col-sm-3 col-form-label ps-0">{{ localize('last_year_profit_loss_code') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="last_year_profit_loss_code" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->last_year_profit_loss_code == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('last_year_profit_loss_code'))
                                    <div class="error text-danger text-start">
                                        {{ $errors->first('last_year_profit_loss_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="salary_code"
                                class="col-sm-3 col-form-label ps-0">{{ localize('salary_code') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="salary_code" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->salary_code == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('salary_code'))
                                    <div class="error text-danger text-start">{{ $errors->first('salary_code') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="prov_state_tax"
                                class="col-sm-3 col-form-label ps-0">{{ localize('prov_state_tax') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="prov_state_tax" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->prov_state_tax == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('prov_state_tax'))
                                    <div class="error text-danger text-start">{{ $errors->first('prov_state_tax') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="state_tax" class="col-sm-3 col-form-label ps-0">{{ localize('state_tax') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="state_tax" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->state_tax == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('state_tax'))
                                    <div class="error text-danger text-start">{{ $errors->first('state_tax') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="sales_discount"
                                class="col-sm-3 col-form-label ps-0">{{ localize('sales_discount') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="sales_discount" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->sales_discount == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('sales_discount'))
                                    <div class="error text-danger text-start">{{ $errors->first('sales_discount') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="shipping_cost1"
                                class="col-sm-3 col-form-label ps-0">{{ localize('shipping_cost1') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="shipping_cost1" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->shipping_cost1 == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('shipping_cost1'))
                                    <div class="error text-danger text-start">{{ $errors->first('shipping_cost1') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="shipping_cost2"
                                class="col-sm-3 col-form-label ps-0">{{ localize('shipping_cost2') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="shipping_cost2" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->shipping_cost2 == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('shipping_cost2'))
                                    <div class="error text-danger text-start">{{ $errors->first('shipping_cost2') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="prov_npf_code"
                                class="col-sm-3 col-form-label ps-0">{{ localize('prov_npf_code') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="prov_npf_code" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->prov_npf_code == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('prov_npf_code'))
                                    <div class="error text-danger text-start">{{ $errors->first('prov_npf_code') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="emp_icf_contribution"
                                class="col-sm-3 col-form-label ps-0">{{ localize('emp_icf_contribution') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="emp_icf_contribution" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->emp_icf_contribution == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('emp_icf_contribution'))
                                    <div class="error text-danger text-start">
                                        {{ $errors->first('emp_icf_contribution') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="empr_icf_contribution"
                                class="col-sm-3 col-form-label ps-0">{{ localize('empr_icf_contribution') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9">
                                <select name="empr_icf_contribution" class="form-select">
                                    @foreach ($fourthLevelcoas as $key => $coa)
                                        <option value="{{ $coa->id }}"
                                            {{ @$predefine_account->empr_icf_contribution == $coa->id ? 'selected' : '' }}>
                                            {{ $coa->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('empr_icf_contribution'))
                                    <div class="error text-danger text-start">
                                        {{ $errors->first('empr_icf_contribution') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        @can('create_predefine_accounts')
                            <button type="submit" class="btn btn-primary submit_button"
                                id="create_submit">{{ @$predefine_account ? 'update' : 'save' }}</button>
                        @endcan
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('js')
    <script src="{{ module_asset('Accounts/js/predefine.min.js') }}"></script>
@endpush
