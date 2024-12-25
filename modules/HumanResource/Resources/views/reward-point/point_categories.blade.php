@extends('backend.layouts.app')
@section('title', localize('point_categories'))
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('point_categories') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_point_categories')
                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addPointCategory"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('new_category') }}</a>
                        @endcan

                        @include('humanresource::reward-point.create_point_category')
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
                            <th width="25%">{{ localize('category_name') }}</th>
                            <th width="15%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dbData as $key => $data)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $data->point_category }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary-soft btn-sm me-1" data-bs-toggle="modal"
                                        data-bs-target="#editPointCategory{{ $data->id }}" title="Edit"><i
                                            class="fa fa-edit"></i></a>

                                    <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                        data-bs-toggle="tooltip" title="Delete"
                                        data-route="{{ route('reward.destroy', $data->uuid) }}"
                                        data-csrf="{{ csrf_token() }}"><i class="fa fa-trash"></i></a>

                                </td>
                            </tr>
                            @include('humanresource::reward-point.edit_point_category')
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
