@php
    $user = auth()->user();
    $role = $user->role ?? null;
@endphp

@if ($role === 'admin')

    @include('layouts.admin-sidebar')

@elseif ($role === 'cashier')

    @include('layouts.cashier-sidebar')

@elseif ($role === 'inventory')

    @include('layouts.inventory-sidebar')

@else

@endif
