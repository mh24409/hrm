@method('PUT')
<div class="row ps-4 pe-4">
    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="client_name"
                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('client_name') }}<span
                    class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="text" class="form-control" id="client_name" name="client_name"
                    placeholder="{{ localize('client_name') }}" value="{{ old('client_name') ?? $client->client_name }}"
                    required>
            </div>

            @if ($errors->has('client_name'))
                <div class="error text-danger m-2">{{ $errors->first('client_name') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="country"
                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('country') }}<span
                    class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">

                <select name="country" id="country_id" class="form-control select-basic-single" required>
                    <option value="">{{ localize('country') }}</option>
                    @foreach ($countries as $country)

                        <option value="{{ $country->id }}" {{ $country->id == $client->country ? 'selected' : '' }}>{{ $country->country_name }}
                        </option>

                    @endforeach
                </select>

            </div>

            @if ($errors->has('country'))
                <div class="error text-danger m-2">{{ $errors->first('country') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="email"
                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('email') }}<span
                    class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="text" class="form-control" id="email" name="email"
                    placeholder="{{ localize('email') }}" value="{{ old('email') ?? $client->email }}"
                    required>
            </div>

            @if ($errors->has('email'))
                <div class="error text-danger m-2">{{ $errors->first('email') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="company_name"
                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('company_name') }}<span
                    class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">
                <input type="text" class="form-control" id="company_name" name="company_name"
                    placeholder="{{ localize('company_name') }}" value="{{ old('company_name') ?? $client->company_name }}"
                    required>
            </div>

            @if ($errors->has('company_name'))
                <div class="error text-danger m-2">{{ $errors->first('company_name') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12 mt-3">
        <div class="row">
            <label for="address"
                class="col-form-label col-sm-3 col-md-12 col-xl-3 fw-semibold">{{ localize('address') }}<span
                    class="text-danger">*</span></label>
            <div class="col-sm-9 col-md-12 col-xl-9">

                <textarea  class="form-control" id="address" name="address"
                    placeholder="{{ localize('address') }}" rows ="5" required>{{ $client->address }}</textarea>
            </div>

            @if ($errors->has('address'))
                <div class="error text-danger m-2">{{ $errors->first('address') }}</div>
            @endif
        </div>
    </div>
</div>