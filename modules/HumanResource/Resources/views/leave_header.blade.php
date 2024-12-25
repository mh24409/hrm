<div class="row  dashboard_heading mb-3">
    <div class="card fixed-tab col-12 col-md-12">
        <ul class="nav nav-tabs">
            @can('read_weekly_holiday')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('leave.weekleave') || request()->routeIs('leave.weekleave.edit') ? 'active' : '' }}"
                        href="{{ route('leave.weekleave') }}">{{ localize('weekly_holiday') }}</a>
                </li>
            @endcan
            @can('read_holiday')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('holiday.index') ? 'active' : '' }}"
                        href="{{ route('holiday.index') }}">{{ localize('holiday') }}</a>
                </li>
            @endcan
            @can('read_leave_type')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('leave.leaveTypeindex') ? 'active' : '' }}"
                        href="{{ route('leave.leaveTypeindex') }}">{{ localize('leave_type') }}</a>
                </li>
            @endcan
            <!-- @can('read_leave_type')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('leave.leaveGenerate') || request()->routeIs('leave.generateLeaveDetail') ? 'active' : '' }}"
                        href="{{ route('leave.leaveGenerate') }}">{{ localize('leave_generate') }}</a>
                </li>
            @endcan -->
            @can('read_leave_approval')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('leave.approval') ? 'active' : '' }}"
                        href="{{ route('leave.approval') }}">{{ localize('leave_approval') }}</a>
                </li>
            @endcan
            @can('read_leave_application')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('leave.index') ? 'active' : '' }}"
                        href="{{ route('leave.index') }}">{{ localize('leave_application') }}</a>
                </li>
            @endcan
        </ul>
    </div>
</div>
