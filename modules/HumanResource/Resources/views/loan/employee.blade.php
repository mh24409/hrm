@extends('backend.layouts.app')
@section('title', localize('employee_wise_loan_report'))
@section('content')
    @include('humanresource::loan_header')
    @include('backend.layouts.common.validation')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('employee_wise_loan_report') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        <button type="button" class="btn btn-success btn-sm" onclick="printContent();"> <i
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
                                <div class="row">
                                    <div class="col-md-2 mb-4">
                                        <div class="form-group mx-0 row">
                                            <div class="col-md-12 pe-0">
                                                <select name="employee_id" id="employee_id"
                                                    class="form-control select-basic-single">
                                                    <option value="" selected disabled>
                                                        {{ localize('select_employee') }}
                                                    </option>
                                                    @foreach ($employees as $key => $employee)
                                                        <option value="{{ $employee->id }}">
                                                            {{ $employee->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="text-danger error_employee_id"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2 mb-4">
                                        <input type="text" class="form-control" id="reportrange" name="date"
                                            placeholder="{{ localize('date') }}">
                                    </div>

                                    <div class="col-md-2 mb-4">
                                        <button type="button" id="employee-load-report-filter"
                                            class="btn btn-success">{{ localize('find') }}</button>
                                        <button type="button" id="employee-load-report-filter-reset"
                                            class="btn btn-danger">{{ localize('reset') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="employee_loan_report_url" value="{{ route('hr.loans.employee.report') }}">
        <div class="card-body" id="print-content">
            <div id="allResult"></div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/loan.js') }}"></script>
@endpush
