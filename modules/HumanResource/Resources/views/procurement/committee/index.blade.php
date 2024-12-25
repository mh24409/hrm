@extends('backend.layouts.app')
@section('title', @localize('committee_list'))
@push('css')
@endpush
@section('content')
@include('backend.layouts.common.validation')
@include('backend.layouts.common.message')
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('committee_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_committees')
                            <a class="btn btn-success btn-sm" onclick="addCommittee()"><i
                                class="fa-sharp fa-solid fa-circle-plus"></i>
                                {{ localize('add_committee') }}</a>
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
    <div class="modal fade" id="committeeDetailsModal" aria-labelledby="committeeDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="committeeDetailsModalLabel"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal">×</button>
                </div>
                <form id="committeeDetailsForm" method="POST" enctype="multipart/form-data">
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

        <input type="hidden" id="committee_create" value="{{ route('committee.create') }}" />
        <input type="hidden" id="lang_add_committee" value="{{ localize('add_committee') }}" />
        <input type="hidden" id="committee_store" value="{{ route('committee.store') }}" />

        <input type="hidden" id="committee_edit" value="{{ route('committee.edit', ':committee') }}" />
        <input type="hidden" id="lang_update_committee" value="{{ localize('update_committee') }}" />
        <input type="hidden" id="committee_update" value="{{ route('committee.update', ':committee') }}" />

    </div>

@endsection

@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script src="{{ module_asset('HumanResource/js/committee.js') }}"></script>

@endpush


