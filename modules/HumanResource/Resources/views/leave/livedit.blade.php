<form class="validateEditForm" action="{{ route('leave.update', $row->uuid) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="row">
                    <label for="employee_id"
                        class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold text-start">{{ localize('employee') }}<span
                            class="text-danger">*</span></label>
                    <div class="col-sm-9 col-md-12 col-xl-9">
                        <select name="employee_id" required id="employee_id" class="select-basic-single w-100">
                            <option value="">{{ localize('select_employee') }}</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ $row->employee_id == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->full_name }}</option>
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
                        <select name="leave_type_id" required id="leave_type_id" class="select-basic-single w-100">
                            <option value="">{{ localize('select_leave_type') }}</option>
                            @foreach ($leaveTypes as $leaveType)
                                <option value="{{ $leaveType->id }}"
                                    {{ $row->leave_type_id == $leaveType->id ? 'selected' : '' }}>
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
                        <input type="text" required class="form-control date_picker" id=""
                            name="leave_apply_start_date"
                            value="{{ old('leave_apply_start_date') ?? $row->leave_apply_start_date }}" required>
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
                        <input type="text" required class="form-control date_picker" id=""
                            name="leave_apply_end_date"
                            value="{{ old('leave_apply_end_date') ?? $row->leave_apply_end_date }}" required>
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
                        <input type="text" class="form-control" id="edit_total_day" name="total_apply_day"
                            placeholder="{{ localize('total_days') }}"
                            value="{{ old('total_apply_day') ?? $row->total_apply_day }}" readonly>
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

                        @if (!empty($row->location))
                            <small>
                                <a href="{{ asset('storage/' . $row->location) }}" target="_blank">
                                    @php
                                        $myFile = asset('storage/' . $row->location);
                                        $name = basename($myFile);
                                    @endphp
                                    {{ $name }}
                                </a>
                            </small>
                        @endif
                    </div>
                </div>
                <input type="hidden" name="oldlocation" value="{{ $row->location }}">
            </div>

            <div class="col-md-12 mt-3">
                <div class="row">
                    <label for="location"
                        class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('reason') }}</label>
                    <div class="col-sm-9 col-md-12 col-xl-9">
                        <textarea name="reason" class="form-control" id="reason" cols="65" rows="3">{{ old('reason') ?? $row->reason }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ localize('close') }}</button>
        <button class="btn btn-primary submit_button" id="create_submit">{{ localize('update') }}</button>
    </div>
</form>


<script src="{{ asset('backend/assets/dist/js/custom.js') }}"></script>
