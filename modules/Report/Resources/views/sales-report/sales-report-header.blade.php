<div class="card mb-3">
    <div class="fixed-tab card-header bg-white py-3 pl-0">
        <ul class="nav nav-tabs">
            @can('read_sales_report')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.sales-report') ? 'active' : '' }}"
                        href="{{ route('report.sales-report') }}">{{ localize('sales_report') }}</a>
                </li>
            @endcan
            @can('read_cash_register_report')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.cash_register_report') ? 'active' : '' }}"
                        href="{{ route('report.cash_register_report') }}">{{ localize('cash_register') }}</a>
                </li>
            @endcan
            @can('read_sale_cashier')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.sale-report-casher') ? 'active' : '' }}"
                        href="{{ route('report.sale-report-casher') }}">{{ localize('sale_cashier') }}</a>
                </li>
            @endcan
            @can('read_user_wise_sales_report')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.userwise-sales-report') ? 'active' : '' }}"
                        href="{{ route('report.userwise-sales-report') }}">{{ localize('user_wise_sales') }}</a>
                </li>
            @endcan
            @can('read_day_wise_sales_report')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.daywise-sales-report') ? 'active' : '' }}"
                        href="{{ route('report.daywise-sales-report') }}">{{ localize('day_wise_sales') }}</a>
                </li>
            @endcan
            @can('read_sales_due_report')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.sales-due-report') ? 'active' : '' }}"
                        href="{{ route('report.sales-due-report') }}">{{ localize('sales_due') }}</a>
                </li>
            @endcan
            @can('read_sales_return')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.sales-return') ? 'active' : '' }}"
                        href="{{ route('report.sales-return') }}">{{ localize('sales_return') }}</a>
                </li>
            @endcan
            @can('read_undelivered_sales_report')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.undelivered-sale-report') ? 'active' : '' }}"
                        href="{{ route('report.undelivered-sale-report') }}">{{ localize('undelivered_sales') }}</a>
                </li>
            @endcan
            @can('read_supplier_wise_sale_profit')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.supplier-wise-sale-profit-report') ? 'active' : '' }}"
                        href="{{ route('report.supplier-wise-sale-profit-report') }}">{{ localize('supplier_wise_sale_profit') }}</a>
                </li>
            @endcan
            @can('read_category_wise_sale_report')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.category_wise_sale_report') ? 'active' : '' }}"
                        href="{{ route('report.category_wise_sale_report') }}">{{ localize('category_wise_sale') }}</a>
                </li>
            @endcan
        </ul>
    </div>
</div>
