<div class="row  dashboard_heading mb-3">
    <div class="card fixed-tab col-12 col-md-12">
        <ul class="nav nav-tabs">
            @can('read_loan')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('hr.loans.index') || request()->routeIs('hr.loan.edit') ? 'active' : '' }}"
                        href="{{ route('hr.loans.index') }}">{{ localize('loan') }}</a>
                </li>
            @endcan
            @can('read_loan_disburse_report')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('hr.loans.loan_disburse_report') ? 'active' : '' }}"
                        href="{{ route('hr.loans.loan_disburse_report') }}">{{ localize('loan_disburse_report') }}</a>
                </li>
            @endcan

            @can('read_employee_wise_loan')
                <li class="nav-item">
                    <a class="nav-link mt-0 {{ request()->routeIs('hr.loans.employee') ? 'active' : '' }}"
                        href="{{ route('hr.loans.employee') }}">{{ localize('employee_wise_loan') }}</a>
                </li>
            @endcan
        </ul>
    </div>
</div>
