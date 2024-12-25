<div class="row ps-4 pe-4">
    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="name" class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('vendor_name') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="text" name="name" id="name" class="form-control" placeholder="{{ localize('vendor_name') }}"
                    value="{{ old('name') }}" required>
            </div>
            @if ($errors->has('name'))
                <div class="error text-danger m-2">{{ $errors->first('name') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="mobile" class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('mobile_number') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="text" name="mobile" id="mobile" class="form-control" placeholder="{{ localize('mobile_number') }}"
                    value="{{ old('mobile') }}" required>
            </div>
            @if ($errors->has('mobile'))
                <div class="error text-danger m-2">{{ $errors->first('mobile') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="email" class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('email_address') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="email" name="email" id="email" class="form-control" placeholder="{{ localize('email_address') }}"
                    value="{{ old('email') }}" required>
            </div>
            @if ($errors->has('email'))
                <div class="error text-danger m-2">{{ $errors->first('email') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="address" class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('address') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <textarea name="address" id="address" rows="2" class="form-control" placeholder="{{ localize('address') }}" required></textarea>
            </div>
            @if ($errors->has('address'))
                <div class="error text-danger m-2">{{ $errors->first('address') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="country_id" class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('country') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <select name="country_id" id="country_id" class="form-select select-basic-single" required>
                    <option value="">{{ localize('select_country') }}</option>
                    @foreach ($countries as $key => $country)
                        <option value="{{ $country->id }}"
                            {{ old('country_id') == $country->id ? 'selected' : '' }}>
                            {{ $country->country_name }}</option>
                    @endforeach
                </select>
                @if ($errors->has('country_id'))
                    <div class="error text-danger text-start">{{ $errors->first('country_id') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="city" class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('city') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="text" name="city" id="city" class="form-control" placeholder="{{ localize('city') }}"
                    value="{{ old('city') }}" required>
            </div>
            @if ($errors->has('city'))
                <div class="error text-danger m-2">{{ $errors->first('city') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="zip" class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('zip_code') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="number" name="zip" id="zip" min="0" class="form-control" placeholder="{{ localize('zip_code') }}"
                    value="{{ old('zip') }}" required>
            </div>
            @if ($errors->has('zip'))
                <div class="error text-danger m-2">{{ $errors->first('zip') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="previous_balance" class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('previous_balance') }}
                <span class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="number" name="previous_balance" id="previous_balance" step="any" class="form-control" placeholder="{{ localize('previous_balance') }}"
                    value="{{ old('previous_balance') }}" required>
            </div>
            @if ($errors->has('previous_balance'))
                <div class="error text-danger m-2">{{ $errors->first('previous_balance') }}</div>
            @endif
        </div>
    </div>
    
</div>