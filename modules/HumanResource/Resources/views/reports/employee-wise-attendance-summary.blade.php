@extends('backend.layouts.app')
@section('title', localize('employee_wise_attendance_report'))
@push('css')
    <link rel="stylesheet" href="{{ module_asset('HumanResource/css/att.css?v=1') }}">
@endpush
@section('content')
    @include('humanresource::reports_header')
    @include('backend.layouts.common.validation')

    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('employee_wise_attendance_report') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        <button type="button" class="btn btn-success btn-sm" onclick="printDetails();"> <i
                                class="fas fa-print"></i> {{ localize('print') }}</button>
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"> <i
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
                                <div class="card">
                                    <div class="card-body">
                                        <form class="row g-3" action="" method="GET">
                                            <div class="col-md-4">
                                                <div class="form-group mb-3 mx-0 pb-3 ">

                                                    <div class="pe-0">
                                                        <label
                                                            class="col-md-3 col-form-label ps-0">{{ localize('date') }}<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control account-date-range"
                                                            id="date-range" name="date" placeholder="Select Date"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb-3 mx-0 pb-3 ">

                                                    <div class="pe-0">
                                                        <label
                                                            class="col-md-3 col-form-label ps-0">{{ localize('employee') }}<span
                                                                class="text-danger">*</span></label>
                                                        <select name="employee_id" id="employee_id" class="form-control">
                                                            <option value="">{{ localize('select_employee') }}
                                                            </option>
                                                            @foreach ($employees as $key => $employee)
                                                                <option value="{{ $employee->id }}">
                                                                    {{ $employee->full_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('employee_id'))
                                                            <div class="error text-danger">
                                                                {{ $errors->first('employee_id') }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2" style="margin-top: 57px;">
                                                <button type="submit" id="filter"
                                                    class="btn btn-success">{{ localize('find') }}</button>
                                                <button type="button" id="searchreset"
                                                    class="btn btn-danger">{{ localize('reset') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="submit_url" value="{{ route('reports.employee_wise_attendance_summery_reports') }}">

        <div class="card-body" id="print-table">
            <div id="allResult">
            </div>
        </div>
    </div>


@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/employee-wise-attendance.js') }}"></script>
@endpush
