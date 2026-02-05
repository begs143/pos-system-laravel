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

                               <!-- 🔽 RECEIPT START -->
                               <div class="receipt">

                                   <!-- ===== Invoice Header ===== -->
                                   <div class="mb-4">
                                       <!-- Logo -->
                                       <img src="{{ asset('assets/images/logo.svg') }}" alt="Company Logo"
                                           class="receipt-logo">

                                       <!-- Company Details -->
                                       <h3 class="mt-2 mb-1 receipt-title">My Company Inc.</h3>
                                       <div class="receipt-info">
                                           <div>123 Business Street, City, Country</div>
                                           <div>Phone: +63 912 345 6789 | Email: info@mycompany.com</div>
                                           <div>Invoice #: <strong>{{ $sale->invoice_no }}</strong></div>
                                           <div>Date: {{ \Carbon\Carbon::parse($sale->sale_date)->format('d-m-Y H:i') }}
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


                               <!-- ===== Buttons ===== -->
                               <div class="mt-4 d-flex justify-content-center gap-2 no-print">
                                   <a href=" {{ auth()->user()->roleRoute('sale-orders.index') }}"
                                       class="btn btn-secondary">
                                       Close
                                   </a>

                                   <button class="btn btn-primary" onclick="printDesktop()">
                                       🖨️ Desktop Print
                                   </button>

                                   <button class="btn btn-dark" onclick="printThermal()">
                                       🧾 Thermal Print
                                   </button>
                               </div>

                           </div>
                       </div>
                   </div>
               </div>





               <x-footer-layout />
           </div>


           </div>
       @endsection
       @push('media-print')
           <style>
               @media print {

                   /* Hide dashboard, headers, footers, etc. */
                   header,
                   nav,
                   aside,
                   footer,
                   .sidebar,
                   .navbar,
                   .no-print {
                       display: none !important;
                   }

                   /* Thermal print only */
                   body.thermal-print {
                       width: 58mm;
                       /* adjust to your printer width */
                       font-family: monospace;
                       font-size: 11px;
                       margin: 0;
                       padding: 0;
                   }

                   body.thermal-print .receipt {
                       width: 100%;
                       margin: 0;
                       padding: 0;
                   }

                   body.thermal-print img.receipt-logo {
                       width: 80px;
                       margin-bottom: 5px;
                   }

                   body.thermal-print h3.receipt-title {
                       font-size: 12px;
                       margin: 0 0 5px 0;
                   }

                   body.thermal-print .receipt-info div {
                       font-size: 10px;
                       line-height: 1.2;
                       margin: 0;
                   }

                   body.thermal-print table {
                       width: 100%;
                       border-collapse: collapse;
                       margin: 5px 0;
                   }

                   body.thermal-print th,
                   body.thermal-print td {
                       padding: 2px 0;
                       text-align: left;
                       border: none;
                       /* remove Bootstrap borders */
                   }

                   body.thermal-print tfoot td {
                       font-weight: bold;
                   }

                   /* Remove Bootstrap classes that mess layout */
                   body.thermal-print .table-responsive,
                   body.thermal-print .table {
                       display: block;
                       width: 100%;
                   }

                   /* Hide buttons and everything else */
                   body.thermal-print .no-print {
                       display: none;
                   }
               }
           </style>
       @endpush
