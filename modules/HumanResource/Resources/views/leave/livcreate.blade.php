<!-- Modal -->
<div class="modal fade" id="addLeaveApplication" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('leave_application_create') }}
                </h5>
            </div>
            <form class="validateForm" action="{{ route('leave.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body text-start">
                    <div class="row">

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="employee_id"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold text-start">{{ localize('employee') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <select name="employee_id" id="employee_id" required class="select-basic-single">
                                        <option value="" selected disabled>{{ localize('select_employee') }}
                                        </option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                        @endforeach

                                    </select>

                                    @if ($errors->has('employee_id'))
                                        <div class="error text-danger m-2">{{ $errors->first('employee_id') }}</div>
                                    @endif
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="leave_type_id"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold text-start">{{ localize('leave_type') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <select name="leave_type_id" required id="leave_type_id"
                                        class="select-basic-single">
                                        <option value="" selected disabled>{{ localize('select_leave_type') }}
                                        </option>
                                        @foreach ($leaveTypes as $leaveType)
                                            <option value="{{ $leaveType->id }}">
                                                {{ $leaveType->leave_type }}</option>
                                        @endforeach

                                    </select>

                                    @if ($errors->has('leave_type_id'))
                                        <div class="error text-danger m-2">{{ $errors->first('leave_type_id') }}</div>
                                    @endif
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="leave_apply_start_date"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('from_date') }}
                                    <span class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" required class="form-control date_picker" id="start_date"
                                        name="leave_apply_start_date" placeholder="{{ localize('from_date') }}"
                                        value="{{ current_date() }}" required>
                                </div>

                                @if ($errors->has('leave_apply_start_date'))
                                    <div class="error text-danger m-2">{{ $errors->first('leave_apply_start_date') }}
                                    </div>
                                @endif
                            </div>
                        </div>


                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="leave_apply_end_date"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('end_date') }}
                                    <span class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" class="form-control date_picker" id="end_date"
                                        name="leave_apply_end_date" placeholder="{{ localize('end_date') }}"
                                        value="" required>
                                </div>

                                @if ($errors->has('leave_apply_end_date'))
                                    <div class="error text-danger m-2">{{ $errors->first('leave_apply_end_date') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="total_apply_day"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('total_days') }}</label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" class="form-control" id="total_day" name="total_apply_day"
                                        placeholder="{{ localize('total_days') }}"
                                        value="{{ old('total_apply_day') }}" readonly>
                                </div>

                                @if ($errors->has('total_apply_day'))
                                    <div class="error text-danger m-2">{{ $errors->first('total_apply_day') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="location"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('application_hard_copy') }}</label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="file" class="form-control" id="location" name="location">
                                </div>

                                @if ($errors->has('location'))
                                    <div class="error text-danger m-2">{{ $errors->first('location') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="reason"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('reason') }}</label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <textarea class="form-control" name="reason" id="reason">{{ old('reason') }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button class="btn btn-primary submit_button">{{ localize('save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
