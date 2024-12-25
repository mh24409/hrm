@extends('backend.layouts.app')
@section('title', localize('Employee Performance List'))
@section('content')
    @include('backend.layouts.common.validation')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('Employee Performance List') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_employee_performance')
                            <a href="{{ route('employee-performances.create') }}" class="btn btn-success btn-sm"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('Add New Employee Performance') }}</a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
@endsection
@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
