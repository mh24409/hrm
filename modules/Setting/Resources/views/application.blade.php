@extends('setting::settings')
@section('title', localize('application'))
@push('css')
@endpush

@section('setting_content')

    <!--/.Content Header (Page header)-->
    <div class="body-content pt-0">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 fw-semi-bold mb-0">{{ __('Edit Application') }}</h6>
                    </div>
                </div>
            </div>

            <form action="{{ route('applications.update', $app->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 col-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="title" class="col-form-label">{{ localize('title') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            value="{{ $app->title }}" required>

                                        @if ($errors->has('title'))
                                            <div class="error text-danger">
                                                {{ $errors->first('title') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="phone" class="col-form-label">{{ localize('phone') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            value="{{ $app->phone }}" required>

                                        @if ($errors->has('phone'))
                                            <div class="error text-danger">
                                                {{ $errors->first('phone') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="address" class="col-form-label">{{ localize('address') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="address" name="address"
                                            value="{{ $app->address }}" required>
                                        @if ($errors->has('address'))
                                            <div class="error text-danger">
                                                {{ $errors->first('address') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="website" class="col-form-label">{{ localize('website') }}</label>
                                        <input type="text" class="form-control" id="website" name="website"
                                            value="{{ $app->website }}">
                                        @if ($errors->has('website'))
                                            <div class="error text-danger">
                                                {{ $errors->first('website') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group mb-2">
                                        <label for="currency" class="col-form-label">{{ localize('currency') }}<span
                                                class="text-danger">*</span></label>
                                        <select name="currency_id" id="currency_id" class="select-basic-single" required>
                                            <option value="" selected disabled>{{ localize('currency') }}</option>
                                            @foreach ($currencies as $currency)
                                                <option value="{{ $currency->id }}"
                                                    {{ $app->currency_id == $currency->id ? 'selected' : '' }}>
                                                    {{ $currency->title }}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('currency_id'))
                                            <div class="error text-danger">
                                                {{ $errors->first('currency_id') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="floating_number"
                                            class="col-form-label">{{ localize('floating_number') }}<span
                                                class="text-danger">*</span></label>
                                        <select name="floating_number" id="floating_number" class="select-basic-single"
                                            required>
                                            <option value="" selected disabled>
                                                {{ localize('select_floating_number') }}</option>
                                            <option value="1" {{ $app->floating_number == 1 ? 'selected' : '' }}> 0
                                            </option>
                                            <option value="2" {{ $app->floating_number == 2 ? 'selected' : '' }}> 0.0
                                            </option>
                                            <option value="3" {{ $app->floating_number == 3 ? 'selected' : '' }}> 0.00
                                            </option>
                                            <option value="4" {{ $app->floating_number == 4 ? 'selected' : '' }}>
                                                0.000 </option>

                                        </select>

                                        @if ($errors->has('floating_number'))
                                            <div class="error text-danger">
                                                {{ $errors->first('floating_number') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group mb-2">
                                        <label for="state_income_tax"
                                            class="col-form-label">{{ localize('state_income_tax') }}</label>
                                        <input type="number" class="form-control" id="state_income_tax"
                                            name="state_income_tax" value="{{ $app->state_income_tax }}">
                                        @if ($errors->has('state_income_tax'))
                                            <div class="error text-danger">
                                                {{ $errors->first('state_income_tax') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group mb-2">
                                        <label for="soc_sec_npf_tax"
                                            class="col-form-label">{{ localize('soc_sec_npf(%)') }}</label>
                                        <input type="text" class="form-control" id="soc_sec_npf_tax"
                                            name="soc_sec_npf_tax" value="{{ $app->soc_sec_npf_tax }}">
                                        @if ($errors->has('soc_sec_npf_tax'))
                                            <div class="error text-danger">
                                                {{ $errors->first('soc_sec_npf_tax') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group mb-2">
                                        <label for=""
                                            class="form-check-label text-start ps-0 pb-1">{{ localize('fixed_date') }}</label>
                                        <div class="toggle-example">
                                            <input type="checkbox" id="fixed_date"
                                                {{ $app != null && $app->fixed_date == true ? 'checked' : '' }}
                                                data-bs-toggle="toggle" data-on="Enable" data-off="Disable"
                                                data-onstyle="success" data-offstyle="danger">
                                        </div>
                                    </div>

                                    <div class="form-group mb-2 fixed_date_show">
                                        <span>Please fixed your sale date</span>
                                        <input type="text" class="form-control date_picker" name="fixed_date" required
                                            placeholder=" {{ localize('select_date') }}" value="{{ $app->fixed_date }}">

                                        @if ($errors->has('fixed_date'))
                                            <div class="error text-danger">
                                                {{ $errors->first('fixed_date') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-2">
                                        <label for="email" class="col-form-label">{{ localize('email_address') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ $app->email }}" required>

                                        @if ($errors->has('email'))
                                            <div class="error text-danger">
                                                {{ $errors->first('email') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="tax_no" class="col-form-label">{{ localize('tax_no') }}</label>
                                        <input type="text" class="form-control" id="tax_no" name="tax_no"
                                            value="{{ $app->tax_no }}">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="footer_text"
                                            class="col-form-label">{{ localize('footer_text') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="footer_text" required
                                            value="{{ $app->footer_text }}">

                                        @if ($errors->has('footer_text'))
                                            <div class="error text-danger">
                                                {{ $errors->first('footer_text') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group mb-2">
                                        <label for="negative_amount_symbol"
                                            class="col-form-label">{{ localize('negative_amount_symbol') }}<span
                                                class="text-danger">*</span></label>
                                        <select name="negative_amount_symbol" id="negative_amount_symbol"
                                            class="select-basic-single" required>
                                            <option value="" selected disabled>
                                                {{ localize('select_negative_amount_symbol') }}</option>
                                            <option value="1"
                                                {{ $app->negative_amount_symbol == 1 ? 'selected' : '' }}> - </option>
                                            <option value="2"
                                                {{ $app->negative_amount_symbol == 2 ? 'selected' : '' }}> ( ) </option>

                                        </select>

                                        @if ($errors->has('negative_amount_symbol'))
                                            <div class="error text-danger">
                                                {{ $errors->first('negative_amount_symbol') }}</div>
                                        @endif
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="" class="col-form-label">{{ localize('direction') }}</label>
                                        <select name="rtl_ltr" id="rtl_ltr" class="select-basic-single" required>
                                            <option value="" selected disabled>{{ localize('select_one') }}
                                            </option>
                                            <option value="1" {{ $app->rtl_ltr == 1 ? 'selected' : '' }}> LTR
                                            </option>
                                            <option value="2" {{ $app->rtl_ltr == 2 ? 'selected' : '' }}> RTL
                                            </option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label for="employer_contribution"
                                            class="col-form-label">{{ localize('employer_contribution(%)') }}</label>
                                        <input type="text" class="form-control" id="employer_contribution"
                                            name="employer_contribution" value="{{ $app->employer_contribution }}">
                                        @if ($errors->has('employer_contribution'))
                                            <div class="error text-danger">
                                                {{ $errors->first('employer_contribution') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-group mb-2">
                                        <label for="icf_amount"
                                            class="col-form-label">{{ localize('icf_amount') }}</label>
                                        <input type="text" class="form-control" id="icf_amount" name="icf_amount"
                                            value="{{ $app->icf_amount }}">
                                        @if ($errors->has('icf_amount'))
                                            <div class="error text-danger">
                                                {{ $errors->first('icf_amount') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group mb-2">
                                <label for="" class="col-form-label fw-semibold">{{ localize('logo') }}</label>
                                <input type="button" id="removeLogo" value="x" class="remove-icon d-none" />
                                <img id="logo_preview" src="{{ app_setting()->logo }}"
                                    data-default_src="{{ app_setting()->logo }}" class="img-thumbnail"
                                    alt="Logo-Preview" />

                                <div class="input-group">
                                    <input type="file" class="form-control" name="logo" id="logo">
                                    <label class="input-group-text" for="logo">{{ localize('upload') }}</label>

                                    @if ($errors->has('image'))
                                        <div class="error text-danger">
                                            {{ $errors->first('image') }}</div>
                                    @endif
                                </div>
                                <span>Recommended pixel (135 X 45)</span>
                            </div>

                            <div class="form-group mb-2">
                                <label for=""
                                    class="col-form-label fw-semibold">{{ localize('sidebar_logo') }}</label>

                                <input type="button" id="sidebarLogo" value="x" class="remove-icon d-none" />
                                <img id="sidebar_logo_preview" src="{{ app_setting()->sidebar_logo }}"
                                    data-default_src="{{ app_setting()->sidebar_logo }}" class="img-thumbnail"
                                    alt="Sidebar-Logo-Preview" />

                                <div class="input-group">
                                    <input type="file" class="form-control" name="sidebar_logo" id="sidebar_logo">
                                    <label class="input-group-text" for="sidebar_logo">{{ localize('upload') }}</label>
                                </div>
                                <span>Recommended pixel (150 X 45)</span>
                            </div>

                            <div class="form-group mb-2">
                                <label for=""
                                    class="col-form-label fw-semibold">{{ localize('sidebar_collapsed_logo') }}</label>

                                <input type="button" id="sidebarCollapsedLogo" value="x"
                                    class="remove-icon d-none" />
                                <img id="sidebar_collapsed_logo_preview"
                                    src="{{ app_setting()->sidebar_collapsed_logo }}"
                                    data-default_src="{{ app_setting()->sidebar_collapsed_logo }}" class="img-thumbnail"
                                    alt="Sidebar-Collapsed-Logo-Preview" />

                                <div class="input-group">
                                    <input type="file" class="form-control" name="sidebar_collapsed_logo"
                                        id="sidebar_collapsed_logo">
                                    <label class="input-group-text"
                                        for="sidebar_collapsed_logo">{{ localize('upload') }}</label>
                                </div>
                                <span>Recommended pixel (90px X 90px)</span>
                            </div>

                            <div class="form-group mb-2">
                                <label for=""
                                    class="col-form-label fw-semibold">{{ localize('login_image') }}</label>

                                <input type="button" id="loginImage" value="x" class="remove-icon d-none" />
                                <img id="login_image_preview" src="{{ app_setting()->login_image }}"
                                    data-default_src="{{ app_setting()->login_image }}" class="img-thumbnail"
                                    alt="Login-Image-Preview" />

                                <div class="input-group">
                                    <input type="file" class="form-control" name="login_image" id="login_image">
                                    <label class="input-group-text" for="login_image">{{ localize('upload') }}</label>
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <label for=""
                                    class="col-form-label fw-semibold">{{ localize('favicon') }}</label>
                                <input type="button" id="removeFavicon" value="x" class="remove-icon d-none" />
                                <img id="favicon_preview" src="{{ app_setting()->favicon }}"
                                    data-default_src="{{ app_setting()->favicon }}" class="img-thumbnail"
                                    alt="Sidebar-Logo-Preview" />

                                <div class="input-group">
                                    <input type="file" class="form-control" name="favicon" id="favicon">
                                    <label class="input-group-text" for="favicon">{{ localize('upload') }}</label>

                                    @if ($errors->has('favicon'))
                                        <div class="error text-danger">
                                            {{ $errors->first('favicon') }}</div>
                                    @endif
                                </div>
                                <span>Recommended pixel (60 X 60)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer form-footer">
                    <button type="submit" class="btn btn-success">{{ localize('submit') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ module_asset('Setting/js/app.js') }}"></script>
@endpush
