@extends('backend.layouts.app')
@section('title', localize('monthly_present_report'))
@push('css')
@endpush
@section('content')
    @include('humanresource::reports_header')
    @include('backend.layouts.common.validation')

    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('monthly_present_report') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="true" aria-controls="flush-collapseOne"> <i
                                class="fas fa-filter"></i> {{ localize('filter') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <div id="flush-collapseOne" class="accordion-collapse collapse bg-white mb-4 show"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="row">
                                    <div class="col-md-2 mb-4">
                                        <div class="form-group mx-0 row">
                                            <div class="col-md-12 pe-0">
                                                <select name="department_id" id="department_id"
                                                    class="form-control select-basic-single" @required(true)>
                                                    <option value="" selected disabled>
                                                        {{ localize('select_department') }}</option>
                                                    <option value="0">{{ localize('all') }}</option>
                                                    @foreach ($departments as $key => $department)
                                                        <option value="{{ $department->id }}">
                                                            {{ $department->department_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <div class="form-group mx-0 row">
                                            <div class="col-md-12 pe-0">
                                                <select name="employee_id" id="employee_id"
                                                    class="form-control select-basic-single" @required(true)>
                                                    <option value="" disabled>
                                                        {{ localize('select_employee') }}</option>
                                                    <option value="0">{{ localize('all') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <select name="year" id="year"
                                            class="form-control select-basic-single {{ $errors->first('year') ? 'is-invalid' : '' }}">
                                            <option value="" selected>{{ localize('select_year') }}
                                            </option>
                                            @for ($year = \Carbon\Carbon::now()->year; $year >= 1995; $year--)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <select name="month" id="month"
                                            class="form-control select-basic-single {{ $errors->first('month') ? 'is-invalid' : '' }}">
                                            <option value="" selected>{{ localize('select_month') }}
                                            </option>
                                            @for ($month = 1; $month <= 12; $month++)
                                                <option value="{{ $month }}">
                                                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-4">
                                        <button type="button" id="filter"
                                            class="btn btn-success">{{ localize('find') }}</button>
                                        <button type="button" id="searchreset"
                                            class="btn btn-danger">{{ localize('reset') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="table_customize">
                <input type="hidden" id="url" value="{{ route('reports.monthly-report') }}" />
                <div id="report-result">
                </div>
            </div>
        </div>

        <input type="hidden" id="get_employees_department" value="{{ route('get-employees-department') }}">
        <input type="hidden" id="lang_all" value="{{ localize('all') }}">
        <input type="hidden" id="monthlyUrl" value="{{ route('reports.monthly') }}">

    </div>

@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/monthly-report.js') }}"></script>
@endpush
