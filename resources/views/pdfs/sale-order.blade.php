<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="stylesheet" href="{{ public_path('assets/css/invoice.css') }}" type="text/css">

</head>

<style>
    body {
        font-family: DejaVu Sans, sans-serif;
    }
</style>

<body>



    <table class="table-no-border">

        <tr>

            <td class="width-70">



                <img src="{{ public_path('assets/images/logo.png') }}" alt="" width="200" />

            </td>

            <td class="width-50">

                <h2>Invoice: {{ $sale->invoice_no }}</h2>

            </td>

        </tr>

    </table>



    <div class="margin-top">

        <table class="table-no-border">

            <tr>

                <td class="width-55">
                    <div><strong>My Company Inc.</strong></div>
                    <div>123 Business Street, City, Country</div>
                    <div>Phone: +63 912 345 6789 | Email: info@mycompany.com</div>
                    <div>Date: {{ $sale->sale_date ? \Carbon\Carbon::parse($sale->sale_date)->format('F d, Y') : '-' }}
                    </div>
                    <div>Cashier ID: {{ $sale->cashier?->id ?? '-' }}</div>



                </td>

                <td class="width-45">

                    <div><strong>-</strong></div>
                    <div>-</div>
                    <div>-</div>

                </td>

            </tr>

        </table>

    </div>



    <div>

        <table class="product-table">

            <thead>

                <tr>

                    <th class="width-25">

                        <strong>Product</strong>

                    </th>

                    <th class="width-25">

                        <strong>Qty</strong>

                    </th>

                    <th class="width-25">

                        <strong>Unit Price</strong>

                    </th>

                    <th class="width-25">

                        <strong>Total</strong>

                    </th>

                </tr>

            </thead>

            <tbody>



                @foreach ($sale->items as $item)
                    <tr>

                        <td class="width-25">

                            {{ $item->product->name ?? '-' }}

                            {{-- {{ $value['quantity'] }} --}}

                        </td>

                        <td class="width-25">

                            {{ $item->quantity }}

                            {{-- {{ $value['description'] }} --}}

                        </td>

                        <td class="width-25">

                            ₱{{ number_format($item->selling_price, 2) }}

                            {{-- {{ $value['price'] }} --}}

                        </td>

                        <td class="width-25">

                            ₱{{ number_format($item->selling_price * $item->quantity, 2) }}

                            {{-- {{ $value['price'] }} --}}

                        </td>

                    </tr>
                @endforeach


            </tbody>

            <tfoot>

                <tr>

                    <td class="width-70" colspan="3">

                        SUBTOTAL:

                    </td>

                    <td class="width-15">

                        ₱{{ number_format($sale->total_amount, 2) }}

                    </td>

                </tr>

                <tr>

                    <td class="width-70" colspan="3">

                        <strong>TOTAL AMOUNT:</strong>

                    </td>

                    <td class="width-25">

                        ₱{{ number_format($sale->total_amount, 2) }}

                    </td>

                </tr>

                <tr>

                    <td class="width-70" colspan="3">

                        Amount Paid:

                    </td>

                    <td class="width-25">

                        ₱{{ number_format($sale->amount_paid, 2) }}

                    </td>
                </tr>




                <tr>

                    <td class="width-70" colspan="3">

                        Change:

                    </td>

                    <td class="width-25">

                        ₱{{ number_format($sale->change, 2) }}

                    </td>
                </tr>

            </tfoot>

        </table>

    </div>

    <div class="footer-div">

        <p>THANK YOU <br />HAPPY TO SERVE</p>

    </div>

</body>

</html>
