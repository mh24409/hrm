@extends('backend.layouts.app')
@section('title', localize('detail_attendance_report'))
@push('css')
    <link href="{{ module_asset('HumanResource/css/attendance-details-print.css') }}" rel="stylesheet">
@endpush
@section('content')
    @include('humanresource::reports_header')
    @include('backend.layouts.common.validation')

    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('daily_attendance_report') }}</h6>
                </div>
                <div class="text-end">
                    <div class="actions">
                        <button class="btn btn-success no-print btn-sm" onclick="printDetails();"><i
                                class="fas fa-print"></i>&nbsp;{{ localize('print') }}</button>
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne"> <i
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
                            <div id="flush-collapseOne" class="accordion-collapse collapse bg-white mb-4"
                                aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">


                                <form class="row g-3" action="" method="GET">
                                    <div class="col-md-2">
                                        <input type="text" class="form-control date_picker" name="date"
                                            placeholder="Date" id="date" value="{{ $date }}">
                                    </div>

                                    <div class="col-md-2">
                                        <button type="submit" id="filter"
                                            class="btn btn-success">{{ localize('find') }}</button>
                                        <button type="button" id="searchreset"
                                            class="btn btn-danger">{{ localize('reset') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="table-responsive" id="print-table">
                <table class="table display table-bordered table-striped table-hover" id="staff-attendance-detail-table">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 col-6">
                                <div class="fs-10 text-start pb-3">
                                    {{ localize('print_date') }}: {{ \Carbon\Carbon::now()->format('d-m-Y h:i:sa') }} ,
                                    {{ localize('user') }}:
                                    {{ auth()->user()->full_name }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <img class="img-fluid" src="{{ app_setting()->logo }}">
                            </div>
                            <div class="col text-center">
                                <h5 class="text-center">{{ ucwords($employee?->full_name) }}</h5>
                                <div class="text-center fs-14">
                                    <span style="color: #000; font-weight: 700">{{ localize('position_name') }}
                                        :&nbsp;</span>{{ $employee?->position?->position_name }}<br>
                                    <span style="color: #000; font-weight: 700">{{ localize('department_name') }}
                                        :&nbsp;</span>{{ $employee?->department?->department_name }}<br>
                                    <span style="color: #000; font-weight: 700">{{ localize('employee_id') }}
                                        :&nbsp;</span> {{ $employee?->employee_id }} <br>
                                    {{ \Carbon\Carbon::parse($date)->format('d M, Y') }}
                                </div>
                            </div>
                            <div class="col text-end">
                                @php
                                    $len = strlen(app_setting()->address);
                                    $space = strrpos(app_setting()->address, ' ', -$len / 2);
                                    $col1 = substr(app_setting()->address, 0, $space);
                                    $col2 = substr(app_setting()->address, $space);
                                @endphp
                                <div class="fs-14">{{ $col1 }} <br> {{ $col2 }}</div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <thead>
                        <tr>
                            <th width="5%">{{ localize('sl') }}</th>
                            <th width="20%">{{ localize('device_id') }}</th>
                            <th width="10%">{{ localize('date') }}</th>
                            <th width="20%">{{ localize('punch_time') }}</th>
                            <th width="20%">{{ localize('status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $key => $attendance)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $attendance->machine_id }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->time)->format('Y-m-d') }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->time)->format('H:i:s') }}</td>
                                <td>
                                    @if ($key % 2 == 0)
                                        {{ localize('in') }}
                                    @else
                                        {{ localize('out') }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ localize('empty_data') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
@push('js')
    <script src="{{ module_asset('HumanResource/js/report-filter.js') }}"></script>
@endpush
