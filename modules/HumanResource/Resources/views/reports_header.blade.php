<div class="row  dashboard_heading mb-3">
    <div class="card fixed-tab col-12 col-md-12">
        <ul class="nav nav-tabs">
            @can('read_attendance_report')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.staff-attendance') ? 'active' : '' }}"
                        href="{{ route('reports.staff-attendance') }}">{{ localize('attendance_report') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.lateness-closing-attendance') ? 'active' : '' }}"
                        href="{{ route('reports.lateness-closing-attendance') }}">{{ localize('lateness_closing_attendance_report') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.attendance-log') || request()->routeIs('reports.attendance-log-details') ? 'active' : '' }}"
                        href="{{ route('reports.attendance-log') }}">{{ localize('attendance_log') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.daily-present') ? 'active' : '' }}"
                        href="{{ route('reports.daily-present') }}">{{ localize('daily_present') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.monthly') ? 'active' : '' }}"
                        href="{{ route('reports.monthly') }}">{{ localize('monthly') }}</a>
                </li>
            @endcan
            @can('read_leave_report')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('reports.leave') ? 'active' : '' }}"
                        href="{{ route('reports.leave') }}">{{ localize('leave_report') }}</a>
                </li>
            @endcan
            @can('read_employee_report')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('reports.employee') ? 'active' : '' }}"
                        href="{{ route('reports.employee') }}">
                        {{ localize('employee_report') }}
                    </a>
                </li>
            @endcan
            @can('read_employee_wise_attendance')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('reports.employee_wise_attendance_summery') ? 'active' : '' }}"
                        href="{{ route('reports.employee_wise_attendance_summery') }}">{{ localize('employee_wise_attendance') }}</a>
                </li>
            @endcan
            @can('read_adhoc_report')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('reports.adhoc-advance') ? 'active' : '' }}"
                        href="{{ route('reports.adhoc-advance') }}">{{ localize('adhoc_report') }}</a>
                </li>
            @endcan
        </ul>
    </div>
</div>
