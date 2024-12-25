@extends('backend.layouts.app')
@section('title', localize('leave_generate'))
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
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('leave_type_year') }}</h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table display table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>{{ localize('sl') }}</th>
                            <th>{{ localize('employee_name') }}</th>
                            <th>{{ localize('leave_type') }}</th>
                            <th>{{ localize('total_allocation_leave') }}</th>
                            <th>{{ localize('total_taken_leave') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dbData as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    {{ $data?->employee?->full_name }}
                                    {{ $data?->employee?->middle_name }}
                                    {{ $data?->employee?->last_name }}
                                </td>

                                <td class="text-capitalize">{{ $data?->leaveType?->leave_type }}</td>
                                <td class="text-capitalize">{{ $data?->entitled }}</td>
                                <td class="text-capitalize">{{ $data?->taken }}</td>

                            </tr>

                        @empty
                            <tr>
                                <td class="text-center" colspan="5">{{ localize('empty_data') }}</td>
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
