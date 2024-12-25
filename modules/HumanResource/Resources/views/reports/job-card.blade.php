@extends('backend.layouts.app')
@section('title', localize('job_card_report'))
@push('css')
@endpush
@section('content')
    @include('humanresource::reports_header')
    @include('backend.layouts.common.validation')

    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('job_card_report') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
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
                                <div class="row">
                                    <div class="col-md-2 mb-4">
                                        <div class="form-group mx-0 row">
                                            <div class="col-md-12 pe-0">
                                                <select name="employee_id" id="employee_id"
                                                    class="form-control select-basic-single">
                                                    <option value="0" selected>{{ localize('select_employee') }}
                                                    </option>
                                                    @foreach ($employees as $key => $employee)
                                                        <option value="{{ $employee->id }}">
                                                            {{ ucwords($employee->full_name) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 mb-4">
                                        <input type="text" class="form-control date-range" name="date"
                                            placeholder="{{ localize('date') }}">
                                    </div>

                                    <div class="col-md-2 mb-4">
                                        <button type="button" id="show-report"
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
                <input type="hidden" name="job_card_report_url" value="{{ route('reports.job_card_reports') }}">

                <div id="report-result">
                </div>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/report-filter.js') }}"></script>
@endpush
