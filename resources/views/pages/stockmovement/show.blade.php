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


               <div class="row py-1 mb-4">
                   <div class="col-12">
                       <div>
                           <form action="" method="GET">
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
                           <table class="table mb-0 text-nowrap  table-hover align-middle">
                               <thead>
                                   <tr>

                                       <th>Product</th>
                                       <th>Date</th>
                                       <th>Type</th>
                                       <th class="">Quantity</th>
                                       <th>Supplier</th>
                                       <th>Added By</th>
                                       <th>Remarks</th>
                                   </tr>
                               </thead>
                               <tbody>
                                   @forelse ($movements as $movement)
                                       <tr>

                                           <td class="d-flex align-items-center">
                                               <img src="{{ $movement->product->product_image
                                                   ? asset('storage/' . $movement->product->product_image)
                                                   : asset('assets/images/default-product.png') }}"
                                                   alt="{{ $movement->product->name }}" class="avatar avatar-md rounded" />
                                               <span class="ms-3">{{ $movement->product->name ?? '-' }}</span>
                                           </td>
                                           <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                                           <td>
                                               @php
                                                   $badgeClass = match ($movement->type) {
                                                       'in' => 'bg-success bg-opacity-10 text-success',
                                                       'out' => 'bg-danger bg-opacity-10 text-danger',
                                                   };
                                               @endphp
                                               <span class="badge {{ $badgeClass }} rounded-pill">
                                                   {{ ucfirst($movement->type) }}
                                               </span>
                                           </td>
                                           <td class="">
                                               @php
                                                   $sign =
                                                       $movement->type === 'in'
                                                           ? '+'
                                                           : ($movement->type === 'out'
                                                               ? '-'
                                                               : '');
                                                   $colorClass =
                                                       $movement->type === 'in'
                                                           ? 'text-success'
                                                           : ($movement->type === 'out'
                                                               ? 'text-danger'
                                                               : 'text-warning');
                                               @endphp
                                               <span
                                                   class="{{ $colorClass }}">{{ $sign }}{{ $movement->quantity }}</span>
                                           </td>
                                           <td>{{ $movement->supplier->name ?? 'No Supplier' }}</td>
                                           <td>{{ $movement->user->name ?? '-' }}</td>
                                           <td>{{ $movement->remarks ?? '-' }}</td>
                                       </tr>
                                   @empty
                                       <tr>
                                           <td colspan="8" class="text-center text-muted">No products found</td>
                                       </tr>
                                   @endforelse
                               </tbody>


                               @php
                                   $current = $movements->currentPage();
                                   $last = $movements->lastPage();
                               @endphp

                               <tfoot>
                                   <tr>
                                       <td class="border-bottom-0">
                                           Showing {{ $movements->perPage() }} transactions per page
                                       </td>
                                       <td colspan="9" class="border-bottom-0">
                                           <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                               <ul class="pagination mb-0">

                                                   {{-- Prev --}}
                                                   <li class="page-item {{ $current == 1 ? 'disabled' : '' }}">
                                                       <a class="page-link"
                                                           href="{{ $movements->previousPageUrl() ?? '#' }}"
                                                           tabindex="-1">
                                                           Previous
                                                       </a>
                                                   </li>

                                                   {{-- Page numbers --}}
                                                   @for ($i = 1; $i <= $last; $i++)
                                                       <li class="page-item {{ $current == $i ? 'active' : '' }}">
                                                           <a class="page-link"
                                                               href="{{ $movements->url($i) }}">{{ $i }}</a>
                                                       </li>
                                                   @endfor

                                                   {{-- Next --}}
                                                   <li class="page-item {{ $current == $last ? 'disabled' : '' }}">
                                                       <a class="page-link" href="{{ $movements->nextPageUrl() ?? '#' }}">
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



               <x-footer-layout />


           </div>
           </div>
       </main>
   @endsection
