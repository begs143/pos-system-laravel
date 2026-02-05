   @extends('layouts.app')
   @section('content')
       <!-- MAIN CONTENT -->
       <main id="content" class="content py-10">
           <div class="container-fluid">
               <div class="row">
                   <div class="col-12">
                       <div class="d-flex justify-content-between align-items-center mb-4">
                           <div class="">
                               <h1 class="fs-3 mb-1">Order</h1>
                               <p class="mb-0">Add to cart Item</p>
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
                               <form action="{{ auth()->user()->roleRoute('sale-orders.index') }}" method="GET"
                                   class="d-flex gap-2">
                                   <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                       placeholder="Search products..." style="max-width: 230px;">
                                   <button type="submit" class="btn btn-primary">
                                       <i class="ti ti-search"></i>
                                   </button>
                               </form>

                           </div>
                       </div>
                       <div class="card table-responsive">
                           <table class="table mb-0 text-nowrap  table-hover">
                               <thead class="table-light border-light">
                                   <tr>
                                       <th>Image</th>
                                       <th>Code</th>
                                       <th>Category</th>
                                       <th>Price</th>
                                       <th>Stocks</th>
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
                                               <a href="#" class="btn btn-sm btn-secondary add-to-cart"
                                                   data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                                   data-price="{{ $product->selling_price }}"
                                                   data-unit="{{ $product->unit->abbreviation ?? 'pcs' }}"
                                                   data-image="{{ $product->product_image
                                                       ? asset('storage/' . $product->product_image)
                                                       : asset('assets/images/default-product.png') }}"
                                                   data-stock="{{ $product->stockBalance->quantity_on_hand ?? 0 }}">
                                                   Add Cart
                                               </a>
                                           </td>
                                       </tr>
                                   @empty
                                       <tr>
                                           <td colspan="8" class="text-center text-muted">No products found</td>
                                       </tr>
                                   @endforelse
                               </tbody>

                               @php
                                   $current = $products->currentPage();
                                   $last = $products->lastPage();
                               @endphp

                               <tfoot>
                                   <tr>
                                       <td class="border-bottom-0">
                                           Showing {{ $products->perPage() }} products per page
                                       </td>
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

               <div class="row g-3 mt-3">
                   <div class="col-md-8">
                       <div class="card">
                           <div class="card-header">
                               <h4 class="card-title">Order Items</h4>


                           </div>

                           <div class="card-body ">


                               <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                   <table class="table table-hover mb-0 table-centered">
                                       <tbody id="cart-body">
                                           <tr class="text-muted text-center" id="empty-cart">
                                               <td colspan="5">No items in cart</td>
                                           </tr>
                                       </tbody>
                                   </table>
                               </div>

                           </div>

                           <!-- CARD FOOTER -->
                           <div class="card-footer d-flex justify-content-end">
                               <div class="d-flex m-2 gap-2 me-2">


                               </div>
                           </div>

                       </div>

                   </div>


                   <div class="col-md-4">
                       <div class="card">
                           <div class="card-header">
                               <h4 class="card-title">Order Summary</h4>

                           </div>
                           <div class="card-body">


                               <div class="mt-2 mb-2">
                                   <div class="table-responsive">
                                       <table class="table table-bordered bg-light-subtle">
                                           <h5 class="fw-semibold mb-3">Total Items: (<span class="text-muted"
                                                   id="total-items">0</span>)
                                           </h5>
                                           <tbody>


                                               </h5>
                                               <tr>
                                                   <td>Items :</td>
                                                   <td class="text-end text-dark fw-medium">
                                                       <span id="total-items1">0</span> (Items)
                                                   </td>
                                               </tr>
                                               <tr>
                                                   <td>Subtotal :</td>
                                                   <td class="text-end text-dark fw-medium">
                                                       ₱<span id="subtotal">0.00</span>
                                                   </td>
                                               </tr>
                                               <tr>
                                                   <td>Discount :</td>
                                                   <td class="text-end text-dark fw-medium">
                                                       ₱<span id="discount">0.00</span>
                                                   </td>
                                               </tr>


                                               <tr>
                                                   <td class="fw-semibold text-danger">Total Amount :</td>
                                                   <td class="text-end text-success fw-semibold">
                                                       ₱<span id="total-amount">0.00</span>
                                                   </td>
                                               </tr>


                                           </tbody>


                                       </table>
                                   </div>
                               </div>
                               <label for="name" class="form-label fw-semibold">Cash Amount :</label>
                               <input type="number" class="form-control" id="cash_amount" name="cash_amount"
                                   min="1" step="1"
                                   onkeydown="return event.key !== '-' && event.key !== '+' && event.key !== 'e' && event.key !== '.'"
                                   placeholder="Enter Amount" required>

                               <div class=" gap-1 hstack mt-3">

                                   <a href="#" class="btn btn-secondary w-100" onclick="clearCart()">Clear</a>

                                   <button type="button" class="btn btn-primary w-100" onclick="checkout(this)"
                                       data-checkout-url="{{ auth()->user()->roleRoute('sale-orders.summary') }}">
                                       Check Out
                                   </button>
                               </div>
                           </div>
                       </div>
                   </div>


               </div>

               <x-footer-layout />
       </main>
   @endsection

   @push('pos-sale-script')
       <script src="{{ asset('assets/js/core/app-pos-sale.js') }}"></script>
   @endpush
