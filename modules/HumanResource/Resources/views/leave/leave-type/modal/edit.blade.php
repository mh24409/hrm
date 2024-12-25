<!-- Modal -->
<div class="modal fade" id="update-leave-type-{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    @lang('language.edit_leave_type')
                </h5>
            </div>
            <form class="validateEditForm" action="{{ route('leave-types.update', $data->uuid) }}" method="POST">
                @method('PATCH')
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="leave_type"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('leave_type') }}
                                    <span class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" required class="form-control" name="leave_type"
                                        placeholder="{{ localize('leave_type') }}" value="{{ $data->leave_type }}"
                                        required>
                                </div>

                                @if ($errors->has('leave_type'))
                                    <div class="error text-danger m-2">{{ $errors->first('leave_type') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="leave_code"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('leave_code') }}
                                    <span class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" class="form-control" name="leave_code"
                                        placeholder="{{ localize('leave_code') }}" value="{{ $data->leave_code }}"
                                        required>
                                </div>

                                @if ($errors->has('leave_code'))
                                    <div class="error text-danger m-2">{{ $errors->first('leave_code') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="leave_days"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('leave_days') }}</label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" class="form-control" required name="leave_days"
                                        placeholder="{{ localize('leave_days') }}" value="{{ $data->leave_days }}">
                                </div>

                                @if ($errors->has('leave_days'))
                                    <div class="error text-danger m-2">{{ $errors->first('leave_days') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">@lang('language.close')</button>
                    <button class="btn btn-primary submit_button">@lang('language.update')</button>
                </div>
            </form>
        </div>
    </div>
</div>
