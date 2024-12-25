<!-- Modal -->
<div class="modal fade" id="edit-salary-advance-{{ $row->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('edit_salary_advance') }}
                </h5>
            </div>
            <form id="edit-salary-advance" action="{{ route('salary-advance.update', $row->uuid) }}" method="POST">
                @method('PATCH')
                @csrf
                <div class="modal-body">
                    <div class="card-body">
                        <div class="form-group row mb-2">
                            <label for="employee-input"
                                class="col-sm-3 col-form-label font-weight-600">{{ localize('employee') }}</label>
                            <div class="col-sm-9">
                                <input type="text" id="employee-input" class="form-control"
                                    value="{{ $row->employee ? $row->employee->full_name : '' }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <label for="amount"
                                class="col-sm-3 col-form-label font-weight-600">{{ localize('amount') }}</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="amount" name="amount"
                                    value="{{ $row->amount }}" placeholder="{{ localize('amount') }}">
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label for="salary_month"
                                class="col-sm-3 col-form-label font-weight-600">{{ localize('salary_month') }}</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="salary_month" name="salary_month"
                                    value="{{ $row->salary_month }}" placeholder="{{ localize('salary_month') }}"
                                    disabled>
                            </div>
                        </div>

                        <div class="form-group row mb-2">
                            <label for="is_active"
                                class="col-sm-3 col-form-label font-weight-600">{{ localize('is_active') }}</label>
                            <div class="col-sm-9">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="active" name="is_active" class="custom-control-input"
                                        value="1" {{ $row->is_active == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="active">{{ localize('active') }}</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="inactive" name="is_active" class="custom-control-input"
                                        value="0" {{ $row->is_active == 0 ? 'checked' : '' }}>
                                    <label class="custom-control-label"
                                        for="inactive">{{ localize('inactive') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button type="submit" class="btn btn-success submit_button">{{ localize('update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
