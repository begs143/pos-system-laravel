   @extends('layouts.app')
   @section('content')
       <!-- MAIN CONTENT -->
       <main id="content" class="content py-10">
           <div class="container-fluid">

               <div class="row">
                   <div class="col-12">
                       <div class="d-flex justify-content-between align-items-center mb-4">
                           <div class="">
                               <h1 class="fs-3 mb-1">Create Purchase</h1>
                               <p class="mb-0">Manage items</p>
                           </div>
                       </div>

                       <div>
                           <form action="{{ auth()->user()->roleRoute('purchase-orders.create') }}" method="GET">
                               <div class="d-flex gap-2 mb-3" style="max-width: 230px;">
                                   <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                       placeholder="Search Product...">

                                   <button type="submit" class="btn btn-primary">
                                       <i class="ti ti-search"></i>
                                   </button>
                               </div>
                           </form>
                       </div>


                   </div>
               </div>

               <form action="{{ auth()->user()->roleRoute('purchase-orders.store') }}" method="POST" id="poForm">
                   @csrf

                   <input type="hidden" name="items" id="poCartInput">
                   <div class="row d-flex align-items-start">
                       <div class="col-md-8">
                           <div class="card">
                               <div class="card-body p-4">
                                   <div class="row">
                                       <div class="col-md-6 mb-3">
                                           <label for="productName" class="form-label">Purchase No.</label>
                                           <input type="text" class="form-control" value="{{ $poNumber }}" disabled>

                                       </div>
                                       <div class="col-md-6 mb-3">
                                           <label for="createdBy" class="form-label">Created By</label>
                                           <input type="text" class="form-control"
                                               value="{{ auth()->user()->name ?? '' }}" disabled>
                                       </div>
                                       <div class="col-md-6 mb-3">
                                           <label class="form-label">Supplier</label>
                                           <select class="form-select" id="supplierSelect" name="supplier_id">
                                               <option value="">No Supplier</option>
                                               @foreach ($suppliers as $supplier)
                                                   <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                               @endforeach
                                           </select>
                                       </div>

                                       <div class="col-md-6 mb-3">
                                           <label class="form-label">Status</label>
                                           <select class="form-select" id="statusSelect" name="status" required>
                                               <option value="pending">Pending</option>
                                               <option value="sent">Sent</option>
                                               <option value="received">Received</option>
                                               <option value="cancelled">Cancelled</option>
                                           </select>
                                       </div>
                                   </div>


                               </div>
                           </div>

                           <div class="card mt-3 mb-3">
                               <div class="card-body p-4">

                                   <!-- Product Table -->
                                   <div class="card table-responsive">
                                       <table class="table mb-0 text-nowrap table-hover">
                                           <thead class="table-light border-light">
                                               <tr>
                                                   <th>Name</th>
                                                   <th>Code</th>
                                                   <th>Category</th>
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
                                                                   alt="{{ $product->name }}"
                                                                   class="avatar avatar-md rounded" />
                                                               <span class="ms-3">{{ $product->name }}</span>
                                                           </a>
                                                       </td>
                                                       <td>{{ $product->product_code ?? '-' }}</td>
                                                       <td>{{ $product->category->name ?? '-' }}</td>
                                                       <td>
                                                           <a href="#" class="btn btn-sm btn-secondary add-to-po"
                                                               data-id="{{ $product->id }}"
                                                               data-name="{{ $product->name }}"
                                                               data-price="{{ $product->selling_price }}"
                                                               data-unit="{{ $product->unit->abbreviation ?? 'pcs' }}"
                                                               data-image="{{ $product->product_image
                                                                   ? asset('storage/' . $product->product_image)
                                                                   : asset('assets/images/default-product.png') }}"
                                                               data-stock="{{ $product->stockBalance->quantity_on_hand ?? 0 }}">
                                                               Add
                                                           </a>
                                                       </td>
                                                   </tr>
                                               @empty
                                                   <tr>
                                                       <td colspan="8" class="text-center text-muted">No products found
                                                       </td>
                                                   </tr>
                                               @endforelse
                                           </tbody>
                                           <tfoot>
                                               <tr>
                                                   <td class="border-bottom-0">
                                                       Showing {{ $products->perPage() }} products per page
                                                   </td>
                                                   <td colspan="9" class="border-bottom-0">
                                                       <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                                           <ul class="pagination mb-0">
                                                               <li
                                                                   class="page-item {{ $products->currentPage() == 1 ? 'disabled' : '' }}">
                                                                   <a class="page-link"
                                                                       href="{{ $products->previousPageUrl() ?? '#' }}"
                                                                       tabindex="-1">
                                                                       Previous
                                                                   </a>
                                                               </li>
                                                               @for ($i = 1; $i <= $products->lastPage(); $i++)
                                                                   <li
                                                                       class="page-item {{ $products->currentPage() == $i ? 'active' : '' }}">
                                                                       <a class="page-link"
                                                                           href="{{ $products->url($i) }}">{{ $i }}</a>
                                                                   </li>
                                                               @endfor
                                                               <li
                                                                   class="page-item {{ $products->currentPage() == $products->lastPage() ? 'disabled' : '' }}">
                                                                   <a class="page-link"
                                                                       href="{{ $products->nextPageUrl() ?? '#' }}">Next</a>
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
                       </div>
                       <!-- Right Side: Cart -->
                       <div class="col-md-4">
                           <div class="card">
                               <div class="card-header">
                                   <h5 class="card-title">Item List</h5>
                               </div>
                               <div class="card-body">
                                   <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                       <table class="table table-hover mb-0 table-centered">
                                           <tbody id="po-cart-body">

                                               <tr class="text-muted text-center" id="po-empty-cart">
                                                   <td colspan="5">No items found</td>
                                               </tr>


                                           </tbody>
                                       </table>
                                   </div>
                               </div>

                               <div class="card-body">

                                   <div class="mt-2 mb-2">
                                       <div class="table-responsive">
                                           <table class="table table-bordered bg-light-subtle">
                                               <h5 class="fw-semibold mb-3">Total Items: (<span class="text-muted"
                                                       id="po-total-items">0</span>)</h5>
                                               <tbody>
                                                   <tr>
                                                       <td>Items :</td>
                                                       <td class="text-end text-dark fw-medium">
                                                           <span id="po-total-items-display">0</span> (Items)
                                                       </td>
                                                   </tr>
                                                   <tr>
                                                       <td>Subtotal :</td>
                                                       <td class="text-end text-dark fw-medium">
                                                           ₱<span id="po-subtotal">0.00</span>
                                                       </td>
                                                   </tr>
                                                   <tr>
                                                       <td>Discount :</td>
                                                       <td class="text-end text-dark fw-medium">
                                                           ₱<span id="po-discount">0.00</span>
                                                       </td>
                                                   </tr>
                                                   <tr>
                                                       <td class="fw-semibold text-danger">Total Amount :</td>
                                                       <td class="text-end text-success fw-semibold">
                                                           ₱<span id="po-total-amount">0.00</span>
                                                       </td>
                                                   </tr>
                                               </tbody>
                                           </table>
                                       </div>
                                   </div>



                                   <div class="gap-2 d-flex mt-3">
                                       <button type="button" class="btn btn-secondary flex-grow-1"
                                           onclick="clearPoCart()">Clear</button>

                                       <button type="submit" class="btn btn-primary flex-grow-1">Create P.O</button>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
               </form>



               <x-footer-layout />
           </div>
       </main>
   @endsection

   @push('pos-sale-script')
       <script src="{{ asset('assets/js/core/app-po-script.js') }}"></script>
   @endpush
