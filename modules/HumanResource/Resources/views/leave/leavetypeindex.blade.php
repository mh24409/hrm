@extends('backend.layouts.app')
@section('title', localize('leave_type_list'))
@push('css')
@endpush
@section('content')

    @include('humanresource::leave_header')

    <div class="card mb-4 fixed-tab-body">
        @include('backend.layouts.common.validation')
        @include('backend.layouts.common.message')
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('leave_type_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_leave_type')
                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#create-leave-type"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('add_leave_type') }}</a>
                            @include('humanresource::leave.leave-type.modal.create')
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
                            <th width="5%">{{ localize('sl') }}</th>
                            <th width="25%">{{ localize('leave_type') }}</th>
                            <th width="20%">{{ localize('days') }}</th>
                            <th>{{ localize('action') }}</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dbData as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="text-capitalize">{{ $data->leave_type }}</td>
                                <td>{{ $data->leave_days }}</td>

                                <td>
                                    @can('update_leave_type')
                                        <a href="#" class="btn btn-primary-soft btn-sm me-1" data-bs-toggle="modal"
                                            data-bs-target="#update-leave-type-{{ $data->id }}" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                        @include('humanresource::leave.leave-type.modal.edit')
                                    @endcan
                                    @can('delete_leave_type')
                                        <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                            data-bs-toggle="tooltip" title="Delete"
                                            data-route="{{ route('leave-types.destroy', $data->uuid) }}"
                                            data-csrf="{{ csrf_token() }}"><i class="fa fa-trash"></i></a>
                                    @endcan
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

@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/hrcommon.js') }}"></script>
@endpush
