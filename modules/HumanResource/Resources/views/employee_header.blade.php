<div class="row  dashboard_heading mb-3">
    <div class="card fixed-tab col-12 col-md-12">
        <ul class="nav nav-tabs">
            @can('read_employee')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('employees.index') || request()->routeIs('employees.edit') || request()->routeIs('employees.create') || request()->routeIs('employees.show') ? 'active' : '' }}"
                        href="{{ route('employees.index') }}">{{ localize('employee') }}</a>
                </li>
            @endcan

            @can('read_positions')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('positions.index') ? 'active' : '' }}"
                        href="{{ route('positions.index') }}">{{ localize('positions') }}</a>
                </li>
            @endcan
            @can('read_inactive_employees_list')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('employees.inactive_list') ? 'active' : '' }}"
                        href="{{ route('employees.inactive_list') }}">{{ localize('inactive_employees_list') }}</a>
                </li>
            @endcan
        </ul>
    </div>
</div>
