<!-- Modal -->
<div class="modal fade" id="editUnit{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" 
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">{{ localize('edit_unit') }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal">×</button>
            </div>
            <form action="{{ route('units.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-12 mt-3">
                            <div class="row">
                                <label for="unit"
                                    class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('unit') }}<span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9 col-md-12 col-xl-9">
                                    <input type="text" class="form-control" id="unit" name="unit"
                                        placeholder="{{ localize('unit') }}" value="{{ old('unit') ??  $data->unit }}" required>
                                </div>

                                @if ($errors->has('unit'))
                                    <div class="error text-danger m-2">{{ $errors->first('unit') }}</div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"
                        data-bs-dismiss="modal">{{ localize('close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ localize('update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
