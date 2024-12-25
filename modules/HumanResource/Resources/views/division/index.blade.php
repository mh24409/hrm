@extends('backend.layouts.app')
@section('title', localize('sub_department_list'))
@section('content')
    @include('backend.layouts.common.validation')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('sub_department_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_sub_departments')
                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#create-division"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('add_sub_department') }}</a>
                            @include('humanresource::division.modal.create')
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            {{-- <div class="table-responsive">
                <table id="example" class="table display table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="10%">{{ localize('sl') }}</th>
                            <th width="15%">{{ localize('sub_department_name') }}</th>
                            <th width="15%">{{ localize('department') }}</th>
                            <th width="10%">{{ localize('status') }}</th>
                            <th width="10%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($divisions as $key => $division)
                            <tr>
                                <td>#{{ $key + 1 }}</td>
                                <td>{{ $division->department_name }}</td>
                                <td>{{ $division->parentDept() ? $division->parentDept()->department_name : 'Not Found' }}
                                </td>
                                <td>
                                    @if ($division->is_active == 1)
                                        <span class="badge bg-success">{{ localize('active') }}</span>
                                    @elseif($division->is_active == 0)
                                        <span class="badge bg-danger ">{{ localize('inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @can('update_sub_departments')
                                        <a href="#" class="btn btn-primary-soft btn-sm me-1" data-bs-toggle="modal"
                                            data-bs-target="#update-division-{{ $division->id }}" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                        @include('humanresource::division.modal.edit')
                                    @endcan
                                    @can('delete_sub_departments')
                                        <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                            data-bs-toggle="tooltip" title="Delete"
                                            data-route="{{ route('divisions.destroy', $division->uuid) }}"
                                            data-csrf="{{ csrf_token() }}"><i class="fa fa-trash"></i></a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> --}}
            {{ $dataTable->table() }}
        </div>
    </div>
    <!-- Edit Modal -->
    <div class="modal fade" id="edit-sub-department" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
    <script src="{{ module_asset('HumanResource/js/sub-department.js') }}"></script>
@endpush
