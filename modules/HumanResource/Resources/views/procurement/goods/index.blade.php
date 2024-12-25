@extends('backend.layouts.app')
@section('title', localize('goods_received_list'))
@push('css')
@endpush
@section('content')
@include('backend.layouts.common.validation')
@include('backend.layouts.common.message')
    
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('goods_received_list') }}</h6>
            </div>
            <div class="text-end">
                <div class="actions">
                    @can('create_goods_received')
                        <a href="{{ route('goods.create') }}" class="btn btn-success btn-sm"><i
                                class="fa-sharp fa-solid fa-circle-plus"></i>
                            {{ localize('goods_receive') }}</a>
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
