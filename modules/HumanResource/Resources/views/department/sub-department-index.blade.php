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
                        @can('create_sub_department')
                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#create-sub-department"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('add_sub_department') }}</a>
                            @include('humanresource::department.modal.sub-department-create')
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
                            <th width="10%">{{ localize('sl') }}</th>
                            <th width="15%">{{ localize('sub_department_name') }}</th>
                            <th width="10%">{{ localize('status') }}</th>
                            <th width="10%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departments as $key => $department)
                            <tr>
                                <td>#{{ $key + 1 }}</td>
                                <td class="ps-5">{{ $department->department_name }}</td>
                                <td>
                                    @if ($department->is_active == 1)
                                        <span class="badge bg-success">{{ localize('active') }}</span>
                                    @elseif($department->is_active == 0)
                                        <span class="badge bg-danger ">{{ localize('inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="#" class="btn btn-primary-soft btn-sm me-1" data-bs-toggle="modal"
                                        data-bs-target="#update-department-{{ $department->id }}" title="Edit"><i
                                            class="fa fa-edit"></i></a>

                                    <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                        data-bs-toggle="tooltip" title="Delete"
                                        data-route="{{ route('departments.destroy', $department->uuid) }}"
                                        data-csrf="{{ csrf_token() }}"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @include('humanresource::department.modal.sub-department-edit')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
