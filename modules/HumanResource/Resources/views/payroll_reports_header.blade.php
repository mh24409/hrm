<div class="row  dashboard_heading mb-3">
    <div class="card fixed-tab col-12 col-md-12">
        <ul class="nav nav-tabs">
            @can('read_payroll_report')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.npf3-soc-sec-tax-report') ? 'active' : '' }}"
                        href="{{ route('reports.npf3-soc-sec-tax-report') }}">{{ localize('npf3_soc_sec_tax_report') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.iicf3-contribution') ? 'active' : '' }}"
                        href="{{ route('reports.iicf3-contribution') }}">{{ localize('iicf3_contribution') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.social-security-npf-icf') ? 'active' : '' }}"
                        href="{{ route('reports.social-security-npf-icf') }}">{{ localize('social_security_npf_icf') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.gra-ret-5-report') ? 'active' : '' }}"
                        href="{{ route('reports.gra-ret-5-report') }}">{{ localize('gra_ret_5') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.sate-income-tax') ? 'active' : '' }}"
                        href="{{ route('reports.sate-income-tax') }}">{{ localize('sate_income_tax_report') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.salary-confirmation-form') ? 'active' : '' }}"
                        href="{{ route('reports.salary-confirmation-form') }}">{{ localize('salary_confirmation_form') }}</a>
                </li>

            @endcan
        </ul>
    </div>
</div>
