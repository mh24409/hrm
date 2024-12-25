@extends('backend.layouts.app')
@section('title', localize('salary_setup'))
@push('css')
@endpush
@section('content')

    @include('humanresource::payroll_header')


    @include('backend.layouts.common.validation')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('salary_setup_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_salary_setup')
                            <a href="{{ route('salary-setup.create') }}" class="btn btn-success btn-sm"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('salary_setup') }}</a>
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
