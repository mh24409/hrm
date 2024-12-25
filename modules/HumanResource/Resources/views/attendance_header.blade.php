<div class="row  dashboard_heading mb-3">
    <div class="card fixed-tab col-12 col-md-12">
        <ul class="nav nav-tabs">
            @can('read_attendance')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('attendances.create') ? 'active' : '' }}"
                        href="{{ route('attendances.create') }}">{{ localize('attendance_form') }}</a>
                </li>
            @endcan
            @can('read_monthly_attendance')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('attendances.monthlyCreate') ? 'active' : '' }}"
                        href="{{ route('attendances.monthlyCreate') }}">{{ localize('monthly_attendance') }}</a>
                </li>
            @endcan
            @can('read_missing_attendance')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('attendances.missingAttendance') ? 'active' : '' }}"
                        href="{{ route('attendances.missingAttendance') }}">{{ localize('missing_attendance') }}</a>
                </li>
            @endcan
        </ul>
    </div>
</div>
