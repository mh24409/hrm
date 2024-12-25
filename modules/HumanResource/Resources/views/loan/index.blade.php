@extends('backend.layouts.app')
@section('title', localize('loan_list'))
@section('content')
    @include('humanresource::loan_header')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('loan_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        <button type="button" class="btn btn-success" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"> <i
                                class="fas fa-filter"></i> {{ localize('filter') }}</button>

                        @can('create_loan')
                            <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#create-loan"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('add_loan') }}</a>
                        @endcan

                        @include('humanresource::loan.modal.create')
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
                                        <select id="employee_name" name="employee_name" class="select-basic-single">
                                            <option value="">{{ localize('all_employees') }}</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ ucwords($employee->full_name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4 align-self-end">
                                        <button type="button" id="loan-filter"
                                            class="btn btn-success">{{ localize('find') }}</button>
                                        <button type="button" id="loan-search-reset"
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
    <script src="{{ module_asset('HumanResource/js/loan.js') }}"></script>
@endpush
