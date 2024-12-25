<!-- Modal -->
<div class="modal fade" id="create-salary-advance" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('add_salary_advance') }}
                </h5>
            </div>
            <form id="add-salary-advance" class="validateForm" action="{{ route('salary-advance.store') }}"
                method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group mb-2 mx-0 row">
                            <label class="col-md-3 col-form-label ps-0">{{ localize('employee') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-md-9">
                                <select name="employee_id" id="employee_id" required
                                    class="form-control {{ $errors->first('employee_id') ? 'is-invalid' : '' }} select-basic-single">
                                    <option selected disabled>{{ localize('select_employee') }}</option>
                                    @foreach ($employees as $key => $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('employee_id'))
                                    <div class="error text-danger">{{ $errors->first('employee_id') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="amount" class="col-lg-3 col-form-label ps-0 label_amount">
                                {{ localize('amount') }}
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <input type="number" required name="amount" required min="0" step="0.01"
                                    placeholder="{{ localize('amount') }}" class="form-control valid"
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group mb-2 mx-0 row">
                            <label for="salary_month" class="col-lg-3 col-form-label ps-0 label_salary_month">
                                {{ localize('salary_month') }}
                                <span class="text-danger">*</span>
                            </label>

                            <div class="col-lg-9">
                                <input type="month" name="salary_month" required
                                    placeholder="{{ localize('salary_month') }}" class="form-control  "
                                    aria-describedby="emailHelp" autocomplete="off">
                            </div>
                        </div>
                        @radio(['input_name' => 'is_active', 'data_set' => [1 => 'Active', 0 => 'Inactive'], 'value' => 1])
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button type="submit" class="btn btn-success submit_button"
                        id="create_submit">{{ localize('save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
