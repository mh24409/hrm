@method('PATCH')
<div class="row ps-4 pe-4">
    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="name" class="col-form-label col-sm-3 col-md-12 col-xl-2 fw-semibold">{{ localize('name') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-10">
                <input type="text" name="name" id="name" class="form-control" placeholder="{{ localize('name') }}"
                    value="{{ $committee->name }}" required>
            </div>
            @if ($errors->has('name'))
                <div class="error text-danger m-2">{{ $errors->first('name') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="signature" class="col-form-label col-sm-3 col-md-12 col-xl-2 fw-semibold">{{ localize('signature') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-10">
                <input type="file" name="signature" id="signature" class="form-control" aria-describedby="signatureNote" accept="image/*" autocomplete="off" @if(!$committee->signature) required @endif >
                <small id="signatureNote" class="form-text text-black">{{ localize('N.B:_image_width_should_be_300px_and_height_120px') }}</small>

                <small id="fileHelp" class="text-muted mt-2"><img src="{{ $committee->signature ? asset('storage/' . $committee->signature) : asset('backend/assets/dist/img/signature_signature.jpg') }}" id="output" class="img-thumbnail mt-2" width="300" style="height: 120px !important;">
                </small>
            </div>
        </div>
    </div>
</div>