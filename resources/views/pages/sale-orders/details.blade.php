   @extends('layouts.app')
   @section('content')
       <!-- MAIN CONTENT -->
       <main id="content" class="content py-10">
           <div class="container-fluid">

               <div class="row ">
                   <div class="col-md-12">
                       <div class="mb-6 d-flex align-items-center justify-content-between">
                           <!-- Left: Title -->
                           <div>
                               <h1 class="fs-3 mb-1">Order Details</h1>
                               <p class="mb-0">View and manage order details</p>
                           </div>


                       </div>
                   </div>

               </div>

               @include('partials.success-message')
               @include('partials.error-message')

               <div class="row">
                   <div class="col-md-12">
                       <div class="card p-3">
                           <div class="card-body text-center">


                               <div class="receipt">


                                   <div class="mb-4">
                                       <!-- Logo -->
                                       <img src="{{ asset('assets/images/logo.svg') }}" alt="Company Logo"
                                           class="receipt-logo">


                                       <h3 class="mt-2 mb-1 receipt-title">My Company Inc.</h3>
                                       <div class="receipt-info">
                                           <div>123 Business Street, City, Country</div>
                                           <div>Phone: +63 912 345 6789 | Email: info@mycompany.com</div>
                                           <div>Invoice #: <strong>{{ $sale->invoice_no }}</strong></div>
                                           <div>Date: {{ $sale->sale_date->timezone('Asia/Manila')->format('m/d/Y h:i A') }}
                                           </div>
                                           <div>CID#: {{ $sale->cashier?->id ?? 'N/A' }}</div>
                                       </div>
                                   </div>

                                   <!-- ===== Order Items Table ===== -->
                                   <div class="table-responsive">
                                       <table class="table mb-0 align-middle receipt-table">
                                           <thead>
                                               <tr>
                                                   <th>Product</th>
                                                   <th>Qty</th>
                                                   <th>Price</th>
                                                   <th>Total</th>
                                               </tr>
                                           </thead>
                                           <tbody>
                                               @foreach ($sale->items as $item)
                                                   <tr>
                                                       <td>{{ $item->product->name }}</td>
                                                       <td>{{ $item->quantity }}</td>
                                                       <td>₱{{ number_format($item->selling_price, 2) }}</td>
                                                       <td>₱{{ number_format($item->selling_price * $item->quantity, 2) }}
                                                       </td>
                                                   </tr>
                                               @endforeach
                                           </tbody>
                                           <tfoot class="border-top">
                                               <tr>
                                                   <td colspan="3">Subtotal</td>
                                                   <td>₱{{ number_format($sale->total_amount, 2) }}</td>
                                               </tr>
                                               <tr>
                                                   <td colspan="3">Cash</td>
                                                   <td>₱{{ number_format($sale->amount_paid, 2) }}</td>
                                               </tr>
                                               <tr>
                                                   <td colspan="3">Change</td>
                                                   <td>₱{{ number_format($sale->change, 2) }}</td>
                                               </tr>
                                           </tfoot>
                                       </table>
                                   </div>

                               </div>



                               <div class="mt-4 d-flex justify-content-center gap-2 no-print">
                                   <a href=" {{ auth()->user()->roleRoute('sale-orders.index') }}"
                                       class="btn btn-secondary">
                                       Create New Sale Order
                                   </a>


                                   <a href="{{ auth()->user()->roleRoute('sale-orders.view', ['sale' => $sale->id]) }}"
                                       class="btn btn-primary" target="_blank">
                                       Print
                                   </a>



                               </div>

                           </div>
                       </div>
                   </div>
               </div>

               <x-footer-layout />
           </div>


           </div>
       @endsection
       @push('app-script')
           <script>
               window.history.pushState(null, "", window.location.href);
               window.addEventListener("popstate", function() {
                   window.location.href = "{{ auth()->user()->roleRoute('sale-orders.index') }}";
               });
           </script>
       @endpush
