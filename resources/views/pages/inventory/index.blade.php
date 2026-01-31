   @extends('layouts.app')
   @section('content')
       <!-- MAIN CONTENT -->
       <main id="content" class="content py-10">
           <div class="container-fluid">
               <div class="row">
                   <div class="col-12">
                       <div class="d-flex justify-content-between align-items-center mb-4">
                           <div class="">
                               <h1 class="fs-3 mb-1">Product Inventory</h1>
                               <p class="mb-0">Manage your product inventory</p>
                           </div>
                           <div>



                           </div>
                       </div>
                   </div>
               </div>

               @include('partials.success-message')
               @include('partials.error-message')

               <div class="row">
                   <div class="col-12">


                       <div>
                           <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-3">

                               <!-- Left: Search form -->
                               <form action="{{ auth()->user()->roleRoute('inventory.index') }}" method="GET"
                                   class="d-flex gap-2">
                                   <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                       placeholder="Search products..." style="max-width: 230px;">
                                   <button type="submit" class="btn btn-primary">
                                       <i class="ti ti-search"></i>
                                   </button>
                               </form>

                               <!-- Right: Action buttons -->
                               <div class="d-flex gap-2 flex-wrap">
                                   <!-- Add Product -->
                                   <a class="btn btn-primary" href="{{ auth()->user()->roleRoute('inventory.create') }}">
                                       <i class="ti ti-plus"></i>
                                       <span class="nav-text">Add Product</span>
                                   </a>

                                   <!-- Export -->
                                   <a class="btn btn-primary" href="{{ auth()->user()->roleRoute('inventory.export') }}">
                                       <i class="ti ti-file-excel"></i>
                                       <span class="nav-text">Export</span>
                                   </a>
                               </div>

                           </div>
                       </div>
                       <div class="card table-responsive">
                           <table class="table mb-0 text-nowrap  table-hover">
                               <thead>
                                   <tr>
                                       <th>Image</th>
                                       <th>Code</th>
                                       <th>Category</th>
                                       <th>Cost Price</th>
                                       <th>Selling Price</th>
                                       <th>Quantity</th>
                                       <th>Status</th>
                                       <th>Action</th>
                                   </tr>
                               </thead>

                               <tbody>
                                   @forelse($products as $product)
                                       <tr class="align-middle">

                                           <td>
                                               <a>
                                                   <img src="{{ $product->product_image
                                                       ? asset('storage/' . $product->product_image)
                                                       : asset('assets/images/default-product.png') }}"
                                                       alt="{{ $product->name }}" class="avatar avatar-md rounded" />
                                                   <span class="ms-3">{{ $product->name }}</span>
                                               </a>
                                           </td>
                                           <td>{{ $product->product_code ?? '-' }}</td>
                                           <td>{{ $product->category->name ?? '-' }}</td>
                                           <td>₱{{ number_format($product->cost_price, 2) }}</td>
                                           <td>₱{{ number_format($product->selling_price, 2) }}</td>


                                           <td>
                                               {{ $product->stockBalance->quantity_on_hand ?? 0 }}
                                               {{ $product->unit->abbreviation ?? 'pcs' }}
                                           </td>
                                           <td class="py-3">
                                               <span
                                                   class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                   {{ $product->is_active ? 'Active' : 'Inactive' }}
                                               </span>
                                           </td>

                                           <!-- Actions -->
                                           <td>
                                               <a href="{{ auth()->user()->roleRoute('inventory.edit', $product->id) }}"
                                                   class=""><i class="ti ti-edit fs-5 "></i></a>
                                               <form
                                                   action="{{ auth()->user()->roleRoute('inventory.destroy', $product->id) }}"
                                                   method="POST" style="display: inline;">
                                                   @csrf
                                                   @method('DELETE')
                                                   <button type="submit"
                                                       class="btn btn-link p-0 m-0 align-baseline link-danger"
                                                       onclick="return confirm('Are you sure you want to delete this product?');">
                                                       <i class="ti ti-trash ms-2 fs-5"></i>
                                                   </button>
                                               </form>
                                           </td>
                                       </tr>
                                   @empty
                                       <tr>
                                           <td colspan="8" class="text-center">No products found</td>
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

               <x-footer-layout />
           </div>
       </main>
   @endsection
