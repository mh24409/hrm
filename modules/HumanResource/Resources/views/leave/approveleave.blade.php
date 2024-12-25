<form id="leadForm" action="{{ route('leave.approved', $row->uuid) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="modal-body">
        <div class="row">

            <div class="col-md-12 mt-3">
                <div class="row">
                    <label for="leave_approved_start_date"
                        class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('from_date') }}
                        <span class="text-danger">*</span></label>
                    <div class="col-sm-9 col-md-12 col-xl-9">
                        <input type="text" class="form-control date_picker" id="approved_start_date"
                            name="leave_approved_start_date"
                            value="{{ old('leave_approved_start_date') ?? $row->leave_apply_start_date }}" required>
                    </div>

                    @if ($errors->has('leave_approved_start_date'))
                        <div class="error text-danger m-2">{{ $errors->first('leave_approved_start_date') }}
                        </div>
                    @endif
                </div>
            </div>


            <div class="col-md-12 mt-3">
                <div class="row">
                    <label for="leave_approved_end_date"
                        class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('end_date') }}
                        <span class="text-danger">*</span></label>
                    <div class="col-sm-9 col-md-12 col-xl-9">
                        <input type="text" class="form-control date_picker" id="approved_end_date"
                            name="leave_approved_end_date"
                            value="{{ old('leave_approved_end_date') ?? $row->leave_apply_end_date }}" required>
                    </div>

                    @if ($errors->has('leave_approved_end_date'))
                        <div class="error text-danger m-2">{{ $errors->first('leave_approved_end_date') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <div class="row">
                    <label for="total_approved_day"
                        class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('total_days') }}</label>
                    <div class="col-sm-9 col-md-12 col-xl-9">
                        <input type="text" class="form-control" id="approved_total_day" name="total_approved_day"
                            placeholder="Total Days" value="{{ old('total_approved_day') ?? $row->total_apply_day }}"
                            readonly>
                    </div>

                    @if ($errors->has('total_approved_day'))
                        <div class="error text-danger m-2">{{ $errors->first('total_approved_day') }}</div>
                    @endif
                </div>
            </div>


        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ localize('close') }}</button>
        <button class="btn btn-primary submit_button" id="create_submit">{{ localize('approve_leave') }}</button>
    </div>
</form>

<script src="{{ asset('backend/assets/dist/js/custom.js') }}"></script>
<script src="{{ module_asset('HumanResource/js/hrcommon.js') }}"></script>
