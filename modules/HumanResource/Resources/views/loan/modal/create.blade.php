<!-- Modal -->
<div class="modal fade" id="create-loan" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('add_new_loan') }}
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal">×</button>
            </div>
            <form class="validateForm" action="{{ route('loans.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group mb-2 mx-0 row">
                            <label for="employee_id"
                                class="col-sm-3 col-form-label ps-0">{{ localize('employee_name') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 text-start">
                                <select name="employee_id" id="employee_id" required
                                    class="form-control select-basic-single">
                                    <option value="" selected disabled>{{ localize('select_employee') }}</option>
                                    @foreach ($employees as $key => $employee)
                                        <option value="{{ $employee->id }}"
                                            {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->full_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('employee_id'))
                                    <div class="error text-danger text-start">{{ $errors->first('employee_id') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group mb-2 mx-0 row">
                            <label for="permission_by_id" class="col-lg-3 col-form-label ps-0">{{ localize('permitted_by') }}<span class="text-danger">*</span></label>
                            <div class="col-lg-9 text-start">
                                <select name="permission_by_id" id="permission_by_id" required
                                    class="form-control select-basic-single">
                                    <option value="" selected disabled>{{ localize('select_supervisor') }}</option>
                                    @foreach ($supervisors as $supervisor)
                                        <option value="{{ $supervisor->id }}"
                                            {{ old('permission_by_id') == $supervisor->id ? 'selected' : '' }}>
                                            {{ $supervisor->full_name }}</option>
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
                                <textarea class="form-control" name="loan_details" id="loan_details" placeholder="{{ localize('loan_details') }}">{{ old('loan_details') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="amount" class="col-lg-3 col-form-label ps-0">{{ localize('amount') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 text-start">
                                <input type="number" required id="loan-amount" name="amount"
                                    placeholder="{{ localize('amount') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="approved_date"
                                class="col-lg-3 col-form-label ps-0">{{ localize('approved_date') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 text-start">
                                <input type="text" name="approved_date" required id="approved_date" value=""
                                    placeholder="{{ localize('approved_date') }}" class="form-control date_picker">
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="repayment_start_date"
                                class="col-lg-3 col-form-label ps-0">{{ localize('repayment_from') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 text-start">
                                <input type="text" name="repayment_start_date" required id="repayment_start_date"
                                    value="" placeholder="{{ localize('repayment_from') }}"
                                    class="form-control date_picker">
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="interest-rate"
                                class="col-lg-3 col-form-label ps-0">{{ localize('interest_percentage(%)') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 text-start">
                                <input type="number" required id="interest-rate" name="interest_rate"
                                    placeholder="{{ localize('interest_percentage') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="installment-period"
                                class="col-lg-3 col-form-label ps-0">{{ localize('installment_period') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 text-start">
                                <input type="number" required id="installment-period" name="installment_period"
                                    placeholder="{{ localize('installment_period') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="repayment-amount"
                                class="col-lg-3 col-form-label ps-0">{{ localize('repayment_amount') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 text-start">
                                <input type="number" required id="repayment-amount" name="repayment_amount"
                                    placeholder="{{ localize('repayment_amount') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="installment-amount"
                                class="col-lg-3 col-form-label ps-0">{{ localize('installment') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 text-start">
                                <input type="number" required id="installment-amount" name="installment"
                                    placeholder="{{ localize('installment') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="is_active"
                                class="col-lg-3 col-form-label ps-0">{{ localize('status') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-lg-9 text-start">
                                <select name="is_active" id="is_active" required class="form-control select-basic-single">
                                    <option value="1">{{localize('active')}}</option>
                                    <option value="0">{{localize('inactive')}}</option>
                                </select>
                                @if ($errors->has('is_active'))
                                    <div class="error text-danger text-start">{{ $errors->first('is_active') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button type="reset" class="btn btn-primary reset">{{ localize('reset') }}</button>
                    <button class="btn btn-success submit_button" id="create_submit">{{ localize('save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
