@extends('backend.layouts.app')
@section('title', @localize('interview'))
@push('css')
@endpush
@section('content')
@include('backend.layouts.common.validation')
@include('backend.layouts.common.message')
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('interview') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_interview')
                            <a class="btn btn-success btn-sm" onclick="addInterview()"><i
                                class="fa-sharp fa-solid fa-circle-plus"></i>
                                {{ localize('interview') }}</a>
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
    <div class="modal fade" id="interviewDetailsModal" aria-labelledby="interviewDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="interviewDetailsModalLabel"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal">×</button>
                </div>
                <form id="interviewDetailsForm" method="POST" enctype="multipart/form-data">
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

        <input type="hidden" id="interview_create" value="{{ route('interview.create') }}">
        <input type="hidden" id="lang_interview_form" value="{{ localize('interview_form') }}">
        <input type="hidden" id="interview_store" value="{{ route('interview.store') }}">

        <input type="hidden" id="interview_get_position" value="{{ route('interview.get-position') }}">
        <input type="hidden" id="interview_edit" value="{{ route('interview.edit', ':interview') }}">
        <input type="hidden" id="interview_update" value="{{ route('interview.update', ':interview') }}">
        <input type="hidden" id="lang_update_interview" value="{{ localize('update_interview') }}">

    </div>

@endsection

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script src="{{ module_asset('HumanResource/js/interview-index.js') }}"></script>


@endpush


