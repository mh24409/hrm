<div class="table-responsive">
    <table class="table display table-bordered table-striped table-hover" id="staff-attendance-table">
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
                    <h4 class="text-center">{{ $employee?->full_name }}</h4>
                    <p class="text-center">Attendance History ( From {{ $fromDate }} To {{ $toDate }} )</p>
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
            <div class="row">
                <div class="d-flex justify-content-between">
                    <div>
                        <span class="col-form-label">{{ localize('position_name') }}
                            :&nbsp;</span>{{ $employee?->position?->position_name }}<br>
                        <span class="col-form-label">{{ localize('department_name') }}
                            :&nbsp;</span>{{ $employee?->department?->department_name }}<br>
                        <span class="col-form-label">{{ localize('employee_id') }} :&nbsp;</span>
                        {{ $employee?->employee_id }}
                    </div>
                </div>
            </div>
        </div>
        <br>
        <thead>
            <tr>
                <th width="5%">{{ localize('sl') }}</th>
                <th width="15%">{{ localize('date') }}</th>
                <th width="10%">{{ localize('in_time') }}</th>
                <th width="10%">{{ localize('out_time') }}</th>
                <th width="20%">{{ localize('total_wasting_time') }}</th>
                <th width="20%">{{ localize('total_worked_hour') }}</th>
                <th width="20%">{{ localize('net_hour') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $key => $logs)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ \Carbon\Carbon::parse($key)->format('Y-m-d') }}</td>
                    <td>
                        {{-- first $logs raw_time --}}
                        @if (isset($logs[0]))
                            {{ \Carbon\Carbon::parse($logs[0]->raw_time)->format('H:i:s') }}
                        @else
                            --
                        @endif
                    </td>
                    <td>
                        {{-- last $logs raw_time --}}
                        @if (isset($logs[count($logs) - 1]))
                            {{ \Carbon\Carbon::parse($logs[count($logs) - 1]->raw_time)->format('H:i:s') }}
                        @else
                            --
                        @endif
                    </td>
                    <td>
                        {{-- in array wastingTimeByDate by $key --}}
                        @if (isset($wastingTimeByDate[$key]))
                            {{ $wastingTimeByDate[$key] }}
                        @else
                            --
                        @endif
                    </td>
                    <td>
                        {{-- in array workHoursByDate by $key --}}
                        @if (isset($workHoursByDate[$key]))
                            {{ $workHoursByDate[$key] }}
                        @else
                            --
                        @endif
                    </td>
                    <td>
                        {{-- in array netWorkHoursByDate by $key --}}
                        @if (isset($netWorkHoursByDate[$key]))
                            {{ $netWorkHoursByDate[$key] }}
                        @else
                            --
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">{{ localize('empty_data') }}</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th class="text-end" colspan="4">{{ localize('total') }}</th>
                <th>{{ $totalWastingHours }}</th>
                <th>{{ $totalWorkHours }}</th>
                <th>{{ $totalNetWorkHours }}</th>
            </tr>
        </tfoot>
    </table>
</div>
