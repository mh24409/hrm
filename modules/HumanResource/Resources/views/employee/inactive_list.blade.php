@extends('backend.layouts.app')
@section('title', localize('employee_list'))
@section('content')
    @include('humanresource::employee_header')
    @include('backend.layouts.common.validation')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('employee_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        <button type="button" class="btn btn-success" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"> <i
                                class="fas fa-filter"></i> {{ localize('filter') }}</button>
                        @can('create_inactive_employees_list')
                            <a href="{{ route('employees.create') }}" class="btn btn-success"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('add_employee') }}</a>
                        @endcan

                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <div id="flush-collapseOne" class="accordion-collapse collapse bg-white mb-4"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">

                                <div class="row">
                                    <div class="col-md-2 mb-4">
                                        <select id="employee_name" class="select-basic-single">
                                            <option selected disabled>{{ localize('select_employee') }}</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ ucwords($employee->full_name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <select id="employee_id" class="select-basic-single">
                                            <option selected disabled>{{ localize('select_employee_id') }}</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->employee_id }}">
                                                    {{ $employee->employee_id }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <select id="employee_type" class="select-basic-single">
                                            <option selected disabled>{{ localize('select_employee_type') }}</option>
                                            @foreach ($employeeTypes as $employeeType)
                                                <option value="{{ $employeeType->id }}">{{ $employeeType->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <select id="department" class="select-basic-single">
                                            <option selected disabled>{{ localize('select_department') }}</option>
                                            @foreach ($departments as $key => $department)
                                                <option value="{{ $department->id }}">
                                                    {{ ucwords($department->department_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <select id="designation" class="select-basic-single">
                                            <option selected disabled>{{ localize('select_designation') }}</option>
                                            @foreach ($positions as $key => $position)
                                                <option value="{{ $position->id }}">{{ $position->position_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <select id="blood_group" class="select-basic-single">
                                            <option selected disabled>{{ localize('select_blood_group') }}</option>
                                            @foreach (config('humanresource.bloodGroups') as $key => $value)
                                                <option value="{{ $value }}">{{ $key }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <select id="country" class="select-basic-single">
                                            <option selected disabled>{{ localize('select_country') }}</option>
                                            @foreach ($countries as $key => $country)
                                                <option value="{{ $country->country_name }}">
                                                    {{ ucwords($country->country_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <select id="gender" class="select-basic-single">
                                            <option selected disabled>{{ localize('select_gender') }}</option>
                                            @foreach ($genders as $gender)
                                                <option value="{{ $gender->id }}">{{ ucwords($gender->gender_name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <select id="marital_status" class="select-basic-single">
                                            <option selected disabled>{{ localize('select_marital_status') }}</option>
                                            @foreach ($marital_statuses as $marital_status)
                                                <option value="{{ $marital_status->id }}">
                                                    {{ ucwords($marital_status->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4 align-self-end">
                                        <button type="button" id="filter"
                                            class="btn btn-success">{{ localize('find') }}</button>
                                        <button type="button" id="search-reset"
                                            class="btn btn-danger">{{ localize('reset') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table_customize">
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

@endsection
@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script src="{{ module_asset('HumanResource/js/employee-filter.js') }}"></script>
@endpush
