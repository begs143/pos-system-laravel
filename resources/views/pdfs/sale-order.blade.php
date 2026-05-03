<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt</title>

<style>
    /* Remove the 'size' declaration from @page, let the driver handle the roll length */
    @page {
        margin: 0;
    }

    html, body {
        width: 58mm;
        /* Ensure height only takes up exactly what it needs */
        height: auto;
        margin: 0;
        padding: 0;
        font-family: monospace;
        font-size: 15px;
    }

    .text-center { text-align: center; }
    .text-right { text-align: right; }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 2px;
    }

    th {
        border-bottom: 1px dashed black;
    }

    hr {
        border: none;
        border-top: 1px dashed black;
        margin: 4px 0;
    }
</style>
</head>

<body onload="window.print()">

    <!-- LOGO + HEADER -->
    <div class="text-center">
        <img src="{{ public_path('assets/images/logo.png') }}" width="80">
        <strong>My Company Inc.</strong><br>
        123 Business Street<br>
        Phone: +63 912 345 6789<br>
    </div>

    <hr>

    <!-- INFO -->
    <div>
        Invoice: {{ $sale->invoice_no }}<br>
      Date: {{ $sale->sale_date->timezone('Asia/Manila')->format('m/d/Y h:i A') }}<br>
        Cashier: {{ $sale->cashier?->name ?? '-' }}
    </div>

    <hr>

    <!-- ITEMS -->
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? '-' }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">
                        {{ number_format($item->selling_price * $item->quantity, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <!-- TOTALS -->
    <table>
        <tr>
            <td>Subtotal</td>
            <td class="text-right">{{ number_format($sale->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td>Cash</td>
            <td class="text-right">{{ number_format($sale->amount_paid, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Change</strong></td>
            <td class="text-right"><strong>{{ number_format($sale->change, 2) }}</strong></td>
        </tr>
    </table>

    <hr>

    <!-- FOOTER -->
    <div class="text-center">
        THANK YOU!<br>
        PLEASE COME AGAIN
    </div>

</body>
</html>
