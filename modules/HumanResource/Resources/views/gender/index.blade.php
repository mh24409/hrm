@extends('backend.layouts.app')
@section('content')
    <!--/.Content Header (Page header)-->
    <div class="body-content">
        @include('backend.layouts.common.validation')
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fs-17 fw-semi-bold mb-0">Gender</h6>
                    </div>
                    <div class="text-end">
                        <div class="actions">

                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#create-gender"><i class="fa fa-plus-circle"></i>&nbsp;Add Gender</a>
                            @include('humanresource::gender.modal.create')
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table display table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="20%">{{ localize('sl') }}</th>
                                <th width="40%">Name</th>
                                <th width="20%">Is Active</th>
                                <th width="20%">{{ localize('action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($genders as $key => $gender)
                                <tr>
                                    <td>#{{ $key + 1 }}</td>
                                    <td>{{ $gender->gender_name }}</td>
                                    <td>
                                        @if ($gender->is_active == 1)
                                            <span class="badge bg-success">active</span>
                                        @elseif($gender->is_active == 0)
                                            <span class="badge bg-danger ">inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-primary-soft btn-sm me-1" data-bs-toggle="modal"
                                            data-bs-target="#edit-gender-{{ $gender->id }}" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                            data-bs-toggle="tooltip" title="Delete"
                                            data-route="{{ route('genders.destroy', $gender->uuid) }}"
                                            data-csrf="{{ csrf_token() }}"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                @include('humanresource::gender.modal.edit')
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">{{ localize('empty_data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
    <!--/.body content-->
@endsection
