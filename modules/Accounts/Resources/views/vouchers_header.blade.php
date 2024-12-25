<div class="card mb-3">
    <div class="fixed-tab card-header bg-white py-3 pl-0">
        <ul class="nav nav-tabs">
            @can('read_debit_voucher')
                <li class="nav-item">
                    <a class="nav-link py-1 pl-0 {{ request()->routeIs('debit-vouchers.index') || request()->routeIs('debit-vouchers.edit') || request()->routeIs('debit-vouchers.create') ? 'active' : '' }}"
                        href="{{ route('debit-vouchers.index') }}">{{ localize('debit_voucher') }}</a>
                </li>
            @endcan
            @can('read_credit_voucher')
                <li class="nav-item">
                    <a class="nav-link mt-0 py-1  {{ request()->routeIs('credit-vouchers.index') || request()->routeIs('credit-vouchers.edit') || request()->routeIs('credit-vouchers.create') ? 'active' : '' }}"
                        href="{{ route('credit-vouchers.index') }}">{{ localize('credit_voucher') }}</a>
                </li>
            @endcan
            @can('read_contra_voucher')
                <li class="nav-item">
                    <a class="nav-link mt-0 py-1  {{ request()->routeIs('contra-vouchers.index') || request()->routeIs('contra-vouchers.edit') || request()->routeIs('contra-vouchers.create') ? 'active' : '' }}"
                        href="{{ route('contra-vouchers.index') }}">{{ localize('contra_voucher') }}</a>
                </li>
            @endcan
            @can('read_journal_voucher')
                <li class="nav-item">
                    <a class="nav-link mt-0 py-1 {{ request()->routeIs('journal-vouchers.index') || request()->routeIs('journal-vouchers.edit') || request()->routeIs('journal-vouchers.create') ? 'active' : '' }}"
                        href="{{ route('journal-vouchers.index') }}">{{ localize('journal_voucher') }}</a>
                </li>
            @endcan
        </ul>
    </div>
</div>
