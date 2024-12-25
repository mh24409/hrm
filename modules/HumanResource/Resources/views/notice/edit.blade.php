<!-- Modal -->
<div class="modal fade" id="editNotice{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('edit_notice') }}
                </h5>
            </div>
            <form id="leadForm" action="{{ route('notice.update', $data->uuid) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="notice_type"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('notice_type') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" class="form-control" id="notice_type" name="notice_type"
                                        placeholder="{{ localize('notice_type') }}" value="{{ old('notice_type') ?? $data->notice_type }}"
                                        required>
                                </div>

                                @if ($errors->has('notice_type'))
                                    <div class="error text-danger m-2">{{ $errors->first('notice_type') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="notice_descriptiion"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('notice_descriptiion') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" class="form-control" id="notice_descriptiion" name="notice_descriptiion"
                                        placeholder="{{ localize('notice_descriptiion') }}" value="{{ old('notice_descriptiion') ?? $data->notice_descriptiion }}"
                                        required>
                                </div>

                                @if ($errors->has('notice_descriptiion'))
                                    <div class="error text-danger m-2">{{ $errors->first('notice_descriptiion') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="notice_date"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('notice_date') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" class="form-control date_picker" name="notice_date"
                                        placeholder="{{ localize('notice_date') }}" value="{{ old('notice_date') ?? $data->notice_date }}"
                                        required>
                                </div>

                                @if ($errors->has('notice_date'))
                                    <div class="error text-danger m-2">{{ $errors->first('notice_date') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="notice_attachment"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('notice_attachment') }}</label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="file" class="form-control" id="notice_attachment" name="notice_attachment"
                                        placeholder="{{ localize('notice_attachment') }}"
                                        value="{{ old('notice_attachment') }}">
                                </div>

                                @if ($errors->has('notice_attachment'))
                                    <div class="error text-danger m-2">{{ $errors->first('notice_attachment') }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="notice_by"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('notice_by') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" class="form-control" id="notice_by" name="notice_by"
                                        placeholder="{{ localize('notice_by') }}"
                                        value="{{ old('notice_by') ?? $data->notice_by }}" required>
                                </div>

                                @if ($errors->has('notice_by'))
                                    <div class="error text-danger m-2">{{ $errors->first('notice_by') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button class="btn btn-primary submit_button" id="create_submit">{{ localize('update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
