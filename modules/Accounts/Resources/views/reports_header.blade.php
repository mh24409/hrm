<div class="card mb-3">
    <div class="fixed-tab card-header bg-white py-3 pl-0">
        <ul class="nav nav-tabs">
            @can('read_cash_book')
                <li class="nav-item">
                    <a class="nav-link py-1  pl-0 {{ request()->routeIs('reports.cashbook') || request()->routeIs('reports.cashbookGenerate') ? 'active' : '' }}"
                        href="{{ route('reports.cashbook') }}">{{ localize('cash_book') }}</a>
                </li>
            @endcan
            @can('read_bank_book')
                <li class="nav-item">
                    <a class="nav-link py-1 mt-0  {{ request()->routeIs('reports.bankbook') || request()->routeIs('reports.bankbookGenerate') ? 'active' : '' }}"
                        href="{{ route('reports.bankbook') }}">{{ localize('bank_book') }}</a>
                </li>
            @endcan
            @can('read_day_book')
                <li class="nav-item">
                    <a class="nav-link py-1 mt-0  {{ request()->routeIs('reports.daybook') || request()->routeIs('reports.daybookGenerate') ? 'active' : '' }}"
                        href="{{ route('reports.daybook') }}">{{ localize('day_book') }}</a>
                </li>
            @endcan
            @can('read_general_ledger')
                <li class="nav-item">
                    <a class="nav-link py-1 mt-0 {{ request()->routeIs('reports.ledgergeneral') || request()->routeIs('reports.ledgergeneralGenerate') ? 'active' : '' }}"
                        href="{{ route('reports.ledgergeneral') }}">{{ localize('general_ledger') }}</a>
                </li>
            @endcan
            @can('read_sub_ledger')
                <li class="nav-item">
                    <a class="nav-link py-1 mt-0 {{ request()->routeIs('reports.subledger') || request()->routeIs('reports.subledgerGenerate') ? 'active' : '' }}"
                        href="{{ route('reports.subledger') }}">{{ localize('sub_ledger') }}</a>
                </li>
            @endcan
            @can('read_control_ledger')
                <li class="nav-item">
                    <a class="nav-link py-1 mt-0 {{ request()->routeIs('reports.controlLedger') || request()->routeIs('reports.controlLedger') ? 'active' : '' }}"
                        href="{{ route('reports.controlLedger') }}">{{ localize('control_ledger') }}</a>
                </li>
            @endcan
            @can('read_note_ledger')
                <li class="nav-item">
                    <a class="nav-link py-1 mt-0 {{ request()->routeIs('reports.noteLedger') || request()->routeIs('reports.noteLedger') ? 'active' : '' }}"
                        href="{{ route('reports.noteLedger') }}">{{ localize('note_ledger') }}</a>
                </li>
            @endcan
            @can('read_receipt_payment')
                <li class="nav-item">
                    <a class="nav-link py-1 mt-0 {{ request()->routeIs('reports.receiptpayment') || request()->routeIs('reports.receiptpaymentGenerate') ? 'active' : '' }}"
                        href="{{ route('reports.receiptpayment') }}">{{ localize('receipt_payments') }}</a>
                </li>
            @endcan
            @can('read_trail_balance')
                <li class="nav-item">
                    <a class="nav-link py-1 mt-0 {{ request()->routeIs('reports.trilbalance') || request()->routeIs('reports.trilbalancelGenerate') ? 'active' : '' }}"
                        href="{{ route('reports.trilbalance') }}">{{ localize('trial_balance') }}</a>
                </li>
            @endcan
            @can('read_trail_balance')
                <li class="nav-item">
                    <a class="nav-link py-1 mt-0 {{ request()->is('accounts/profit-loss') ? 'active' : '' }}"
                        href="{{ route('reports.profit-loss') }}">{{ localize('profit_loss') }}</a>
                </li>
            @endcan
            @can('read_balance_sheet')
                <li class="nav-item">
                    <a class="nav-link py-1 mt-0 {{ request()->is('accounts/balance-sheet') ? 'active' : '' }}"
                        href="{{ route('reports.balance.sheet') }}">{{ localize('balance_sheet') }}</a>
                </li>
            @endcan
        </ul>
    </div>
</div>
