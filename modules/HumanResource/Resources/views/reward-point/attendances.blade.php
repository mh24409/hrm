@extends('backend.layouts.app')
@section('title', localize('attendance_points'))
@section('content')
    @include('backend.layouts.common.validation')
    @include('backend.layouts.common.message')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('attendance_points') }}</h6>
                </div>
                <div class="text-end">
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
                            <th width="25%">{{ localize('in_time') }}</th>
                            <th width="25%">{{ localize('points') }}</th>
                            <th width="25%">{{ localize('date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dbData as $key => $data)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $data->employee->full_name }}</td>
                                <td>{{ $data->in_time }}</td>
                                <td>{{ $data->point }}</td>
                                <td>{{ date('Y-m-d', strtotime($data->created_at)) }}</td>
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
