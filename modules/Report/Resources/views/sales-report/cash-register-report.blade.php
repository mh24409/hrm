@extends('backend.layouts.app')
@section('title', localize('cash_register_report'))
@push('css')
    <link href="{{ module_asset('Report/css/cash-report.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    @include('report::sales-report.sales-report-header')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('cash_register_report') }}</h6>
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
                                        <select id="user_name" class="select-basic-single">
                                            <option value="" selected>{{ localize('all_user_name') }}</option>
                                            @foreach ($users as $key => $user)
                                                <option value="{{ $user->id }}">{{ ucwords($user->full_name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <select id="counter_no" class="select-basic-single">
                                            <option value="" selected>{{ localize('all_counter_no') }}</option>
                                            @foreach ($counters as $key => $counter)
                                                <option value="{{ $counter->id }}">{{ ucwords($counter->no) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-4 align-self-end">
                                        <button type="button" name="filter" id="filter"
                                            class="btn btn-success">{{ localize('find') }}</button>
                                        <button type="button" name="reset" id="search-reset"
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
    <div class="modal fade openregister" id="openregister" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="openregisterLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="openclosecash">
            </div>
        </div>
    </div>
@endsection
@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script src="{{ module_asset('Report/js/cash-report.js') }}"></script>
@endpush
