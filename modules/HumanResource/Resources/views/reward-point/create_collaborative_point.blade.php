<!-- Modal -->
<div class="modal fade" id="addCollaborativePoint" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('new_collaborative_point') }}
                </h5>
            </div>
            <form id="leadForm" action="{{ route('reward.point-collaborative-store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="employee_id"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('employee') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">

                                    <select name="employee_id" id="employee_id" class="form-control select-basic-single" required>
                                        <option value="">{{ localize('employee_name') }}</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->full_name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>

                                @if ($errors->has('employee_id'))
                                    <div class="error text-danger m-2">{{ $errors->first('employee_id') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="reason"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('reason') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" class="form-control" id="reason" name="reason"
                                        placeholder="{{ localize('reason') }}" value="{{ old('reason') }}" required>
                                </div>

                                @if ($errors->has('reason'))
                                    <div class="error text-danger m-2">{{ $errors->first('reason') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="point"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('point') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" class="form-control" id="point" name="point"
                                        placeholder="{{ localize('point') }}" value="{{ old('point') }}"
                                        required>
                                </div>

                                @if ($errors->has('point'))
                                    <div class="error text-danger m-2">{{ $errors->first('point') }}</div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button class="btn btn-primary submit_button" id="create_submit">{{ localize('save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
