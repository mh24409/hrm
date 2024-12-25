@extends('backend.layouts.app')
@section('title', @localize('candidate_shortlist'))
@push('css')
@endpush
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('candidate_shortlist') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_candidate_shortlist')
                            <a class="btn btn-success btn-sm" onclick="addCandidateShortlist()"><i
                                class="fa-sharp fa-solid fa-circle-plus"></i>
                                {{ localize('add_shortlist') }}</a>
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
    <div class="modal fade" id="shortlistDetailsModal" aria-labelledby="shortlistDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shortlistDetailsModalLabel"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal">×</button>
                </div>
                <form id="shortlistDetailsForm" method="POST" enctype="multipart/form-data">
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

        <!-- Hidden data -->
        <input type="hidden" id="shortlist_create" value="{{ route('shortlist.create') }}">
        <input type="hidden" id="lang_shortlist_form" value="{{ localize('shortlist_form') }}">
        <input type="hidden" id="shortlist_store_url" value="{{ route('shortlist.store') }}">

        <input type="hidden" id="shortlist_edit_url" value="{{ route('shortlist.edit', ':shortlist') }}">
        <input type="hidden" id="update_shortlist" value="{{ localize('update_shortlist') }}">
        <input type="hidden" id="shortlist_update_url" value="{{ route('shortlist.update', ':shortlist') }}">

    </div>

@endsection

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script src="{{ module_asset('HumanResource/js/shortlist-index.js') }}"></script>
@endpush


