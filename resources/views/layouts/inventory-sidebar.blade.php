<!-- SIDEBAR – INVENTORY -->
<aside id="sidebar" class="sidebar overflow-auto">
    @php
        $user = auth()->user();
        $role = $user->role ?? null;

        $isInventory = $role === 'inventory';
        $isAdmin     = $role === 'admin'; // in case admin reuses this sidebar
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

        {{-- PRODUCT: Category, Unit, Add Product (Admin + Inventory share same routes) --}}
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
            <a class="nav-link {{ request()->routeIs('admin.stockmovement.*') ? 'active' : '' }}"
               href="{{ route('admin.stockmovement.index') }}">
                <i class="ti ti-arrows-transfer-down"></i>
                <span class="nav-text">Stock Movements</span>
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
                <span class="nav-text">Purchase Orders</span>
            </a>
        </li>
        <li>
            <a class="nav-link {{ request()->routeIs('admin.supplier.index') ? 'active' : '' }}"
               href="{{ route('admin.supplier.index') }}">
                <i class="ti ti-users"></i>
                <span class="nav-text">Suppliers</span>
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
