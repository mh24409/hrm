@extends('backend.layouts.app')
@section('title', localize('attendance_details_report'))
@push('css')
@endpush
@section('content')
    @include('humanresource::reports_header')
    @include('backend.layouts.common.validation')

    <div class="card mb-4 fixed-tab-body">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fs-17 fw-semi-bold mb-0">{{ localize('attendance_details_report') }}</h6>
                </div>
                <div class="text-end">
                    {{-- <div class="actions">
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="true" aria-controls="flush-collapseOne"> <i
                                class="fas fa-filter"></i> {{ localize('filter') }}</button>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                <h3 class="text-center">
                    {{ $employee->full_name }}
                </h3>
                <div class="table-responsive">
                    @foreach ($attendancesPaginated as $key => $attendanceGroup)
                        <caption>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $key)->format('F d, Y') }}</caption>
                        <table class="w-100 table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{{ localize('sl') }}</th>
                                    <th>{{ localize('punch_time') }}</th>
                                    <th>{{ localize('status') }}</th>
                                    <th>{{ localize('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    // Initialize total wasting time
                                    $totalWastingTime = 0;

                                    // Iterate over the logs
                                    for ($i = 1; $i < count($attendanceGroup) - 1; $i += 2) {
                                        try {
                                            // Attempt to parse the time strings, handle potential errors
                                            $outTime = Carbon\Carbon::parse($attendanceGroup[$i]->time);
                                            $inTime = Carbon\Carbon::parse($attendanceGroup[$i + 1]->time);

                                            // Process the timestamps if parsing is successful (assuming you still need to calculate wasting time)
                                            $wastingTime = $outTime->diffInSeconds($inTime);
                                            $totalWastingTime += $wastingTime;

                                        } catch (InvalidArgumentException $e) {
                                            // Handle parsing errors gracefully, e.g., log the issue or skip invalid entries
                                            return;
                                        }
                                    }
                                    $duration = \Carbon\CarbonInterval::seconds($totalWastingTime)->cascade();

                                    // Format the duration
                                    $formattedDuration = $duration->format('%H hours %I minutes %S seconds');
                                @endphp
                                @foreach ($attendanceGroup as $item)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <td>{{ \Carbon\Carbon::parse($item->time)->format('H:i:s') }}</td>
                                        <td>{{ $loop->iteration % 2 == 0 ? localize('out') : localize('in') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('attendances.edit', $item->id) }}"
                                                    class="btn btn-primary btn-sm me-2"><i class="fa fa-pencil"></i></a>
                                                <button class="btn btn-danger btn-sm attn_delete"
                                                    data-url="{{ route('attendances.destroy', $item->id) }}"
                                                    type="button"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4">
                                        {{ localize('N.B : You Spent Hours out of Working hours') }}
                                        {{ $formattedDuration }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    @endforeach
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="justify-content-between">
                        {{ $attendancesPaginated->appends(request()->query())->withPath(route('reports.attendance-log-details', $employee->id))->links() }}
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="request_date" value="{{ request()->date }}">
        <input type="hidden" id="reports_attendance_log" value="{{ route('reports.attendance-log') }}">

    </div>

@endsection
@push('js')

    <script src="{{ module_asset('HumanResource/js/attendance-logs-details.js') }}"></script>
@endpush
