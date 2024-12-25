@extends('backend.layouts.app')
@section('title', @localize('vendor_list'))
@push('css')
@endpush
@section('content')
@include('backend.layouts.common.validation')
@include('backend.layouts.common.message')
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('vendor_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_vendors')
                            <a class="btn btn-success btn-sm" onclick="addVendor()"><i
                                class="fa-sharp fa-solid fa-circle-plus"></i>
                                {{ localize('add_vendor') }}</a>
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

    <!-- Modal -->
    <div class="modal fade" id="vendorDetailsModal" aria-labelledby="vendorDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vendorDetailsModalLabel"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal">×</button>
                </div>
                <form id="vendorDetailsForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger-soft me-2"
                            data-bs-dismiss="modal">{{ localize('close') }}</button>
                        <button type="submit" class="btn btn-success me-2"></i>{{ localize('save') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <input type="hidden" id="vendor_create" value="{{ route('vendor.create') }}">
        <input type="hidden" id="vendor_store" value="{{ route('vendor.store') }}">
        <input type="hidden" id="lang_add_vendor" value="{{ localize('add_vendor') }}">

        <input type="hidden" id="vendor_edit" value="{{ route('vendor.edit', ':vendor') }}">
        <input type="hidden" id="vendor_update" value="{{ route('vendor.update', ':vendor') }}">
        <input type="hidden" id="lang_update_vendor" value="{{ localize('update_vendor') }}">

    </div>

@endsection

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script src="{{ module_asset('HumanResource/js/vendor.js') }}"></script>

@endpush


