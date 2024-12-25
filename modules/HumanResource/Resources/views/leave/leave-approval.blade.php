@extends('backend.layouts.app')
@section('title', localize('leave_approval'))
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
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('leave_approval_list') }}</h6>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table display table-bordered table-striped table-hover">
                    <thead class="align-middle">
                        <tr>
                            <th width="5%">{{ localize('sl') }}</th>
                            <th width="10%">{{ localize('employee_name') }}</th>
                            <th width="7%">{{ localize('type') }}</th>
                            <th width="8%">{{ localize('apply_date') }}</th>
                            <th width="7%">{{ localize('leave_start_date') }}</th>
                            <th width="8%">{{ localize('leave_end_date') }}</th>
                            <th width="5%">{{ localize('days') }}</th>
                            <th width="7%">{{ localize('approved_date') }}</th>
                            <th width="8%">{{ localize('approved_start_date') }}</th>
                            <th width="8%">{{ localize('approved_end_date') }}</th>
                            <th width="5%">{{ localize('approved_days') }}</th>
                            <th width="7%">{{ localize('hard_copy') }}</th>
                            <th width="5%">{{ localize('status') }}</th>
                            <th width="10%">{{ localize('action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @forelse($leaves as $key => $leave)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td> {{ $leave->employee?->full_name }} </td>
                                <td class="text-capitalize">{{ $leave?->leaveType?->leave_type }}</td>
                                <td>{{ $leave->leave_apply_date }}</td>
                                <td>{{ $leave->leave_apply_start_date }}</td>
                                <td>{{ $leave->leave_apply_end_date }}</td>
                                <td>{{ $leave->total_apply_day }}</td>
                                <td>{{ $leave->leave_approved_date }}</td>
                                <td>{{ $leave->leave_approved_start_date }}</td>
                                <td>{{ $leave->leave_approved_end_date }}</td>
                                <td>{{ $leave->total_approved_day }}</td>
                                <td>
                                    <small>
                                        <img src="{{ asset('storage/' . $leave->location) }}" width="80"
                                            alt="">
                                    </small>
                                </td>

                                <td>
                                    @if ($leave->is_approved_by_manager == 1)
                                        <span class="badge bg-success">{{ localize('approved_by_manager') }}</span>
                                    @endif

                                    @if ($leave->is_approved == 1)
                                        <span class="badge bg-success">{{ localize('approved') }}</span>
                                    @elseif($leave->is_approved == 0)
                                        <span class="badge bg-danger ">{{ localize('pending') }}</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($leave->is_approved_by_manager == 0)
                                        @can('create_leave_approval')
                                            <a href="#" class="btn btn-success-soft btn-sm me-1" data-bs-toggle="modal"
                                                data-bs-target="#approvedapplication{{ $leave->id }}" title="Approve"><i
                                                    class="fa fa-check"></i></a>
                                            @include('humanresource::leave.modal.approveleave')
                                        @endcan
                                    @endif

                                    @can('delete_leave_approval')
                                        <a href="javascript:void(0)" class="btn btn-danger-soft btn-sm delete-confirm"
                                            data-bs-toggle="tooltip" title="Delete"
                                            data-route="{{ route('leave.destroy', $leave->uuid) }}"
                                            data-csrf="{{ csrf_token() }}"><i class="fa fa-trash"></i></a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center">{{ localize('empty_data') }}</td>
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
