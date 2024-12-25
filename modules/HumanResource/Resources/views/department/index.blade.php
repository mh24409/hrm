@extends('backend.layouts.app')
@section('title', localize('department_list'))
@section('content')
    @include('backend.layouts.common.validation')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('department_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_department')
                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#create-department"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('add_department') }}</a>
                            @include('humanresource::department.modal.create')
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="edit-department" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Body -->
            </div>
        </div>
    </div>
@endsection
@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script src="{{ module_asset('HumanResource/js/department.js') }}"></script>
@endpush
