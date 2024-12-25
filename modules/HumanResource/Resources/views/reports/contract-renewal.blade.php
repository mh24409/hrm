@extends('backend.layouts.app')
@section('title', localize('contract_renewal_report'))
@push('css')
@endpush
@section('content')

    @include('humanresource::reports_header')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('contract_renewal_report') }}</h6>
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
                                <div class="card">
                                    <div class="card-body">
                                        <form class="row g-3" action="" method="GET">
                                            <div class="col-md-2">
                                                <div class="form-group mx-0 row">
                                                    <div class="col-md-12 pe-0">
                                                        <select name="department_id" id="department_id"
                                                            class="form-control select-basic-single {{ $errors->first('department_id') ? 'is-invalid' : '' }}">
                                                            <option value="" selected disabled>
                                                                {{ localize('select_department') }}</option>
                                                            @foreach ($departments as $key => $department)
                                                                <option value="{{ $department->id }}">
                                                                    {{ $department->department_name }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('department_id'))
                                                            <div class="error text-danger">
                                                                {{ $errors->first('department_id') }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <button type="button" id="filter"
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
            {{ $dataTable->table() }}

        </div>
    </div>

@endsection
@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script src="{{ module_asset('HumanResource/js/contract-renewal.js') }}"></script>
@endpush
