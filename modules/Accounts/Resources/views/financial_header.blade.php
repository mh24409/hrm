<div class="card mb-3">
    <div class="fixed-tab card-header bg-white py-3 pl-0">
        <ul class="nav nav-tabs">
            @can('read_financial_year')
                <li class="nav-item">
                    <a class="nav-link py-1 pl-0 {{ request()->routeIs('financial-years.index') ? 'active' : '' }}"
                        href="{{ route('financial-years.index') }}">{{ localize('financial_year') }}</a>
                </li>
            @endcan
            @can('read_quarter')
                <li class="nav-item">
                    <a class="nav-link mt-0 py-1 {{ request()->routeIs('quarters.index') ? 'active' : '' }}"
                        href="{{ route('quarters.index') }}">{{ localize('quarter') }}</a>
                </li>
            @endcan
        </ul>
    </div>
</div>
