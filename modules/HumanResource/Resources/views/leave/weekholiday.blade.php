@extends('backend.layouts.app')
@section('title', localize('weekly_holiday'))
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
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('weekly_holiday') }}</h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table display table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ localize('sl') }}</th>
                            <th>{{ localize('day_name') }}</th>
                            <th>{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dbData as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="text-capitalize">{{ $data?->dayname }}</td>
                                <td>
                                    @can('edit_weekly_holiday')
                                        <a href="{{ route('leave.weekleave.edit', $data->uuid) }}"
                                            class="btn btn-primary-soft btn-sm me-1" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center">{{ localize('empty_data') }}</td>
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
