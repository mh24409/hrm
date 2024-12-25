@extends('backend.layouts.app')
@section('title', localize('employee_points'))
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('employee_points') }}</h6>
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
                            <div id="flush-collapseOne" class="accordion-collapse collapse bg-white mb-4"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">

                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" onfocus="(this.type='month')"
                                            name="start_date" placeholder="Start date" id="start_date">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control" onfocus="(this.type='month')"
                                            name="end_date" placeholder="End date" id="end_date">
                                    </div>
                                    <div class="col-md-2 mb-4 align-self-end">
                                        <button type="button" id="emp_points_filter"
                                            class="btn btn-success">{{ localize('find') }}</button>
                                        <button type="button" id="point_search_reset"
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
    <script src="{{ module_asset('HumanResource/js/employee-points.js') }}"></script>
@endpush
