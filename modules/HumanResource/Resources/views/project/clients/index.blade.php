@extends('backend.layouts.app')
@section('title', localize('clients'))
@push('css')
@endpush
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('clients') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_clients')
                            <a href="#" class="btn btn-success btn-sm" onclick="addClientDetails()"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('new_client') }}</a>
                        @endcan

                        
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table display table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">{{ localize('sl') }}</th>
                            <th width="20%">{{ localize('client_name') }}</th>
                            <th width="20%">{{ localize('company_name') }}</th>
                            <th width="20%">{{ localize('email') }}</th>
                            <th width="20%">{{ localize('country') }}</th>
                            <th width="15%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dbData as $key => $data)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $data->client_name }}</td>
                                <td>{{ $data->company_name }}</td>
                                <td>{{ $data->email }}</td>
                                <td>{{ $data->countryDetail->country_name }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary-soft btn-sm me-1" title="Edit" onclick="editClientDetails({{ $data->id }})"><i
                                            class="fa fa-edit"></i></a>

                                    <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                        data-bs-toggle="tooltip" title="Delete"
                                        data-route="{{ route('project.destroy', $data->uuid) }}"
                                        data-csrf="{{ csrf_token() }}"><i class="fa fa-trash"></i></a>

                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center">{{ localize('empty_data') }}</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="clientDetailsModal" aria-labelledby="addClientDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClientDetailsModalLabel"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal">×</button>
                </div>
                <form id="clientDetailsForm" method="POST" enctype="multipart/form-data">
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

        <input type="hidden" id="client_create" value="{{ route('project.create') }}">
        <input type="hidden" id="client_store" value="{{ route('project.store') }}">
        <input type="hidden" id="lang_add_client" value="{{ localize('add_client') }}">

        <input type="hidden" id="client_edit" value="{{ route('project.edit', ':client') }}">
        <input type="hidden" id="client_update" value="{{ route('project.update', ':client') }}">
        <input type="hidden" id="lang_update_client" value="{{ localize('update_client') }}">
        
    </div>

@endsection
@push('js')

<script src="{{ module_asset('HumanResource/js/client-list.js') }}"></script>
@endpush
