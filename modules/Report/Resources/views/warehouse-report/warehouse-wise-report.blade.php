@extends('backend.layouts.app')
@section('title', localize('warehouse_wise_report'))
@push('css')
    <link href="{{ module_asset('Report/css/report-pdf.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    @include('report::warehouse-report.warehouse-report-header')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('warehouse_wise_report') }}</h6>
                </div>
                <div class="actions text-end">
                    <button type="button" class="btn btn-success" data-bs-toggle="collapse"
                        data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"> <i
                            class="fas fa-filter"></i> {{ localize('filter') }}</button>
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
                                        <select id="warehouse_id" class="select-basic-single">
                                            <option value="0" selected>{{ localize('all_warehouse') }}</option>
                                            @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-4">
                                        <input type="text" name="date" class="form-control" id="stock-report-range"
                                            autocomplete="off" placeholder="{{ localize('date_range') }}">
                                    </div>
                                    <div class="col-md-2 mb-4 align-self-end">
                                        <button type="button" id="filter"
                                            class="btn btn-success">{{ localize('find') }}</button>
                                        <button type="button" id="search-reset"
                                            class="btn btn-danger">{{ localize('reset') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table_customize">
                <table class="table display table-bordered table-striped table-hover align-middle text-end"
                    id="warehouse-wise-quantity-report">
                    <thead class="align-middle">
                        <tr>
                            <th class="text-start">{{ localize('sl') }}</th>
                            <th class="text-start">{{ localize('warehouse_name') }}</th>
                            <th>{{ localize('opening_qty') }}<br> <span class="text-success"> (+)</span></th>
                            <th>{{ localize('received_qty') }}<br> <span class="text-success"> (+)</span></th>
                            <th>{{ localize('delivered_qty') }}<br> <span class="text-danger"> (-)</span></th>
                            <th>{{ localize('purchase_return_qty') }}<br> <span class="text-danger"> (-)</span></th>
                            <th>{{ localize('sale_ret_Qty') }} <br> <span class="text-success"> (+)</span></th>
                            <th>{{ localize('wastage_qty') }} <br> <span class="text-danger"> (-)</span></th>
                            <th>{{ localize('adjustment_qty') }} <br> <span class="text-danger"> (-)</span></th>
                            <th>{{ localize('transfer_qty') }} <br> <span class="text-danger"> (-)</span></th>
                            <th>{{ localize('transfer_rec_qty') }} <br> <span class="text-success"> (+)</span></th>
                            <th>{{ localize('stock') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-end"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ module_asset('Warehouse/js/warehouse-wise-stock.js') }}"></script>
@endpush
