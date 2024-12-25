@extends('backend.layouts.app')
@section('title', @localize('candidate_selection'))
@push('css')
@endpush
@section('content')
@include('backend.layouts.common.validation')
@include('backend.layouts.common.message')
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('candidate_selection') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_candidate_selection')
                            <a class="btn btn-success btn-sm" onclick="addSelection()"><i
                                class="fa-sharp fa-solid fa-circle-plus"></i>
                                {{ localize('add_selection') }}</a>
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
    <div class="modal fade" id="selectionDetailsModal" aria-labelledby="selectionDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectionDetailsModalLabel"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal">×</button>
                </div>
                <form id="selectionDetailsForm" method="POST" enctype="multipart/form-data">
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

        <input type="hidden" id="lang_selection_form" value="{{ localize('selection_form') }}">
        <input type="hidden" id="selection_store" value="{{ route('selection.store') }}">
        <input type="hidden" id="selection_create" value="{{ route('selection.create') }}">

        <input type="hidden" id="interview_get_position" value="{{ route('interview.get-position') }}">

    </div>

@endsection

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script src="{{ module_asset('HumanResource/js/selection-index.js') }}"></script>
@endpush


