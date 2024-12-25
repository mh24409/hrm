@extends('backend.layouts.app')
@section('title', localize('supplier_wise_sale_profit'))
@push('css')
    <link href="{{ module_asset('Report/css/report-pdf.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('backend/invoice/assets/css/theme-1.css?v_' . date('h_i')) }}">
@endpush
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    @include('report::sales-report.sales-report-header')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('supplier_wise_sale_profit') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        <button type="button" class="btn btn-success" data-bs-toggle="collapse"
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
                            <div id="flush-collapseOne" class="accordion-collapse bg-white mb-4 collapse"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">

                                <div class="row">
                                    <div class="col-md-2 mb-4">
                                        <select id="supplier" class="select-basic-single" data-url="{{ $supplier_url }}">
                                            <option value="" selected>{{ localize('supplier') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <input type="text" class="form-control custom-date-range-supplier-wise-sale"
                                            id="date" autocomplete="off" data-toDate="{{ $toDate }}"
                                            data-formDate="{{ $formDate }}" placeholder="{{ localize('date') }}">
                                    </div>
                                    <div class="col-md-2 mb-4 pt-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="sale_report" checked
                                                id="inlineRadio1" value="sale_wise">
                                            <label class="form-check-label"
                                                for="inlineRadio1">{{ localize('sale_wise') }}</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="sale_report"
                                                id="inlineRadio2" value="day_wise">
                                            <label class="form-check-label"
                                                for="inlineRadio2">{{ localize('day_wise') }}</label>
                                        </div>
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
    <!-- Modal -->
    @include('report::invoice-modal')
@endsection
@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script src="{{ module_asset('Report/js/supplier_wise_sale_profit.js') }}"></script>
    <script src="{{ module_asset('Report/js/invoice-details.js') }}"></script>
@endpush
