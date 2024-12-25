<div class="table-responsive">
    <table class="table display table-bordered table-striped table-hover" id="staff-attendance-table">
        <div>
            <h4 class="text-center">{{ $employee?->full_name }}</h4>
            <p class="text-center">Attendance History ( From {{ $fromDate }} To {{ $toDate }} )</p>
            <div class="d-flex justify-content-between">
                <div>
                    <span style="color: #000; font-weight: 700">{{ localize('position_name') }}
                        :&nbsp;</span>{{ $employee?->position?->position_name }}<br>
                    <span style="color: #000; font-weight: 700">{{ localize('department_name') }}
                        :&nbsp;</span>{{ $employee?->department?->department_name }}<br>
                    <span style="color: #000; font-weight: 700">{{ localize('employee_id') }} :&nbsp;</span>
                    {{ $employee?->employee_id }}
                </div>

            </div>
        </div>
        <br>
        <thead>
            <tr>
                <th width="5%">{{ localize('sl') }}</th>
                <th width="10%">{{ localize('date') }}</th>
                <th width="20%">{{ localize('time_in') }}</th>
                <th width="20%">{{ localize('time_out') }}</th>
                <th width="20%">{{ localize('late') }}</th>
                <th width="20%">{{ localize('status') }}</th>
            </tr>
        </thead>
        <tbody>

            @php
                $late_count = 0;
                $present_count = 0;
            @endphp


            @forelse($collection as $key => $attendance)
                @php
                    $lte = '';
                    if ($attendance->in_time) {
                        $in_time = \Carbon\Carbon::parse($attendance->in_time)->format('H:i:s');

                        $start_time = \Carbon\Carbon::parse($attendance->roaster);

                        $totalDuration = 0;

                        if ($in_time && $start_time) {
                            $present_count += 1;
                            $totalDuration = $start_time->diffInSeconds($in_time);
                        }

                        if ($start_time->format('H:i:s') < $in_time) {
                            $lte = gmdate('H:i:s', $totalDuration);
                            $late_count += 1;
                        }
                    } else {
                        $in_time = '';

                        $start_time = '';

                        $totalDuration = 0;
                    }

                @endphp
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}</td>
                    @if ($attendance->in_time && $attendance->out_time)
                        <td>{{ \Carbon\Carbon::parse($attendance->in_time)->format('H:i:s') }}</td>
                        <td>{{ \Carbon\Carbon::parse($attendance->out_time)->format('H:i:s') }}</td>
                        <td><span
                                class="badge badge-danger-soft sale-badge-ft-13">{{ $lte ? 'Late(' . $lte . ')' : null }}</span>
                        </td>
                    @else
                        <td>--</td>
                        <td>--</td>
                        <td>--</td>
                    @endif
                    @if ($attendance->status == 'Present')
                        <td><span class="badge badge-success-soft sale-badge-ft-13">{{ $attendance->status }}</span>
                        </td>
                    @elseif($attendance->status == 'Absent')
                        <td><span class="badge badge-danger-soft sale-badge-ft-13">{{ $attendance->status }}</span>
                        </td>
                    @elseif($attendance->status == 'Holiday')
                        <td><span class="badge badge-warning-soft sale-badge-ft-13">{{ $attendance->status }}</span>
                        </td>
                    @elseif($attendance->status == 'Weekend')
                        <td><span class="badge badge-warning-soft sale-badge-ft-13">{{ $attendance->status }}</span>
                        </td>
                    @endif

                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">{{ localize('empty_data') }}</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th class="text-end">{{ localize('present') }}</th>
                <th>{{ $present_count }}</th>
                <th class="text-end">{{ localize('late') }}</th>
                <th class="">{{ $late_count }}</th>
            </tr>

        </tfoot>
    </table>
</div>
