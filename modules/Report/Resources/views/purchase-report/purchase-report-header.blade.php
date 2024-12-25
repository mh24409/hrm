<div class="card mb-3">
    <div class="fixed-tab card-header bg-white py-3 pl-0">
        <ul class="nav nav-tabs">
            @can('read_purchases_summary')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.purchases_summary_report') ? 'active' : '' }}"
                        href="{{ route('report.purchases_summary_report') }}">{{ localize('purchases_summary') }}</a>
                </li>
            @endcan
            @can('read_purchase_details')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.purchase-report') ? 'active' : '' }}"
                        href="{{ route('report.purchase-report') }}">{{ localize('purchase_details') }}</a>
                </li>
            @endcan
            @can('read_purchase_details')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.receive-report') ? 'active' : '' }}"
                        href="{{ route('report.receive-report') }}">{{ localize('received_details') }}</a>
                </li>
            @endcan
            @can('read_category_wise_purchase')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.category_wise_purchase_report') ? 'active' : '' }}"
                        href="{{ route('report.category_wise_purchase_report') }}">{{ localize('category_wise_purchase') }}</a>
                </li>
            @endcan
        </ul>
    </div>
</div>
