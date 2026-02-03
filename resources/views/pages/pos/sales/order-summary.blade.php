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
                               <h1 class="fs-3 mb-1">Order Summary</h1>
                               <p class="mb-0">View and manage order summary</p>
                           </div>

                           <!-- Right: Back Button -->
                           <div class="">
                               <a href="{{ route('admin.pos.sale.index') }}" class="btn btn-secondary">
                                   <i class="ti ti-arrow-left"></i> Back
                               </a>
                           </div>
                       </div>
                   </div>

               </div>

               <div class="row">
                   <div class="col-md-12">
                       <div class="card">
                           <div class="card-body">
                               <!-- ===== Order Items Table ===== -->
                               <h2 class="fs-4 mb-2">Order Items</h2>

                               <form action="{{ auth()->user()->roleRoute('pos.sale.store') }}" method="POST">
                                   @csrf

                                   <div class="table-responsive">
                                       <table class="table mb-0 align-middle">
                                           <thead>
                                               <tr>
                                                   <th>Product</th>
                                                   <th>Quantity</th>
                                                   <th>Price</th>
                                                   <th>Total</th>
                                               </tr>
                                           </thead>
                                           <tbody>
                                               @foreach ($items as $i => $item)
                                                   <tr>
                                                       <td>{{ $item['name'] }}</td>
                                                       <td>{{ $item['qty'] }}</td>
                                                       <td>₱{{ number_format($item['price'], 2) }}</td>
                                                       <td>₱{{ number_format($item['price'] * $item['qty'], 2) }}</td>
                                                   </tr>


                                                   <!-- Hidden inputs to submit product_id and qty -->
                                                   <input type="hidden" name="items[{{ $i }}][id]"
                                                       value="{{ $item['id'] }}">
                                                   <input type="hidden" name="items[{{ $i }}][qty]"
                                                       value="{{ $item['qty'] }}">
                                               @endforeach

                                               <!-- Hidden input for cash amount -->
                                               <input type="hidden" name="cash_amount" value="{{ $cash_amount }}">
                                           </tbody>

                                           <tfoot class="border-top">
                                               @php
                                                   $subtotal = collect($items)->sum(fn($i) => $i['price'] * $i['qty']);
                                                   $change = $cash_amount - $subtotal;
                                               @endphp
                                               <tr>
                                                   <td colspan="3" class="text-muted -">Subtotal</td>
                                                   <td class="">₱{{ number_format($subtotal, 2) }}</td>
                                               </tr>

                                               <tr>
                                                   <td colspan="3" class="fw-semibold">Total</td>
                                                   <td>₱{{ number_format($subtotal, 2) }}</td>
                                               </tr>

                                               <tr>
                                                   <td colspan="3" class="">Cash</td>
                                                   <td>₱{{ number_format($cash_amount, 2) }}</td>
                                               </tr>


                                               <tr>
                                                   <td colspan="3" class="">Change</td>
                                                   <td class="fw-semibold">
                                                       ₱{{ number_format($change >= 0 ? $change : 0, 2) }}</td>
                                               </tr>
                                           </tfoot>
                                       </table>
                                   </div>

                                   <div class="mt-4 d-flex justify-content-start gap-2">
                                       <button type="submit" class="btn btn-primary" onclick="globalClearCart()">
                                           Proceed to Payment
                                       </button>
                                   </div>
                               </form>

                           </div>


                       </div>
                   </div>
               </div>

           </div>
       </main>
       <x-footer-layout />
   @endsection

   @push('pos-sale-script')
       <script src="{{ asset('assets/js/core/app-script.js') }}"></script>
   @endpush
