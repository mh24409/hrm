@extends('backend.layouts.app')
@section('title', 'Performance Criteria List')
@section('content')
    <!--/.Content Header (Page header)-->
    <div class="body-content">
        @include('backend.layouts.common.validation')
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 fw-semi-bold mb-0">Performance Criteria List</h6>
                    </div>
                    <div class="text-end">
                        <div class="actions">
                            @can('add_performance_criteria')
                                <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#create-criteria"><i class="fa fa-plus-circle"></i>&nbsp;Add Criteria</a>
                                @include('humanresource::employee.performance.criteria.modal.create')
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                {{ $dataTable->table(['class' => 'table table-bordered table-striped table-hover w-100']) }}
            </div>
        </div>
    </div>
    <!--/.body content-->
@endsection
@push('js')
    <script src="{{ asset('public/backend/assets/sweetalert.js') }}"></script>
    {{ $dataTable->scripts() }}
@endpush
