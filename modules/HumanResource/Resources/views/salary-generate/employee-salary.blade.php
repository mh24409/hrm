@extends('backend.layouts.app')
@section('title', localize('employee_salary'))
@section('content')
    @include('humanresource::payroll_header')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header d-print-none">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fs-20 fw-semi-bold mb-0">{{ localize('employee_salary') }}</h4>
                </div>
            </div>
            <div class="text-end">
                <div class="actions">
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"> <i
                            class="fas fa-filter"></i> {{ localize('filter') }}</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('backend.layouts.common.validation')
            @include('backend.layouts.common.message')
            <div class="row">
                <div class="col-12">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <div id="flush-collapseOne" class="accordion-collapse collapse bg-white mb-4"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">

                                <div class="row">
                                    <div class="col-md-3 mb-4">
                                        <input type="month" class="form-control" id="salary_month" autocomplete="off"
                                            value ="{{ \Carbon\Carbon::now()->format('Y-m') }}"
                                            placeholder="{{ localize('salary_month') }}">
                                    </div>

                                    <div class="col-md-2 mb-4 align-self-end">
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
            {{ $dataTable->table() }}
        </div>
    </div>
@endsection
@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script src="{{ module_asset('HumanResource/js/employee-salary.js') }}"></script>
@endpush
