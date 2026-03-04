<!-- CASHIER SIDEBAR -->
<aside id="sidebar" class="sidebar overflow-auto">
    @php
        $user = auth()->user();
        $role = $user->role ?? null;
        $isCashier = $role === 'cashier';
    @endphp

    <div class="logo-area">
        <a href="{{ route('dashboard') }}" class="d-inline-flex">
            <img src="{{ asset('assets/images/logo.svg') }}" alt="">
        </a>
    </div>

    <ul class="nav flex-column mb-10">
        {{-- MAIN --}}
        <li class="px-3 py-2">
            <small class="nav-text text-muted">Main</small>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('user.dashboard', 'dashboard') ? 'active' : '' }}"
               href="{{ route('dashboard') }}">
                <i class="ti ti-home"></i>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>

        {{-- ORDER & SALES --}}
        <li class="px-3 pt-4 pb-2">
            <small class="nav-text text-muted">Order &amp; Sales</small>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('user.sale-orders.index') ? 'active' : '' }}"
               href="{{ route('user.sale-orders.index') }}">
                <i class="ti ti-shopping-cart"></i>
                <span class="nav-text">Sale Order</span>
            </a>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('user.sale-orders.transactions') ? 'active' : '' }}"
               href="{{ route('user.sale-orders.transactions') }}">
                <i class="ti ti-file-text"></i>
                <span class="nav-text">S.O Transactions</span>
            </a>
        </li>

        {{-- ACCOUNT --}}
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
