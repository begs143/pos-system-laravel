<!-- ADMIN SIDEBAR -->
<aside id="sidebar" class="sidebar overflow-auto">
    @php
        $user = auth()->user();
        $role = $user->role ?? null;

        $isAdmin = $role === 'admin';

        // ORDER & SALES (admin only here)
        $saleIndexRoute   = route('admin.sale-orders.index');
        $saleTransRoute   = route('admin.sale-orders.transactions');
        $saleIndexRouteIs = request()->routeIs('admin.sale-orders.index');
        $saleTransRouteIs = request()->routeIs('admin.sale-orders.transactions');

        // LOGS
        $logsRoute   = route('admin.logs.index');
        $logsRouteIs = request()->routeIs('admin.logs.index');
    @endphp

    <div class="logo-area">
        <a href="{{ url('/dashboard') }}" class="d-inline-flex">
            <img
                src="{{ asset('assets/images/logo.svg') }}"
                alt="Logo">
        </a>
    </div>

    <ul class="nav flex-column mb-10">
        {{-- MAIN --}}
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

        {{-- PRODUCT --}}
        <li class="px-3 pt-4 pb-2">
            <small class="nav-text text-muted">Product</small>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.inventory.create') ? 'active' : '' }}"
               href="{{ route('admin.inventory.create') }}">
                <i class="ti ti-plus"></i>
                <span class="nav-text">Add Product</span>
            </a>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.category.*') ? 'active' : '' }}"
               href="{{ route('admin.category.index') }}">
                <i class="ti ti-category"></i>
                <span class="nav-text">Category</span>
            </a>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.units.*') ? 'active' : '' }}"
               href="{{ route('admin.units.index') }}">
                <i class="ti ti-link"></i>
                <span class="nav-text">Unit</span>
            </a>
        </li>

        {{-- INVENTORY / STOCK --}}
        <li class="px-3 pt-4 pb-2">
            <small class="nav-text text-muted">Inventory / Stock</small>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.inventory.index') ? 'active' : '' }}"
               href="{{ route('admin.inventory.index') }}">
                <i class="ti ti-box"></i>
                <span class="nav-text">Inventory</span>
            </a>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.inventory.manage') ? 'active' : '' }}"
               href="{{ route('admin.inventory.index') }}">
                <i class="ti ti-adjustments"></i>
                <span class="nav-text">Stock Manage</span>
            </a>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.stockmovement.*') ? 'active' : '' }}"
               href="{{ route('admin.stockmovement.index') }}">
                <i class="ti ti-arrows-transfer-down"></i>
                <span class="nav-text">Stock Movements</span>
            </a>
        </li>

        {{-- ORDER & SALES --}}
        <li class="px-3 pt-4 pb-2">
            <small class="nav-text text-muted">Order &amp; Sales</small>
        </li>
        <li>
            <a class="nav-link {{ $saleIndexRouteIs ? 'active' : '' }}"
               href="{{ $saleIndexRoute }}">
                <i class="ti ti-shopping-cart"></i>
                <span class="nav-text">Sale Order</span>
            </a>
        </li>
        <li>
            <a class="nav-link {{ $saleTransRouteIs ? 'active' : '' }}"
               href="{{ $saleTransRoute }}">
                <i class="ti ti-file-text"></i>
                <span class="nav-text">S.O Transactions</span>
            </a>
        </li>

        {{-- PURCHASE ORDER --}}
        <li class="px-3 pt-4 pb-2">
            <small class="nav-text text-muted">Purchase Order</small>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.purchase-orders.index') ? 'active' : '' }}"
               href="{{ route('admin.purchase-orders.index') }}">
                <i class="ti ti-receipt"></i>
                <span class="nav-text">Purchase Order</span>
            </a>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.supplier.index') ? 'active' : '' }}"
               href="{{ route('admin.supplier.index') }}">
                <i class="ti ti-users"></i>
                <span class="nav-text">Supplier</span>
            </a>
        </li>

        {{-- MAINTENANCE --}}
        <li class="px-3 pt-4 pb-2">
            <small class="nav-text text-muted">Maintenance</small>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.user-role') ? 'active' : '' }}"
               href="{{ route('admin.user-role') }}">
                <i class="ti ti-user-cog"></i>
                <span class="nav-text">User Role</span>
            </a>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.report.index') ? 'active' : '' }}"
               href="{{ route('admin.report.index') }}">
                <i class="ti ti-report-analytics"></i>
                <span class="nav-text">Reports</span>
            </a>
        </li>
   <li>
    <a class="nav-link {{ request()->routeIs('admin.logs.index') ? 'active' : '' }}"
       href="{{ route('admin.logs.index') }}">
        <i class="ti ti-alert-circle"></i>
        <span class="nav-text">Logs</span>
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
