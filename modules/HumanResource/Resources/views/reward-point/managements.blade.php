@extends('backend.layouts.app')
@section('title', localize('management_points'))
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('management_points') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_management_points')
                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addMgmntPoint"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('add_management_point') }}</a>
                        @endcan

                        @include('humanresource::reward-point.create_management_point')
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
                            <th width="25%">{{ localize('employee') }}</th>
                            <th width="25%">{{ localize('point_category') }}</th>
                            <th width="25%">{{ localize('point') }}</th>
                            <th width="15%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dbData as $key => $data)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $data->employee->full_name }}</td>
                                <td>{{ $data->pointCategory->point_category }}</td>
                                <td>{{ $data->point }}</td>
                                <td>
                                @can('delete_management_points')
                                    <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                            data-bs-toggle="tooltip" title="Delete"
                                            data-route="{{ route('reward.point-management-delete', $data->id) }}"
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
