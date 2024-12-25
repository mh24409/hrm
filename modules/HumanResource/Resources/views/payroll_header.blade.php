<div class="row  dashboard_heading mb-3">
    <div class="card fixed-tab col-12 col-md-12">
        <ul class="nav nav-tabs">
            @can('read_salary_advance')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('salary-advance.index') ? 'active' : '' }}"
                        href="{{ route('salary-advance.index') }}">{{ localize('salary') }}
                        {{ localize('advance') }}</a>
                </li>
            @endcan
            @can('read_salary_generate')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('salary.generate-form') || request()->routeIs('salary.chart') || request()->routeIs('salary.approval-form') || request()->routeIs('salary.approval') ? 'active' : '' }}"
                        href="{{ route('salary.generate-form') }}">{{ localize('salary') }}
                        {{ localize('generate') }}</a>
                </li>
            @endcan

            @can('read_manage_employee_salary')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('employee.salary') || request()->routeIs('employee.payslip') ? 'active' : '' }}"
                        href="{{ route('employee.salary') }}">{{ localize('manage_employee_salary') }}</a>
                </li>
            @endcan
        </ul>
    </div>
</div>
