<!-- Modal -->
<div class="modal fade" id="editPointCategory{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    {{ localize('update_point_category') }}
                </h5>
            </div>
            <form id="leadForm" action="{{ route('reward.update', $data->uuid) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="point_category"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('category_name') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" class="form-control" id="point_category" name="point_category"
                                        placeholder="{{ localize('category_name') }}" value="{{ old('point_category') ?? $data->point_category }}"
                                        required>
                                </div>

                                @if ($errors->has('point_category'))
                                    <div class="error text-danger m-2">{{ $errors->first('point_category') }}</div>
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
