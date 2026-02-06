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

                <h2>P.O #: {{ $po->po_number }}</h2>

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
                    <div>Date: 06-02-2026</div>

                </td>

                <td class="width-45">

                    <div><strong>{{ $po->supplier->name ?? '-' }}</strong></div>
                    <div>{{ $po->supplier->contact_person ?? '-' }}</div>
                    <div>{{ $po->supplier->phone ?? '-' }} | {{ $po->supplier->email ?? '-' }}</div>

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



                @foreach ($po->items as $item)
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

                            ₱{{ number_format($item->cost_price, 2) }}

                            {{-- {{ $value['price'] }} --}}

                        </td>

                        <td class="width-25">

                            ₱{{ number_format($item->subtotal, 2) }}

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

                        ₱{{ number_format($po->subtotal ?? $po->total_amount, 2) }}

                    </td>

                </tr>

                <tr>

                    <td class="width-70" colspan="3">

                        DISCOUNT:

                    </td>

                    <td class="width-25">

                        ₱0.00

                    </td>

                </tr>

                <tr>

                    <td class="width-70" colspan="3">

                        <strong>TOTAL AMOUNT:</strong>

                    </td>

                    <td class="width-25">

                        <strong>₱{{ number_format($po->subtotal ?? $po->total_amount, 2) }}</strong>

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
