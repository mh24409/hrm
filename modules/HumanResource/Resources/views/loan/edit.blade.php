@extends('backend.layouts.app')
@section('title', localize('loan'))
@section('content')
    @include('humanresource::loan_header')
    @include('backend.layouts.common.validation')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('edit_loan') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_loan')
                            <a href="{{ route('hr.loans.index') }}" class="btn btn-success"><i
                                    class="fa fa-list"></i>&nbsp;{{ localize('loan_list') }}</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <form class="validateEditForm" action="{{ route('loans.update', $loan->uuid) }}" method="POST">
            @method('PATCH')
            @csrf
            <div class="card-body">
                <div class="form-group mb-2 mx-0 row">
                    <label for="employee_id" class="col-sm-3 col-form-label ps-0">{{ localize('employee_name') }}<span
                            class="text-danger">*</span></label>
                    <div class="col-lg-9 text-start">
                        <select name="employee_id" id="employee_id" required class="form-control select-basic-single">
                            <option value="" selected disabled>{{ localize('select_employee') }}</option>
                            @foreach ($employees as $key => $employee)
                                <option value="{{ $employee->id }}"
                                    {{ $loan->employee_id == $employee->id ? 'selected' : '' }}>{{ $employee->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('employee_id'))
                            <div class="error text-danger text-start">{{ $errors->first('employee_id') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group mb-2 mx-0 row">
                    <label for="permission_by_id" class="col-lg-3 col-form-label ps-0">{{ localize('permitted_by') }}<span
                            class="text-danger">*</span></label>
                    <div class="col-lg-9 text-start">
                        <select name="permission_by_id" id="permission_by_id" required
                            class="form-control select-basic-single">
                            <option value="" selected disabled>{{ localize('select_supervisor') }}</option>
                            @foreach ($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}"
                                    {{ $loan->permission_by_id == $supervisor->id ? 'selected' : '' }}>
                                    {{ $supervisor->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('permission_by_id'))
                            <div class="error text-danger text-start">{{ $errors->first('permission_by_id') }}</div>
                        @endif
                    </div>
                </div>

                <div class="form-group mb-2 mx-0 row">
                    <label for="amount" class="col-lg-3 col-form-label ps-0">{{ localize('loan_details') }}</label>
                    <div class="col-lg-9 text-start">
                        <textarea class="form-control" name="loan_details" id="loan_details" placeholder="{{ localize('loan_details') }}">{{ $loan->loan_details }}</textarea>
                    </div>
                </div>
                <div class="form-group mb-2 mx-0 row">
                    <label for="amount" class="col-lg-3 col-form-label ps-0">{{ localize('amount') }}<span
                            class="text-danger">*</span></label>
                    <div class="col-lg-9 text-start">
                        <input type="number" class="form-control" required id="loan-amount" name="amount"
                            placeholder="{{ localize('amount') }}" value="{{ $loan->amount }}">
                    </div>
                </div>
                <div class="form-group mb-2 mx-0 row">
                    <label for="approved_date" class="col-lg-3 col-form-label ps-0">{{ localize('approved_date') }}<span
                            class="text-danger">*</span></label>
                    <div class="col-lg-9 text-start">
                        <input type="text" class="form-control date_picker" name="approved_date" required
                            id="approved_date" placeholder="{{ localize('approved_date') }}"
                            value="{{ $loan->approved_date }}">
                    </div>
                </div>
                <div class="form-group mb-2 mx-0 row">
                    <label for="repayment_start_date"
                        class="col-lg-3 col-form-label ps-0">{{ localize('repayment_from') }}<span
                            class="text-danger">*</span></label>
                    <div class="col-lg-9 text-start">
                        <input type="text" class="form-control date_picker" name="repayment_start_date" required
                            id="repayment_start_date" value="{{ $loan->repayment_start_date }}"
                            placeholder="{{ localize('repayment_from') }}">
                    </div>
                </div>
                <div class="form-group mb-2 mx-0 row">
                    <label for="interest-rate"
                        class="col-lg-3 col-form-label ps-0">{{ localize('interest_percentage(%)') }}<span
                            class="text-danger">*</span></label>
                    <div class="col-lg-9 text-start">
                        <input type="number" class="form-control" required id="interest-rate" name="interest_rate"
                            placeholder="{{ localize('interest_percentage') }}" value="{{ $loan->interest_rate }}">
                    </div>
                </div>
                <div class="form-group mb-2 mx-0 row">
                    <label for="installment-period"
                        class="col-lg-3 col-form-label ps-0">{{ localize('installment_period') }}<span
                            class="text-danger">*</span></label>
                    <div class="col-lg-9 text-start">
                        <input type="number" class="form-control" required id="installment-period"
                            name="installment_period" placeholder="{{ localize('installment_period') }}"
                            value="{{ $loan->installment_period }}">
                    </div>
                </div>
                <div class="form-group mb-2 mx-0 row">
                    <label for="repayment-amount"
                        class="col-lg-3 col-form-label ps-0">{{ localize('repayment_amount') }}<span
                            class="text-danger">*</span></label>
                    <div class="col-lg-9 text-start">
                        <input type="number" class="form-control" required id="repayment-amount"
                            name="repayment_amount" placeholder="{{ localize('repayment_amount') }}"
                            value="{{ $loan->repayment_amount }}">
                    </div>
                </div>
                <div class="form-group mb-2 mx-0 row">
                    <label for="installment-amount"
                        class="col-lg-3 col-form-label ps-0">{{ localize('installment') }}<span
                            class="text-danger">*</span></label>
                    <div class="col-lg-9 text-start">
                        <input type="number" class="form-control" required id="installment-amount" name="installment"
                            placeholder="{{ localize('installment') }}" value="{{ $loan->installment }}">
                    </div>
                </div>
                <div class="form-group mb-2 mx-0 row">
                    <label for="is_active" class="col-lg-3 col-form-label ps-0">{{ localize('status') }}<span
                            class="text-danger">*</span></label>
                    <div class="col-lg-9 text-start">
                        <select name="is_active" id="is_active" required class="form-control select-basic-single">
                            <option value="1" {{ $loan->is_active == 1 ? 'selected' : '' }}>{{ localize('active') }}
                            </option>
                            <option value="0" {{ $loan->is_active == 0 ? 'selected' : '' }}>
                                {{ localize('inactive') }}</option>
                        </select>
                        @if ($errors->has('is_active'))
                            <div class="error text-danger text-start">{{ $errors->first('is_active') }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('hr.loans.index') }}" class="btn btn-danger">{{ localize('close') }}</a>
                <button type="submit" class="btn btn-success" id="update_submit">{{ localize('update') }}</button>
            </div>
    </div>
    </form>

@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/loan.js') }}"></script>
@endpush
