<div class="card mb-3">
    <div class="fixed-tab card-header bg-white py-3 pl-0">
        <ul class="nav nav-tabs">
            @can('read_warehouse_wise_report')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.warehouse_wise_report') ? 'active' : '' }}"
                        href="{{ route('report.warehouse_wise_report') }}">{{ localize('warehouse_wise_report') }}</a>
                </li>
            @endcan
            @can('read_warehouse_wise_product')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('report.warehouse_wise_product_report') ? 'active' : '' }}"
                        href="{{ route('report.warehouse_wise_product_report') }}">{{ localize('warehouse_wise_product') }}</a>
                </li>
            @endcan
        </ul>
    </div>
</div>
