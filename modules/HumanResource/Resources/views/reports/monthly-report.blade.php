<div class="table-responsive">
    <table class="table display table-bordered table-striped table-hover" id="monthly-present-report-table">
        <thead>
            <tr>
                <th width="5%">{{ localize('sl') }}</th>
                <th width="10%">{{ localize('employee_id') }}</th>
                <th width="10%">{{ localize('name') }}</th>
                <th width="10%">{{ localize('department') }}</th>
                <th width="10%">{{ localize('date') }}</th>
                <th width="20%">{{ localize('time_in') }}</th>
                <th width="20%">{{ localize('time_out') }}</th>
                <th width="20%">{{ localize('status') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($collection as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->employee_id }}</td>
                    <td>{{ $item->employee_name }}</td>
                    <td>{{ $item->department_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->date)->format('Y-m-d') }}</td>
                    <td>{{ $item->count > 0 ? \Carbon\Carbon::parse($item->in_time)->format('H:i:s') : '--' }}</td>
                    <td>{{ $item->count > 1 ? \Carbon\Carbon::parse($item->out_time)->format('H:i:s') : '--' }}</td>
                    @if ($item->status == 'Present')
                        <td><span class="badge badge-success-soft sale-badge-ft-13">{{ $item->status }}</span>
                        </td>
                    @elseif($item->status == 'Absent')
                        <td><span class="badge badge-danger-soft sale-badge-ft-13">{{ $item->status }}</span>
                        </td>
                    @elseif($item->status == 'Holiday')
                        <td><span class="badge badge-warning-soft sale-badge-ft-13">{{ $item->status }}</span>
                        </td>
                    @elseif($item->status == 'Weekend')
                        <td><span class="badge badge-warning-soft sale-badge-ft-13">{{ $item->status }}</span>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">{{ localize('empty_data') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
