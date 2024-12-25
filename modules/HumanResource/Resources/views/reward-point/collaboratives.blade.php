@extends('backend.layouts.app')
@section('title', @localize('collaborative_points'))
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('collaborative_points') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        @can('create_collaborative_points')
                            <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCollaborativePoint"><i
                                    class="fa fa-plus-circle"></i>&nbsp;{{ localize('add_collaborative_point') }}</a>
                        @endcan

                        @include('humanresource::reward-point.create_collaborative_point')
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
                            <th width="25%">{{ localize('point_share_with') }}</th>
                            <th width="25%">{{ localize('point') }}</th>
                            <th width="25%">{{ localize('point_date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dbData as $key => $data)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $data->pointReceiveEmployee->full_name }}</td>
                                <td>{{ $data->point }}</td>
                                <td>{{ $data->point_date }}</td>
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
