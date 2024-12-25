@extends('backend.layouts.app')
@section('title', localize('attendance_summary_report'))
@push('css')
@endpush
@section('content')
    @include('humanresource::reports_header')
    @include('backend.layouts.common.validation')

    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('attendance_summary_report') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="true" aria-controls="flush-collapseOne"> <i
                                class="fas fa-filter"></i> {{ localize('filter') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <div id="flush-collapseOne" class="accordion-collapse collapse bg-white mb-4 show"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="row">
                                    <div class="col-md-2 mb-4">
                                        <div class="form-group mx-0 row">
                                            <div class="col-md-12 pe-0">
                                                <select name="employee_id" id="employee_id"
                                                    class="form-control select-basic-single">
                                                    <option value="" selected disabled>
                                                        {{ localize('select_employee') }}</option>
                                                    @foreach ($employees as $key => $employee)
                                                        <option value="{{ $employee->id }}" @selected(request()->employee_id == $employee->id)>
                                                            {{ $employee->full_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-4">
                                        <input type="text" class="form-control" name="text" id="date-range"
                                            placeholder="{{ localize('date') }}" value="{{ request()->date }}" />
                                    </div>

                                    <div class="col-md-2 mb-4">
                                        <button type="button" id="filter"
                                            class="btn btn-success">{{ localize('find') }}</button>
                                        <button type="button" id="searchreset"
                                            class="btn btn-danger">{{ localize('reset') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="table-responsive">
                    @foreach ($dayWiseAttendancesPaginated as $key => $employeeAttendances)
                        <table class="w-100 table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th colspan="7" class="text-center">
                                        {{ localize('Attendance History of ') }}
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d', $key)->format('F d, Y') }}
                                    </th>
                                </tr>
                                <tr>
                                    <th>{{ localize('sl') }}</th>
                                    <th>{{ localize('employee_name') }}</th>
                                    <th>{{ localize('in_time') }}</th>
                                    <th>{{ localize('out_time') }}</th>
                                    <th>{{ localize('worked_hours') }}</th>
                                    <th>{{ localize('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employeeAttendances as $item)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ $item->employee->full_name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->in_time)->format('H:i:s') }}</td>
                                        <td>{{ $item->count > 1 ? \Carbon\Carbon::parse($item->out_time)->format('H:i:s') : '--' }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->out_time)->diff(\Carbon\Carbon::parse($item->in_time))->format('%H:%I:%S') }}
                                        </td>
                                        <td>
                                            <a href="{{ route('reports.attendance-log-details', $item->employee_id) }}"
                                                class="btn btn-primary btn-sm"><i class="fa fa-eye"></i>
                                                {{ localize('details') }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="justify-content-between">
                        {{ $dayWiseAttendances->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="request_date" value="{{ request()->date }}">
        <input type="hidden" id="report_attendance_logs" value="{{ route('reports.attendance-log') }}">

    </div>

@endsection
@push('js')

    <script src="{{ module_asset('HumanResource/js/attendance-logs.js') }}"></script>
@endpush
