@extends('backend.layouts.app')
@section('title', localize('edit_employee'))
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css">
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/tagsinput/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ module_asset('HumanResource/css/employee.css') }}">
@endpush
@section('content')

    @include('humanresource::employee_header')

    <div class="card mb-3 fixed-tab-body">
        @include('backend.layouts.common.validation')
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('employee_update') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('read_employee')
                            <a href="{{ route('employees.index') }}" class="btn btn-success btn-sm"><i
                                    class="fa fa-plus-circle"></i> {{ localize('employee_list') }}</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-12 text-center">
                    <form action="{{ route('employees.update', $employee->uuid) }}" method="POST" class="f1"
                        enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        <input type="hidden" name="basic_setup_rule_id" value="{{ @$basic_setup_rule->id }}">
                        <input type="hidden" id="sub_departments" value="{{ json_encode($sub_departments) }}">
                        <h3 class="mb-1 fw-semi-bold">{{ localize('edit_employee_information') }}</h3>
                        <div class="f1-steps">
                            <div class="f1-progress">
                                <div class="f1-progress-line" data-now-value="20" data-number-of-steps="6"
                                    style="width: 20%;"></div>
                            </div>
                            <div class="f1-step active">
                                <div class="f1-step-icon"><i class="fa fa-user"></i></div>
                                <p>{{ localize('basic_info') }}</p>
                            </div>
                            <div class="f1-step">
                                <div class="f1-step-icon"><i class="fa fa-key"></i></div>
                                <p>{{ localize('salary_and_bank_info') }}</p>
                            </div>

                            <div class="f1-step">
                                <div class="f1-step-icon"><i class="fa fa-info-circle "></i></div>
                                <p>{{ localize('personal_information') }}</p>
                            </div>
                            <div class="f1-step">
                                <div class="f1-step-icon"><i class="fa fa-address-card"></i></div>
                                <p>{{ localize('biological_info_contact') }}</p>
                            </div>
                            <div class="f1-step">
                                <div class="f1-step-icon"><i class="fa fa-users"></i></div>
                                <p>{{ localize('others') }}</p>
                            </div>
                            <div class="f1-step">
                                <div class="f1-step-icon"><i class="fa fa-info"></i></div>
                                <p>{{ localize('supervisor') }}</p>
                            </div>
                        </div>
                        <fieldset>
                            <h5 class="mb-3 fw-semi-bold">{{ localize('basic_info') }}:</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    @input(['input_name' => 'first_name', 'additional_class' => 'required-field', 'value' => $employee->first_name])
                                    @input(['input_name' => 'middle_name', 'value' => $employee->middle_name])
                                    @input(['input_name' => 'last_name', 'additional_class' => 'required-field', 'value' => $employee->last_name])
                                    @input(['input_name' => 'email', 'type' => 'email', 'additional_class' => 'required-field', 'value' => $employee->email])
                                    @input(['input_name' => 'phone', 'additional_class' => 'required-field', 'value' => $employee->phone])
                                    <div class="cust_border form-group mb-3 mx-0 pb-3 row">
                                        <label for="country"
                                            class="col-sm-3 col-form-label ps-0">{{ localize('country') }}</label>
                                        <div class="col-lg-9">
                                            <select name="state_id" class="form-select">
                                                <option value="">{{ localize('select_country') }}</option>
                                                @foreach ($countries as $key => $country)
                                                    <option value="{{ $country->id }}"
                                                        {{ $country->id == $employee->state_id ? 'selected' : '' }}>
                                                        {{ $country->country_name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('state_id'))
                                                <div class="error text-danger text-start">{{ $errors->first('state_id') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @input(['input_name' => 'city', 'required' => false, 'value' => $employee->city])
                                </div>
                                <div class="col-md-6">
                                    @input(['input_name' => 'zip', 'required' => false, 'value' => $employee->zip])
                                    @input(['input_name' => 'alternate_phone', 'required' => false, 'value' => $employee->alternate_phone])
                                    @input(['input_name' => 'national_id_no', 'required' => false, 'value' => $employee->national_id_no])
                                    @input(['input_name' => 'iqama_no', 'required' => false, 'value' => $employee->iqama_no])
                                    @input(['input_name' => 'passport_no', 'required' => false, 'value' => $employee->passport_no])
                                    @input(['input_name' => 'driving_license_no', 'required' => false, 'value' => $employee->driving_license_no])
                                    <div class="cust_border form-group mb-3 mx-0 pb-3 row">
                                        <label for="attendance_time_id"
                                            class="col-sm-3 col-form-label ps-0">{{ localize('attendance_time') }}
                                            <span class="text-danger">*</span></label>
                                        <div class="col-lg-9">
                                            <select name="attendance_time_id" class="form-select required-field" required>
                                                <option value="">{{ localize('select_attendance_time') }}</option>
                                                @foreach ($times as $key => $time)
                                                    <option value="{{ $time->id }}"
                                                        {{ $time->id == $employee->attendance_time_id ? 'selected' : '' }}>
                                                        {{ $time->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('attendance_time_id'))
                                                <div class="error text-danger text-start">
                                                    {{ $errors->first('attendance_time_id') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="f1-buttons">
                                <button type="button" class="btn btn-success btn-next">{{ localize('next') }}</button>
                            </div>
                        </fieldset>
                        <fieldset>
                            <h5 class="mb-3 fw-semi-bold">{{ localize('bank_info') }}:</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    @input(['input_name' => 'account_number', 'required' => false, 'value' => $bank_info?->acc_number])
                                    @input(['input_name' => 'bank_name', 'required' => false, 'value' => $bank_info?->bank_name])
                                    @input(['input_name' => 'tin_no', 'required' => false, 'value' => $employee_file?->tin_no])
                                    <small
                                        class="text-danger offset-3 p-3">{{ localize('note_:_related_to_state_tax') }}</small>
                                </div>
                                <div class="col-md-6">
                                    @input(['input_name' => 'branch_address', 'required' => false, 'value' => $bank_info?->branch_address])
                                    @input(['input_name' => 'bban_num', 'required' => false, 'value' => $bank_info?->bban_num])
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    @input(['input_name' => 'basic_salary', 'type' => 'number', 'required' => true, 'additional_class' => 'required-field basic_salary calculate-salary', 'value' => $employee_file?->basic])
                                    @input(['input_name' => 'transport_allowance', 'type' => 'number', 'required' => true, 'additional_class' => 'required-field transport_allowance calculate-salary', 'value' => $employee_file?->transport])
                                </div>
                                <div class="col-md-6 mb-2">
                                    @input(['input_name' => 'gross_salary', 'type' => 'number', 'required' => true, 'additional_class' => 'required-field gross_salary', 'value' => $employee_file?->gross_salary])
                                </div>
                            </div>
                            <h5 class="mb-3 fw-semi-bold">{{ localize('benefit') }}:</h5>
                            <div class="row mb-2">
                                <div class="col-md-6 mb-2">
                                    @input(['input_name' => 'medical_benefit', 'type' => 'number', 'required' => false, 'value' => $employee_file?->medical_benefit])
                                    @input(['input_name' => 'transportation_benefit', 'type' => 'number', 'required' => false, 'value' => $employee_file?->transportation_benefit])
                                </div>
                                <div class="col-md-6 mb-2">
                                    @input(['input_name' => 'family_benefit', 'type' => 'number', 'required' => false, 'value' => $employee_file?->family_benefit])
                                    @input(['input_name' => 'other_benefit', 'type' => 'number', 'required' => false, 'value' => $employee_file?->other_benefit])
                                </div>
                            </div>
                            <div class="f1-buttons">
                                <button type="button" class="btn btn-previous">{{ localize('previous') }}</button>
                                <button type="button" class="btn btn-success btn-next">{{ localize('next') }}</button>
                            </div>
                        </fieldset>

                        <fieldset>
                            <h5 class="mb-3 fw-semi-bold">{{ localize('personal_information') }}:</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="cust_border form-group mb-3 mx-0 pb-3 row">
                                        <label for="department_id"
                                            class="col-sm-3 col-form-label ps-0">{{ localize('department') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-9">
                                            <select name="department_id" class="form-select required-field"
                                                id="department">
                                                <option value="">{{ localize('select_department') }}</option>
                                                @foreach ($departments as $key => $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ $department->id == $employee->department_id ? 'selected' : '' }}>
                                                        {{ $department->department_name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('department_id'))
                                                <div class="error text-danger text-start">
                                                    {{ $errors->first('department_id') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="cust_border form-group mb-3 mx-0 pb-3 row">
                                        <label for="sub_department_id"
                                            class="col-sm-3 col-form-label ps-0">{{ localize('sub_department') }}
                                        </label>
                                        <div class="col-lg-9">
                                            <select name="sub_department_id" class="form-select" id="sub_department">
                                                <option value="">{{ localize('select_sub_department') }}</option>
                                                @isset($employee->sub_department_id)
                                                    <option value="{{ $employee->sub_department->id }}" selected>
                                                        {{ $employee->sub_department->department_name }}
                                                    </option>
                                                @endisset
                                            </select>
                                            @if ($errors->has('sub_department_id'))
                                                <div class="error text-danger text-start">
                                                    {{ $errors->first('sub_department_id') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="cust_border form-group mb-3 mx-0 pb-3 row">
                                        <label for="position"
                                            class="col-sm-3 col-form-label ps-0">{{ localize('position') }}</label>
                                        <div class="col-lg-9">
                                            <select name="position_id" class="form-select required-field">
                                                <option value="">{{ localize('select_position') }}</option>
                                                @foreach ($positions as $key => $position)
                                                    <option value="{{ $position->id }}"
                                                        {{ $position->id == $employee->position_id ? 'selected' : '' }}>
                                                        {{ $position->position_name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('position_id'))
                                                <div class="error text-danger text-start">
                                                    {{ $errors->first('position_id') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="cust_border form-group mb-3 mx-0 pb-3 row">
                                        <label for="duty_type"
                                            class="col-sm-3 col-form-label ps-0">{{ localize('duty_type') }}</label>
                                        <div class="col-lg-9">
                                            <select name="duty_type_id" id="duty_type" class="form-select">
                                                <option value="">{{ localize('select_duty_type') }}</option>
                                                @foreach ($duty_types as $key => $duty_type)
                                                    <option value="{{ $duty_type->id }}"
                                                        {{ $duty_type->id == $employee->duty_type_id ? 'selected' : '' }}>
                                                        {{ $duty_type->type_name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('duty_type_id'))
                                                <div class="error text-danger text-start">
                                                    {{ $errors->first('duty_type_id') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    @input(['input_name' => 'contract_start_date', 'type' => 'date', 'additional_class' => 'contractual', 'value' => $employee->contract_start_date])
                                    @input(['input_name' => 'contract_end_date', 'type' => 'date', 'additional_class' => 'contractual', 'value' => $employee->contract_end_date])

                                    @input(['input_name' => 'joining_date', 'type' => 'date', 'additional_class' => 'required-field', 'value' => $employee->joining_date])

                                    @input(['input_name' => 'hire_date', 'type' => 'date', 'additional_class' => 'required-field', 'value' => $employee->hire_date])
                                    @input(['input_name' => 'rehire_date', 'type' => 'date', 'required' => false, 'value' => $employee->rehire_date])
                                    @input(['input_name' => 'termination_date', 'type' => 'date', 'required' => false, 'value' => $employee->termination_date])
                                    @input(['input_name' => 'card_no', 'required' => 'false', 'value' => $employee->card_no])
                                    @input(['input_name' => 'monthly_work_hours', 'type' => 'number', 'required' => true, 'additional_class' => 'required-field', 'value' => $employee->monthly_work_hours])
                                    @radio(['input_name' => 'work_permit', 'data_set' => [1 => 'Yes', 0 => 'No'], 'required' => false, 'value' => $employee->work_permit])
                                </div>
                                <div class="col-md-6">
                                    <div class="cust_border form-group mb-3 mx-0 pb-3 row">
                                        <label for="pay_frequency_id"
                                            class="col-sm-3 col-form-label ps-0">{{ localize('pay_frequency') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-9">
                                            <select name="pay_frequency_id" class="form-select required-field">
                                                <option value="">{{ localize('select_pay_frequency') }}</option>
                                                @foreach ($pay_frequencies as $key => $pay_frequency)
                                                    <option value="{{ $pay_frequency->id }}"
                                                        {{ $pay_frequency->id == $employee->pay_frequency_id ? 'selected' : '' }}>
                                                        {{ $pay_frequency->frequency_name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('pay_frequency_id'))
                                                <div class="error text-danger text-start">
                                                    {{ $errors->first('pay_frequency_id') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    @input(['input_name' => 'pay_frequency_text', 'value' => $employee->pay_frequency_text])

                                    @input(['input_name' => 'hourly_rate', 'type' => 'number', 'required' => false, 'value' => $employee->hourly_rate])
                                    @input(['input_name' => 'hourly_rate2', 'type' => 'number', 'required' => false, 'value' => $employee->hourly_rate2])
                                    @input(['input_name' => 'employee_grade', 'value' => $employee->employee_grade, 'required' => false])
                                    @input(['input_name' => 'work_in_city', 'value' => $employee->work_in_city, 'required' => false])

                                    <div class="cust_border form-group mb-3 mx-0 pb-3 row">
                                        <label for="employee_type_id"
                                            class="col-sm-3 col-form-label ps-0">{{ localize('employee_type') }}</label>
                                        <div class="col-lg-9">
                                            <select name="employee_type_id" class="form-select">
                                                <option value="">{{ localize('select_employee_type') }}</option>
                                                @foreach ($employee_types as $key => $employee_type)
                                                    <option value="{{ $employee_type->id }}"
                                                        {{ $employee_type->id == $employee->employee_type_id ? 'selected' : '' }}>
                                                        {{ $employee_type->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('employee_type_id'))
                                                <div class="error text-danger text-start">
                                                    {{ $errors->first('employee_type_id') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="f1-buttons">
                                <button type="button" class="btn btn-previous">{{ localize('previous') }}</button>
                                <button type="button" class="btn btn-success btn-next">{{ localize('next') }}</button>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>{{ localize('biological_info') }}</h5>
                                    @input(['input_name' => 'date_of_birth', 'type' => 'date', 'additional_class' => 'required-field', 'value' => $employee->date_of_birth])
                                    <div class="cust_border form-group mb-3 mx-0 pb-3 row">
                                        <label for="gender"
                                            class="col-sm-3 col-form-label ps-0">{{ localize('gender') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-9">
                                            <select name="gender_id" class="form-select required-field" required>
                                                <option value="">{{ localize('select_gender') }}</option>
                                                @foreach ($genders as $key => $gender)
                                                    <option value="{{ $gender->id }}"
                                                        {{ $gender->id == $employee->gender_id ? 'selected' : '' }}>
                                                        {{ $gender->gender_name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('gender_id'))
                                                <div class="error text-danger text-start">
                                                    {{ $errors->first('gender_id') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="cust_border form-group mb-3 mx-0 pb-3 row">
                                        <label for="marital_status_id"
                                            class="col-sm-3 col-form-label ps-0">{{ localize('marital_status') }}</label>
                                        <div class="col-lg-9">
                                            <select name="marital_status_id" class="form-select">
                                                @foreach ($marital_statuses as $key => $status)
                                                    <option value="{{ $status->id }}"
                                                        {{ $status->id == $employee->marital_status_id ? 'selected' : '' }}>
                                                        {{ $status->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('marital_status_id'))
                                                <div class="error text-danger text-start">
                                                    {{ $errors->first('marital_status_id') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    @input(['input_name' => 'no_of_kids', 'type' => 'number', 'required' => false, 'value' => $employee->no_of_kids])
                                    @input(['input_name' => 'sos', 'required' => false, 'value' => $employee->sos])
                                    <small
                                        class="text-danger offset-3 p-3">{{ localize('note_:_emergency_message_number') }}</small>
                                    @input(['input_name' => 'religion', 'required' => false, 'value' => $employee->religion])
                                    @input(['input_name' => 'ethnic_group', 'required' => false, 'value' => $employee->ethnic_group])
                                    @input(['input_name' => 'profile_image', 'type' => 'file', 'accept' => 'image/*', 'tooltip' => 'Attached Passport Size photo', 'required' => false])
                                    @if ($employee->profile_img_location)
                                        <div class="col-md-4">
                                            <img width="70"
                                                src="{{ asset('storage/' . $employee->profile_img_location) }}">
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h5>{{ localize('emergency_contact') }}</h5>
                                    @input(['input_name' => 'emergency_contact_person', 'required' => false, 'value' => $employee->emergency_contact_person])
                                    @input(['input_name' => 'emergency_contact_relationship', 'required' => false, 'value' => $employee->emergency_contact_relationship])
                                    @input(['input_name' => 'emergency_contact', 'type' => 'number', 'required' => false, 'value' => $employee->emergency_contact])
                                    @input(['input_name' => 'emergency_home_phone', 'type' => 'number', 'required' => false, 'value' => $employee->emergency_home_phone])
                                    @input(['input_name' => 'emergency_work_phone', 'type' => 'number', 'required' => false, 'value' => $employee->emergency_work_phone])
                                    @input(['input_name' => 'alter_emergency_contact', 'type' => 'number', 'required' => false, 'value' => $employee->alter_emergency_contact])
                                    @input(['input_name' => 'alter_emergency_home_phone', 'type' => 'number', 'required' => false, 'value' => $employee->alter_emergency_home_phone])
                                    @input(['input_name' => 'alter_emergency_work_phone', 'type' => 'number', 'required' => false, 'value' => $employee->alter_emergency_work_phone])

                                </div>
                            </div>
                            <div class="f1-buttons">
                                <button type="button" class="btn btn-previous">{{ localize('previous') }}</button>
                                <button type="button" class="btn btn-success btn-next">{{ localize('next') }}</button>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>{{ localize('medical_info') }}</h5>
                                    <div class="row">
                                        @input(['input_name' => 'blood_group', 'required' => false, 'value' => $employee->blood_group])
                                        @input(['input_name' => 'health_condition', 'required' => false, 'value' => $employee->health_condition])
                                        @radio(['input_name' => 'is_disable', 'data_set' => [1 => 'Yes', 0 => 'NO'], 'required' => 'false', 'additional_id' => 'is_disable', 'value' => $employee->is_disable])
                                        @input(['input_name' => 'disabilities_desc', 'value' => $employee->disabilities_desc, 'required' => false, 'additional_class' => 'disabilities_desc'])
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5>{{ localize('others') }}</h5>
                                    @input(['input_name' => 'home_email', 'type' => 'email', 'required' => false, 'value' => $employee->home_email])
                                    @input(['input_name' => 'home_phone', 'type' => 'number', 'required' => false, 'value' => $employee->home_phone])
                                    @input(['input_name' => 'cell_phone', 'type' => 'number', 'required' => false, 'value' => $employee->cell_phone])
                                </div>
                            </div>

                            <div id="employee-docs">
                                @foreach ($employee->employee_docs as $key => $doc)
                                    <div class="row employee_docs">
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label class="mb-2" for="doc-title{{ $key }}"><span
                                                        class="text-danger">*</span>{{ localize('doc_title') }}</label>
                                                <input type="text" class="form-control"
                                                    id="doc-title{{ $key }}"
                                                    placeholder="{{ localize('doc_title') }}"
                                                    name="employee_docs[{{ $key + 1 }}][document_title]" required
                                                    value="{{ $doc->doc_title }}">
                                                <input type="hidden" name="employee_docs[{{ $key + 1 }}][doc_id]"
                                                    value="{{ $doc->id }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="mb-2" for="doc-file{{ $key }}"><span
                                                    class="text-danger">*</span>{{ localize('file') }}</label>
                                            <input type="file" class="form-control" id="doc-file{{ $key }}"
                                                name="employee_docs[{{ $key + 1 }}][file]">
                                            <a href="{{ url('/public/storage/' . $doc->file_path) }}"
                                                target="_blank">{{ $doc->file_name }}</a>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label class="mb-2" for="expiry_date{{ $key }}"><span
                                                        class="text-danger">*</span>{{ localize('expiry_date') }}</label>
                                                <input type="date" class="form-control"
                                                    id="expiry_date{{ $key }}"
                                                    placeholder="{{ localize('expiry_date') }}"
                                                    name="employee_docs[{{ $key + 1 }}][expiry_date]"
                                                    value="{{ $doc->expiry_date }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row text-start mb-3">
                                <div class="col-lg-3">
                                    <button id="add_doc_row" class="btn btn-soft-me btn-primary"><i
                                            class="fa fa-plus"></i> {{ localize('add_more') }}</button>
                                </div>
                            </div>

                            <div class="f1-buttons">
                                <button type="button" class="btn btn-previous">{{ localize('previous') }}</button>
                                <button type="button" class="btn btn-success btn-next">{{ localize('next') }}</button>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="my-2">{{ localize('supervisor_setup') }}</h5>
                                    @radio(['input_name' => 'is_supervisor', 'data_set' => [1 => 'Yes', 0 => 'No'], 'required' => true, 'value' => $employee->is_supervisor])
                                </div>
                            </div>
                            <div class="f1-buttons">
                                <button type="button" class="btn btn-previous">{{ localize('previous') }}</button>
                                <button type="submit" class="btn btn-success">{{ localize('update') }}</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('backend/assets/plugins/bootstrap-wizard/form.scripts.js') }}"></script>
    <script src="{{ module_asset('HumanResource/js/salary.js') }}"></script>
    <script src="{{ module_asset('HumanResource/js/employee_form_wiz.js') }}"></script>
    <script src="{{ module_asset('HumanResource/js/employee-edit.js') }}"></script>
@endpush
