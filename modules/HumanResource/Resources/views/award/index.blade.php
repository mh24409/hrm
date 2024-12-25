@extends('backend.layouts.app')
@section('title', localize('award_list'))
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <input type="hidden" id="awardCreate" value="{{ route('award.create') }}">
    <input type="hidden" id="awardStore" value="{{ route('award.store') }}">
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('award_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_award')
                            <a class="btn btn-success btn-sm" onclick="addAwardDetails()"><i
                                    class="fa-sharp fa-solid fa-circle-plus"></i>
                                {{ localize('add_new_award') }}</a>
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
    <div class="modal fade" id="awardDetailsModal" aria-labelledby="awardDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="awardDetailsModalLabel"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal">×</button>
                </div>
                <form id="awardDetailsForm" method="POST" enctype="multipart/form-data">
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
    </div>
@endsection
@push('js')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script src="{{ module_asset('HumanResource/js/award.js') }}"></script>
@endpush
