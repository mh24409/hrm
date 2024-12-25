@extends('backend.layouts.app')
@section('title', localize('request_list'))
@push('css')
@endpush
@section('content')
@include('backend.layouts.common.validation')
@include('backend.layouts.common.message')
    
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('request_list') }}</h6>
            </div>
            <div class="text-end">
                <div class="actions">
                    @can('create_request')
                        <a href="{{ route('procurement_request.create') }}" class="btn btn-success btn-sm"><i
                                class="fa-sharp fa-solid fa-circle-plus"></i>
                            {{ localize('add_new_request') }}</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table_customize">
            {{ $dataTable->table() }}
        </div>
    </div>
</div>

@endsection

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
