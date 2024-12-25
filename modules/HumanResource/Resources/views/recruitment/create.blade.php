@extends('backend.layouts.app')
@section('title', localize('candidate_create'))
@push('css')
@endpush
@section('content')

    <div class="card mb-3 ">
        @include('backend.layouts.common.validation')
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('employee_candidate') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        <a href="{{ route('candidate.index') }}" class="btn btn-success btn-sm"><i
                            class="fa fa-plus-circle"></i>&nbsp; {{ localize('candidate_list') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <form action="{{ route('candidate.store') }}" method="POST" class="f1" enctype="multipart/form-data">
                        @csrf
                        <div class="f1-steps d-flex justify-content-around">
                            <div class="f1-progress">
                                <div class="f1-progress-line" data-now-value="50" data-number-of-steps="3"
                                    style="width: 50%;"></div>
                            </div>
                            <div class="f1-step active">
                                <div class="f1-step-icon"><i class="fa fa-user"></i></div>
                                <p>{{ localize('basic_information') }}</p>
                            </div>
                            <div class="f1-step">
                                <div class="f1-step-icon"><i class="fa fa-graduation-cap"></i></div>
                                <p>{{ localize('educational_information') }}</p>
                            </div>
                            <div class="f1-step">
                                <div class="f1-step-icon"><i class="fa fa-calendar-plus"></i></div>
                                <p>{{ localize('past_experience') }}</p>
                            </div>
                        </div>
                        <fieldset>
                            <h5 class="mb-3 fw-semi-bold">{{ localize('basic_information') }}:</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    @input(['input_name' => 'first_name', 'additional_class' => 'required-field'])
                                    @input(['input_name' => 'last_name', 'required' => false])
                                    @input(['input_name' => 'email', 'type' => 'email', 'required' => false])
                                    @input(['input_name' => 'phone', 'additional_class' => 'required-field'])
                                    @input(['input_name' => 'alternative_phone', 'required' => false])
                                    @input(['input_name' => 'ssn', 'required' => false])
                                    @input(['input_name' => 'present_address', 'required' => false])
                                    @input(['input_name' => 'permanent_address', 'required' => false])
                                    <div class="form-group mx-0 pb-2 row">
                                        <label for="country_id" class="col-sm-3 col-form-label ps-0">{{ localize('country') }}</label>
                                        <div class="col-lg-9">
                                            <select name="country_id" class="form-select select-basic-single">
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
                                    @input(['input_name' => 'city', 'required' => false])
                                    <div class="form-group mb-2 mx-0 row">
                                        <label class="col-lg-3 col-form-label ps-0">{{ localize('zip_code') }}</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="zip" placeholder="{{ localize('zip_code') }}" class="form-control" aria-describedby="emailHelp" autocomplete="off">
                                        </div>
                                    </div>
                                    @input(['input_name' => 'picture', 'type' => 'file', 'tooltip' => localize('Attached Passport Size photo'), 'required' => false])
                                </div>
                            </div>
                            <div class="f1-buttons">
                                <button type="button" class="btn btn-success btn-next">{{ localize('next') }}</button>
                            </div>
                        </fieldset>
                    
                        <fieldset>
                            <h5 class="fw-semi-bold">{{ localize('educational_information') }}:</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row form-group ps-2 pe-4">
                                        <table class="table-responsive" id="educational_information-table">
                                            <tbody>
                                                <tr>
                                                    <td>{{ localize('obtained_degree') }}</td>
                                                    <td>
                                                        <input type="text"
                                                        class="form-control form-number-input mb-2"
                                                        name="degree[]"
                                                        placeholder="{{ localize('obtained_degree') }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ localize('university') }}</td>
                                                    <td>
                                                        <input type="text"
                                                        class="form-control form-number-input mb-2"
                                                        name="university[]"
                                                        placeholder="{{ localize('university') }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ localize('cgpa') }}</td>
                                                    <td>
                                                        <input type="text"
                                                        class="form-control form-number-input mb-2"
                                                        name="cgpa[]"
                                                        placeholder="{{ localize('cgpa') }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="mb-4">{{ localize('comments') }}</td>
                                                    <td>
                                                        <textarea name="comments[]" rows="2" class="form-control form-number-input  mb-4" placeholder="{{ localize('Comments') }}"></textarea>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" id="add_educational_information"
                                        class="btn btn-success btn-sm p-2">{{ localize('add_more') }}</button>
                                </div>
                            </div>
                            <div class="f1-buttons">
                                <button type="button" class="btn btn-previous">{{ localize('previous') }}</button>
                                <button type="button" id="salary-setup"
                                    class="btn btn-success btn-next">{{ localize('next') }}</button>
                            </div>
                        </fieldset>
                        
                        <fieldset>
                            <h5 class="fw-semi-bold">{{ localize('past_experience') }}:</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row form-group ps-2 pe-4">
                                        <table class="table-responsive" id="past_experience-table">
                                            <tbody>
                                                <tr>
                                                    <td>{{ localize('company_name') }}</td>
                                                    <td>
                                                        <input type="text"
                                                        class="form-control form-number-input mb-2"
                                                        name="company_name[]"
                                                        placeholder="{{ localize('company_name') }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ localize('working_period') }}</td>
                                                    <td>
                                                        <input type="text"
                                                        class="form-control form-number-input mb-2"
                                                        name="working_period[]"
                                                        placeholder="{{ localize('working_period') }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>{{ localize('duties') }}</td>
                                                    <td>
                                                        <input type="text"
                                                        class="form-control form-number-input mb-2"
                                                        name="duties[]"
                                                        placeholder="{{ localize('duties') }}">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="mb-4">{{ localize('supervisor') }}</td>
                                                    <td>
                                                        <input type="text"
                                                        class="form-control form-number-input mb-4"
                                                        name="supervisor[]"
                                                        placeholder="{{ localize('supervisor') }}">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" id="add_past_experience"
                                        class="btn btn-success btn-sm p-2">{{ localize('add_more') }}</button>
                                </div>
                            </div>
                            <div class="f1-buttons">
                                <button type="button" class="btn btn-previous">{{ localize('previous') }}</button>
                                <button type="submit" class="btn btn-success btn-submit">{{ localize('submit') }}</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>

            <input type="hidden" value="{{ localize('obtained_degree') }}" id="lang_obtained_degree">
            <input type="hidden" value="{{ localize('university') }}" id="lang_university">
            <input type="hidden" value="{{ localize('cgpa') }}" id="lang_cgpa">
            <input type="hidden" value="{{ localize('comments') }}" id="lang_comments">

            <input type="hidden" value="{{ localize('company_name') }}" id="lang_company_name">
            <input type="hidden" value="{{ localize('working_period') }}" id="lang_working_period">
            <input type="hidden" value="{{ localize('duties') }}" id="lang_duties">
            <input type="hidden" value="{{ localize('supervisor') }}" id="lang_supervisor">

        </div>

    </div>
@endsection
@push('js')
    <script src="{{ asset('backend/assets/plugins/bootstrap-wizard/form.scripts.js') }}"></script>

    <script src="{{ module_asset('HumanResource/js/candidate-create.js') }}"></script>
@endpush
