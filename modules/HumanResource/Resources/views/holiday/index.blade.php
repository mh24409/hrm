@extends('backend.layouts.app')
@section('title', localize('holiday'))
@section('content')
    @include('humanresource::leave_header')
    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            @include('backend.layouts.common.validation')
            @include('backend.layouts.common.message')
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('holiday_list') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addHoliday"><i
                                class="fa fa-plus-circle"></i>&nbsp;{{ localize('add_holiday') }}</a>
                        @include('humanresource::holiday.create')
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
                            <th width="25%">{{ localize('holiday_name') }}</th>
                            <th width="20%">{{ localize('from_date') }}</th>
                            <th width="15%">{{ localize('to_date') }}</th>
                            <th width="15%">{{ localize('total_days') }}</th>
                            <th width="15%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dbData as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->holiday_name }}</td>
                                <td>{{ $data->start_date }}</td>
                                <td>{{ $data->end_date }}</td>
                                <td>{{ $data->total_day }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary-soft btn-sm me-1" data-bs-toggle="modal"
                                        data-bs-target="#editHoliday{{ $data->id }}" title="Edit"><i
                                            class="fa fa-edit"></i></a>

                                    <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                        data-bs-toggle="tooltip" title="Delete"
                                        data-route="{{ route('holiday.destroy', $data->uuid) }}"
                                        data-csrf="{{ csrf_token() }}"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @include('humanresource::holiday.edit')
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
