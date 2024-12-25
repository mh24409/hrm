@extends('backend.layouts.app')
@section('title', $employee->full_name)
@push('css')
    <link rel="stylesheet" href="{{ module_asset('HumanResource/css/employee-show.css') }}">
@endpush
@section('content')
    @include('humanresource::employee_header')
    <div class="row p-4">
        <div class="card p-4">
            <div class="col-sm-12 col-xl-8">
                <div class="media m-1 ">
                    <div class="align-left p-1">
                        <a href="#" class="profile-image">
                            <img src="{{ $employee->profile_img_location ? asset('storage/' . $employee->profile_img_location) : asset('backend/assets/img/avatar-1.jpg') }}"
                                class="avatar avatar-xl rounded-circle img-border height-100"
                                alt="employee profile image for {{ $employee->full_name }}">
                        </a>
                    </div>
                    <div class="media-body mt-1">
                        <h2 class="font-large-1 white">{{ $employee->full_name }}</h2>
                        <h3 class="font-large-2 white">{{ $employee?->position->position_name ?? '---' }}
                            <span>({{ $employee?->sub_department?->department_name ?? '---' }}
                                -
                                {{ $employee?->department?->department_name ?? '---' }})</span>
                        </h3>
                        <p class="white"><i class="fas fa-map-marker-alt"></i>
                            {{ $employee->city }},
                            {{ $employee?->state?->country_name ?? '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row p-3 profile_show">
        <div class="col-lg-6">

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">{{ localize('employee_id') }}</h6>
                        </div>
                        <div class="col-auto">
                            {{ $employee->employee_id }}
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">{{ localize('date_of_birth') }}</h6>
                        </div>
                        <div class="col-auto">
                            <time class="fs-13 fw-semi-bold text-muted"
                                datetime="{{ $employee->date_of_birth }}">{{ $employee->date_of_birth }}</time>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">{{ localize('joinning_date') }}</h6>
                        </div>
                        <div class="col-auto">
                            <time class="fs-13 fw-semi-bold text-muted"
                                datetime="{{ $employee->joining_date }}">{{ $employee->joining_date }}</time>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">{{ localize('email') }}</h6>
                        </div>
                        <div class="col-auto">
                            <a href="mailto:{{ $employee->email }}"><span
                                    class="fs-13 fw-semi-bold text-muted">{{ $employee->email }}</span></a>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">{{ localize('phone_no') }}</h6>
                        </div>
                        <div class="col-auto">
                            <a href="tel:{{ $employee->phone }}"><span
                                    class="fs-13 fw-semi-bold text-muted">{{ $employee->phone }}</span></a>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 fw-semi-bold">{{ localize('card_no') }}</h6>
                        </div>
                        <div class="col-auto">
                            {{ $employee->card_no }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fs-20 fw-semi-bold mb-0">{{ localize('basic_info') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 ">{{ localize('gender') }}</h6>
                        </div>
                        <div class="col-auto">
                            <time
                                class="fs-13 fw-semi-bold text-muted">{{ $employee->gender ? $employee->gender->gender_name : '' }}</time>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 ">{{ localize('marital_status') }}</h6>
                        </div>
                        <div class="col-auto">
                            <time
                                class="fs-13 fw-semi-bold text-muted">{{ $employee->marital_status ? $employee->marital_status->name : '' }}</time>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('no_of_kids') }}</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted">{{ $employee->no_of_kids }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('blood_group') }}</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted">{{ $employee->blood_group }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 ">{{ localize('religion') }}</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted">{{ $employee->religion }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('ethnic_group') }}</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted">{{ $employee->ethnic_group }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('sos') }}</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted">{{ $employee->sos }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('national_id_no') }}</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted">{{ $employee->national_id_no }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('iqama_no') }}</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted">{{ $employee->iqama_no }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('passport_no') }}</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted">{{ $employee->passport_no }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('driving_license_no') }}</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted">{{ $employee->driving_license_no }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fs-20 fw-semi-bold mb-0">{{ localize('personal_information') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('employee_type') }}</h6>
                        </div>
                        <div class="col-auto">
                            <time
                                class="fs-13 fw-semi-bold text-muted">{{ $employee->employee_type ? $employee->employee_type->name : '' }}-{{ $employee->attendance_time
                                    ? $employee->attendance_time->name .
                                        '(' .
                                        $employee->attendance_time->start_time .
                                        ' -
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ' .
                                        $employee->attendance_time->end_time .
                                        ')'
                                    : '' }}</time>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('duty_type') }}</h6>
                        </div>
                        <div class="col-auto">
                            <time
                                class="fs-13 fw-semi-bold text-muted">{{ $employee->duty_type ? $employee->duty_type->type_name : '' }}</time>
                        </div>
                    </div>
                    <hr>

                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0 ">{{ localize('pay_frequency') }}</h6>
                        </div>
                        <div class="col-auto">
                            <span
                                class="fs-13 fw-semi-bold text-muted">{{ $employee->pay_frequency ? $employee->pay_frequency->frequency_name : '' }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('hourly_rate') }}</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 fw-semi-bold text-muted"> {{ app_setting()->currency?->title }}
                                {{ $employee->hourly_rate }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fs-17 fw-semi-bold mb-0">{{ localize('bank_info') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('account_no') }}</h6>
                        </div>
                        <div class="col-auto">
                            <time class="fs-13 text-muted">{{ $bank_info?->acc_number }}</time>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('bban_num') }}</h6>
                        </div>
                        <div class="col-auto">
                            <time class="fs-13 text-muted">{{ $bank_info?->bban_num }}</time>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('bank_name') }}</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 text-muted">{{ $bank_info?->bank_name }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('branch_name') }}</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 text-muted">{{ $bank_info?->branch_address }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('tax_identification_no') }}</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 text-muted">{{ $employee_file?->tin_no }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fs-17 fw-semi-bold mb-0">{{ localize('salary_benefits') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('basic') }}</h6>
                        </div>
                        <div class="col-auto">
                            <time class="fs-13 text-muted">{{ app_setting()->currency?->title }}
                                {{ number_format($employee_file->basic ?? 0.0, 2) }}</time>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('transport_allowance') }}</h6>
                        </div>
                        <div class="col-auto">
                            <time class="fs-13 text-muted">{{ app_setting()->currency?->title }}
                                {{ number_format($employee_file->transport ?? 0.0, 2) }}</time>
                        </div>
                    </div>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{ localize('gross_salary') }}</h6>
                        </div>
                        <div class="col-auto">
                            <span class="fs-13 text-muted">{{ app_setting()->currency?->title }}
                                {{ number_format($employee_file->gross_salary, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fs-17 fw-semi-bold mb-0">{{ localize('deductions') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach ($deductions as $deduction)
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="mb-0">{{ $deduction->name }}</h6>
                            </div>
                            <div class="col-auto">
                                <time class="fs-13 text-muted">{{ app_setting()->currency?->title }}
                                    {{ number_format($deduction?->employee_salary_types->first()?->amount ?? 0.0, 2) }}</time>
                            </div>
                        </div>
                        <hr>
                    @endforeach

                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fs-17 fw-semi-bold mb-0">{{ localize('bonuses') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach ($bonuses as $bonus)
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="mb-0">{{ $bonus->name }}</h6>
                            </div>
                            <div class="col-auto">
                                <time class="fs-13 text-muted">{{ app_setting()->currency?->title }}
                                    {{ number_format($bonus?->employee_salary_types->first()?->amount ?? 0.0, 2) }}</time>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="fs-17 fw-semi-bold mb-0">{{ localize('employee_documents') }}</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="" class="table display table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">{{ localize('sl') }}.</th>
                                    <th>{{ localize('doc_title') }}</th>
                                    <th>{{ localize('file') }}</th>
                                    <th>{{ localize('expiry_date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employee->employee_docs as $key => $doc)
                                    <tr
                                        class="{{ check_expiry($doc->expiry_date) ? 'bg-danger' : '' }} {{ check_expiry($doc->expiry_date, 30) ? 'bg-warning' : '' }}">
                                        <td class="text-center" class="align-middle">{{ $key + 1 }}</td>
                                        <td class="align-middle">{{ $doc->doc_title }}</td>
                                        <td class="align-middle"><a
                                                href="{{ url('/public/storage/' . $doc->file_path) }}" target="_blank"
                                                rel="noopener noreferrer"><i class="far fa-file fa-lg fs-20"></i></a></td>
                                        <td class="align-middle">{{ $doc->expiry_date }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
    </div>

@endsection
