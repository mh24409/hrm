@extends('backend.layouts.app')
@section('title', localize('attendance_report'))
@push('css')
    <link href="{{ module_asset('HumanResource/css/report.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('humanresource::reports_header')
    @include('backend.layouts.common.validation')

    <div class="card mb-4 fixed-tab-body">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('lateness_closing_attendance_report') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        <button type="button" class="btn btn-success btn-sm collapsed" data-bs-toggle="collapse"
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
                                        <select name="employee_id" id="employee_id"
                                            class="select-basic-single {{ $errors->first('employee_id') ? 'is-invalid' : '' }}">
                                            <option value="0" selected>{{ localize('all_employees') }}
                                            </option>
                                            @foreach ($employees as $key => $employee)
                                                <option value="{{ $employee->id }}">
                                                    {{ $employee->full_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <select name="year" id="year"
                                            class="form-control select-basic-single {{ $errors->first('year') ? 'is-invalid' : '' }}">
                                            <option value="0" selected>{{ localize('select_year') }}
                                            </option>
                                            @for ($year = \Carbon\Carbon::now()->year; $year >= 1995; $year--)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <select name="month" id="month"
                                            class="form-control select-basic-single {{ $errors->first('month') ? 'is-invalid' : '' }}">
                                            <option value="0" selected>{{ localize('select_month') }}
                                            </option>
                                            @for ($month = 1; $month <= 12; $month++)
                                                <option value="{{ $month }}">
                                                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-4">
                                        <button type="button" id="attendances-filter"
                                            class="btn btn-success">{{ localize('find') }}</button>
                                        <button type="button" id="attendances-search-reset"
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
    <script src="{{ module_asset('HumanResource/js/lateness-closing-report.js') }}"></script>
@endpush
