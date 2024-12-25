<div class="card mb-3">
    <div class="fixed-tab card-header bg-white py-3 pl-0">
        <ul class="nav nav-tabs">
            @can('read_sub_account')
                <li class="nav-item">
                    <a class="nav-link mt-0 py-1 {{ request()->routeIs('subcodes.index') ? 'active' : '' }}"
                        href="{{ route('subcodes.index') }}">{{ localize('subcode') }}</a>
                </li>
            @endcan
            @can('read_subtype')
                <li class="nav-item">
                    <a class="nav-link py-1  pl-0 {{ request()->routeIs('subtypes.index') ? 'active' : '' }}"
                        href="{{ route('subtypes.index') }}">{{ localize('subtype') }}</a>
                </li>
            @endcan
        </ul>
    </div>
</div>
