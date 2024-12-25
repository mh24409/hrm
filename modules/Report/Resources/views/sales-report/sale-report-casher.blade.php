@extends('backend.layouts.app')
@section('title', localize('sale_report_cashier'))
@push('css')
    <link rel="stylesheet" href="{{ asset('backend/invoice/assets/css/theme-1.css?v_' . date('h_i')) }}">
    <link href="{{ module_asset('Report/css/report-pdf.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    @include('report::sales-report.sales-report-header')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('sale_report_cashier') }}</h6>
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
                            <div id="flush-collapseOne" class="accordion-collapse collapse bg-white mb-4"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">

                                <div class="row">
                                    <div class="col-md-2 mb-4">
                                        <input type="number" class="form-control" id="sale_no"
                                            placeholder="{{ localize('sale_no') }}">
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <select id="customer_name" class="select-basic-single"
                                            data-url="{{ $customer_url }}">
                                            <option selected disabled>{{ localize('customer_name') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <input type="text" class="form-control date-range" id="sale_date"
                                            autocomplete="off" placeholder="{{ localize('sale_date') }}">
                                    </div>
                                    <div class="col-md-2 mb-4 align-self-end">
                                        <button type="button" name="filter" id="filter"
                                            class="btn btn-success">{{ localize('find') }}</button>
                                        <button type="button" name="reset" id="reset"
                                            class="btn btn-danger">{{ localize('reset') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table_customize">
                {{ $dataTable->table([], true) }}
            </div>
        </div>
    </div>
@endsection
@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script src="{{ module_asset('Report/js/sale-report.js') }}"></script>
    <script src="{{ module_asset('Report/js/invoice-details.js') }}"></script>
@endpush
