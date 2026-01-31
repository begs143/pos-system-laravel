   @extends('layouts.app')
   @section('content')
       <!-- MAIN CONTENT -->
       <main id="content" class="content py-10">
           <div class="container-fluid">
               <div class="row">
                   <div class="col-12">
                       <div class="d-flex justify-content-between align-items-center mb-4">
                           <div class="">
                               <h1 class="fs-3 mb-1">Stock Movements</h1>
                               <p class="mb-0">Manage your stock inventory</p>
                           </div>
                       </div>
                   </div>
               </div>






               {{-- Success Message --}}
               @include('partials.success-message')
               @include('partials.error-message')
               {{-- Edit Category Modal --}}

               <div class="row g-5 mb-5">
                   <!-- Stock In -->
                   <div class="col-xl-3 col-md-6 col-12">
                       <div class="card">
                           <div class="card-body p-3">
                               <div class="d-flex justify-content-between">
                                   <div>
                                       <span class="mb-4 d-block">Stock In (This Month)</span>
                                       <h3 class="fw-bold mb-0 text-success">+{{ $stockIn }}</h3>
                                       <p class="mb-0 small text-secondary">Unit added</p>
                                   </div>
                                   <div class="">
                                       <i class="ti ti-trending-up fs-3 text-success"></i>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>

                   <!-- Stock Out -->
                   <div class="col-xl-3 col-md-6 col-12">
                       <div class="card">
                           <div class="card-body p-3">
                               <div class="d-flex justify-content-between">
                                   <div>
                                       <span class="mb-4 d-block">Stock Out (This Month)</span>
                                       <h3 class="fw-bold mb-0 text-danger">-{{ $stockOut }}</h3>
                                       <p class="mb-0 small text-secondary">Unit removed</p>
                                   </div>
                                   <div class="">
                                       <i class="ti ti-trending-down fs-3 text-danger"></i>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>

                   <!-- Low Stock Alerts -->
                   <div class="col-xl-3 col-md-6 col-12">
                       <div class="card">
                           <div class="card-body p-3">
                               <div class="d-flex justify-content-between">
                                   <div>
                                       <span class="mb-4 d-block">Low Stock Alerts</span>
                                       <h3 class="fw-bold mb-0 text-warning">{{ $lowStockCount }}</h3>
                                       <p class="mb-0 small text-secondary">Items need attention</p>
                                   </div>
                                   <div class="">
                                       <i class="ti ti-alert-triangle fs-3 text-warning"></i>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>

                   <!-- Critical Stock Alerts -->
                   <div class="col-xl-3 col-md-6 col-12">
                       <div class="card">
                           <div class="card-body p-3">
                               <div class="d-flex justify-content-between">
                                   <div>
                                       <span class="mb-4 d-block">Critical Stocks Alert</span>
                                       <h3 class="fw-bold mb-0 text-danger">{{ $criticalStockCount }}</h3>
                                       <p class="mb-0 small text-secondary">Items need to restock</p>
                                   </div>
                                   <div class="">
                                       <i class="ti ti-trending-down fs-3 text-danger"></i>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
               </div>

               <div class="row py-1 mb-4">
                   <div class="col-12">
                       <div>
                           <form action="{{ auth()->user()->roleRoute('stockmovement.index') }}" method="GET">
                               <div class="d-flex gap-2 mb-3" style="max-width: 230px;">
                                   <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                       placeholder="Search products...">

                                   <button type="submit" class="btn btn-primary">
                                       <i class="ti ti-search"></i>
                                   </button>
                               </div>
                           </form>
                       </div>
                       <div class="card table-responsive ">
                           <table class="table mb-0 text-nowrap table-hover">
                               <thead class="table-light border-light">
                                   <tr>
                                       <th>Image</th>
                                       <th>Code</th>
                                       <th>Current Stock</th>
                                       <th>Min Stock</th>
                                       <th>Status</th>
                                       <th class="">Action</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   @forelse ($products as $product)
                                       {{-- Add Category Modal --}}
                                       @include('pages.stockmovement.modal-restock')
                                       @php
                                           $currentStock = $product->stockBalance->quantity_on_hand ?? 0;
                                           $reorderLevel = $product->reorder_level;

                                       @endphp

                                       <tr class="align-middle">
                                           <!-- Image + Name -->
                                           <td>
                                               <a>
                                                   <img src="{{ $product->product_image
                                                       ? asset('storage/' . $product->product_image)
                                                       : asset('assets/images/default-product.png') }}"
                                                       alt="{{ $product->name }}" class="avatar avatar-md rounded" />
                                                   <span class="ms-3">{{ $product->name }}</span>
                                               </a>
                                           </td>

                                           <!-- SKU -->
                                           <td class="text-secondary">
                                               {{ $product->product_code }}
                                           </td>

                                           <!-- Current Stock -->
                                           <td
                                               class="{{ $currentStock <= $reorderLevel ? 'text-danger' : 'text-success' }}">
                                               {{ $currentStock }}
                                           </td>

                                           <!-- Reorder Level -->
                                           <td>
                                               {{ $reorderLevel }}
                                           </td>

                                           <!-- Status -->
                                           <td>
                                               @if ($currentStock >= $reorderLevel)
                                                   <span class="badge bg-success text-white text-success rounded-pill">
                                                       Normal
                                                   </span>
                                               @elseif ($currentStock > $reorderLevel / 2)
                                                   <span class="badge bg-secondary bg-opacity-10 text-dark rounded-pill">
                                                       Warning
                                                   </span>
                                               @else
                                                   <span class="badge bg-danger rounded-pill">
                                                       Critical
                                                   </span>
                                               @endif
                                           </td>

                                           <!-- Action -->
                                           <td>
                                               <a href="#" class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                                   data-bs-target="#restockModal{{ $product->id }}">
                                                   Manage Stock
                                               </a>
                                           </td>
                                       </tr>
                                   @empty
                                       <tr>
                                           <td colspan="6" class="text-center text-muted">
                                               No products found
                                           </td>
                                       </tr>
                                   @endforelse
                               </tbody>

                               @php
                                   $current = $products->currentPage();
                                   $last = $products->lastPage();
                               @endphp
                               <tfoot>
                                   <tr>
                                       <td class="border-bottom-0">Showing 25 products per page</td>
                                       <td colspan="9" class="border-bottom-0">
                                           <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                               <ul class="pagination mb-0">

                                                   {{-- Prev --}}
                                                   <li class="page-item {{ $current == 1 ? 'disabled' : '' }}">
                                                       <a class="page-link"
                                                           href="{{ $products->previousPageUrl() ?? '#' }}" tabindex="-1">
                                                           Previous
                                                       </a>
                                                   </li>

                                                   {{-- Page numbers --}}
                                                   @for ($i = 1; $i <= $last; $i++)
                                                       <li class="page-item {{ $current == $i ? 'active' : '' }}">
                                                           <a class="page-link"
                                                               href="{{ $products->url($i) }}">{{ $i }}</a>
                                                       </li>
                                                   @endfor

                                                   {{-- Next --}}
                                                   <li class="page-item {{ $current == $last ? 'disabled' : '' }}">
                                                       <a class="page-link" href="{{ $products->nextPageUrl() ?? '#' }}">
                                                           Next
                                                       </a>
                                                   </li>
                                               </ul>
                                           </nav>
                                       </td>
                                   </tr>
                               </tfoot>
                           </table>

                       </div>
                   </div>
               </div>

               <div class="row">
                   <div class="col-12">
                       <div class="card">
                           <div class="card-body p-5">
                               <h3 class="fs-5">Recent Stock Activity</h3>

                               @forelse($recentMovements as $movement)
                                   @php
                                       $isIn = $movement->type === 'in';
                                       $iconClass = $isIn ? 'ti-trending-up' : 'ti-trending-down';
                                       $bgClass = $isIn
                                           ? 'bg-success bg-opacity-10 text-success'
                                           : 'bg-danger bg-opacity-10 text-danger';
                                       $qtyClass = $isIn ? 'text-success' : 'text-danger';
                                       $displayQty = $isIn ? '+' . $movement->quantity : '-' . abs($movement->quantity);
                                   @endphp

                                   <div class="border-bottom d-flex justify-content-between align-items-center mb-4 pb-3">
                                       <div class="d-flex gap-3 align-items-center py-3">
                                           <div class="icon-shape icon-md {{ $bgClass }} rounded-2">
                                               <i class="ti {{ $iconClass }}"></i>
                                           </div>
                                           <div>
                                               <h5 class="mb-0">{{ $movement->product->product_code }}</h5>
                                               <p class="mb-0 small text-secondary">
                                                   Stock {{ ucfirst(str_replace('_', ' ', $movement->type)) }}
                                                   @if ($movement->remarks)
                                                       - {{ $movement->remarks }}
                                                   @endif
                                               </p>
                                           </div>
                                       </div>
                                       <div class="d-flex flex-column align-items-end">
                                           <span class="fs-5 {{ $qtyClass }}">{{ $displayQty }}</span>
                                           <span>{{ $movement->user->name }}</span>
                                           <span>{{ $movement->created_at->diffForHumans() }}</span>
                                       </div>
                                   </div>
                               @empty
                                   <p class="text-muted">No recent stock activity.</p>
                               @endforelse

                           </div>
                       </div>
                   </div>
               </div>

               <div class="row">
                   <div class="col-12">
                       <footer class="text-center py-2 mt-6 text-secondary ">
                           <p class="mb-0">Copyright © 2026 InApp Inventory Dashboard. Developed by <a
                                   href="https://codescandy.com/" target="_blank" class="text-primary">CodesCandy</a>
                           </p>
                       </footer>
                   </div>

               </div>


           </div>
           </div>
       </main>
   @endsection
