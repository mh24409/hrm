@extends('backend.layouts.app')
@section('title', localize('salary_advanced_list'))
@section('content')
    @include('humanresource::payroll_header')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('salary_advanced_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_salary_advance')
                            <a href="#" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#create-salary-advance"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('add_salary_advance') }}</a>
                        @endcan
                        @include('humanresource::salary_advance.modal.create')
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('backend.layouts.common.validation')
            @include('backend.layouts.common.message')
            <div class="row">
                <div class="col-12">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <div id="flush-collapseOne" class="accordion-collapse collapse bg-white mb-4"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">

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
@endpush
