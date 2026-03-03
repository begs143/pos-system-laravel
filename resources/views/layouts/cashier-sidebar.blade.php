<!-- SIDEBAR -->
<aside id="sidebar" class="sidebar overflow-auto">
    @php
        $user = auth()->user();
        $role = $user->role ?? null;

        $isAdmin     = $role === 'admin';
        $isCashier   = $role === 'cashier';
        $isInventory = $role === 'inventory';

        // ORDER & SALES routes (admin + cashier)
        if ($isAdmin || $isCashier) {
            // admin uses admin.* ; cashier uses user.*
            $salePrefix = $isAdmin ? 'admin' : 'user';

            $saleIndexRoute   = route($salePrefix . '.sale-orders.index');
            $saleTransRoute   = route($salePrefix . '.sale-orders.transactions');

            $saleIndexRouteIs = request()->routeIs('admin.sale-orders.index', 'user.sale-orders.index');
            $saleTransRouteIs = request()->routeIs('admin.sale-orders.transactions', 'user.sale-orders.transactions');
        }

        // LOGS: ADMIN ONLY
        if ($isAdmin) {
            $logsRoute   = route('admin.logs.index');
            $logsRouteIs = request()->routeIs('admin.logs.index');
        }
    @endphp

    <div class="logo-area">
        <a href="{{ url('/dashboard') }}" class="d-inline-flex">
            <img
                src="data:image/svg+xml,%3csvg%20width='62'%20height='67'%20viewBox='0%200%2062%2067'%20fill='none'%20xmlns='http://www.w3.org/2000/svg'%3e%3cpath%20d='M30.604%2066.378L0.00805664%2048.1582V35.7825L30.604%2054.0023V66.378Z'%20fill='%23302C4D'/%3e%3cpath%20d='M61.1996%2048.1582L30.604%2066.378V54.0023L61.1996%2035.7825V48.1582Z'%20fill='%23E66239'/%3e%3cpath%20d='M30.5955%200L0%2018.2198V30.5955L30.5955%2012.3757V0Z'%20fill='%23657E92'/%3e%3cpath%20d='M61.191%2018.2198L30.5955%200V12.3757L61.191%2030.5955V18.2198Z'%20fill='%23A3B2BE'/%3e%3cpath%20d='M30.604%2048.8457L0.00805664%2030.6259V18.2498L30.604%2036.47V48.8457Z'%20fill='%23302C4D'/%3e%3cpath%20d='M61.1996%2030.6259L30.604%2048.8457V36.47L61.1996%2018.2498V30.6259Z'%20fill='%23E66239'/%3e%3c/svg%3e"
                alt="" width="24">
            <span class="logo-text ms-2">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="">
            </span>
        </a>
    </div>

    <ul class="nav flex-column mb-10">
        {{-- MAIN (everyone) --}}
        <li class="px-3 py-2">
            <small class="nav-text text-muted">Main</small>
        </li>
   <li>
    <a class="nav-link {{ request()->routeIs('admin.dashboard', 'user.dashboard') ? 'active' : '' }}"
       href="{{ route('dashboard') }}">
        <i class="ti ti-home"></i>
        <span class="nav-text">Dashboard</span>
    </a>
</li>

        {{-- ORDER & SALES: visible for admin and cashier only --}}
        @if($isAdmin || $isCashier)
            <li class="px-3 py-2">
                <small class="nav-text text-muted">Order &amp; Sales</small>
            </li>

            <li>
                <a class="nav-link {{ $saleIndexRouteIs ?? false ? 'active' : '' }}"
                   href="{{ $saleIndexRoute }}">
                    <i class="ti ti-shopping-cart"></i>
                    <span class="nav-text">Sale Order</span>
                </a>
            </li>

            <li>
                <a class="nav-link {{ $saleTransRouteIs ?? false ? 'active' : '' }}"
                   href="{{ $saleTransRoute }}">
                    <i class="ti ti-file-text"></i>
                    <span class="nav-text">S.O Transactions</span>
                </a>
            </li>
        @endif

        {{-- MAINTENANCE:
             - Admin: Reports + Logs
             - Cashier: NOTHING here
             - Inventory: NOTHING here --}}
        @if($isAdmin)
            <li class="px-3 pt-4 pb-2">
                <small class="nav-text text-muted">Maintenance</small>
            </li>

            <li>
                <a class="nav-link" href="#">
                    <i class="ti ti-receipt"></i>
                    <span class="nav-text">Reports</span>
                </a>
            </li>

            <li>
                <a class="nav-link {{ $logsRouteIs ?? false ? 'active' : '' }}"
                   href="{{ $logsRoute }}">
                    <i class="ti ti-alert-circle"></i>
                    <span class="nav-text">Logs</span>
                </a>
            </li>
        @endif

        {{-- ACCOUNT (everyone) --}}
        <li class="px-3 pt-4 pb-2">
            <small class="nav-text text-muted">Account</small>
        </li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link bg-transparent border-0">
                    <i class="ti ti-logout"></i>
                    <span class="nav-text">Log Out</span>
                </button>
            </form>
        </li>
    </ul>
</aside>
