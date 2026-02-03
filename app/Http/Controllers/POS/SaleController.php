<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Only active products
        $query->where('is_active', 1);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('product_code', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('unit', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Fetch with relations and paginate
        $products = $query->with(['category', 'unit'])
            ->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('pages.pos.sales.index', compact('products'));
    }

    public function orderSummary(Request $request)
    {
        // Get all items except 'ca'
        $itemsInput = $request->except('ca');

        // Get and decode cash amount
        $cashAmountEncoded = $request->query('ca');
        $cashAmount = $cashAmountEncoded ? (float) base64_decode($cashAmountEncoded) : 0;

        // Validate presence of items and cash amount > 0
        if (empty($itemsInput) || $cashAmount <= 0) {
            return redirect()->route('admin.pos.sale.index')
                ->with('error', 'No order data provided or invalid cash amount.');
        }

        // Check if all product IDs exist
        $productIds = array_keys($itemsInput);
        $validProductIds = \App\Models\Product::whereIn('id', $productIds)->pluck('id')->toArray();

        // If any ID is invalid, reject
        $invalidIds = array_diff($productIds, $validProductIds);
        if (! empty($invalidIds)) {
            return redirect()->route('admin.pos.sale.index')
                ->with('error', 'Some products in the order are invalid.');
        }

        // Build items array and calculate total
        $items = [];
        $totalAmount = 0;

        foreach ($itemsInput as $productId => $qty) {
            $product = \App\Models\Product::find($productId);
            $items[] = [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->product_code,
                'qty' => $qty,
                'price' => $product->selling_price,
            ];

            $totalAmount += $product->selling_price * $qty;
        }

        // Validate cash amount >= total
        if ($cashAmount < $totalAmount) {
            return redirect()->route('admin.pos.sale.index')
                ->with('error', 'Cash amount is less than the total order amount.');
        }

        return view('pages.pos.sales.order-summary', [
            'items' => $items,
            'cash_amount' => $cashAmount,
            'total_amount' => $totalAmount,
        ]);
    }

    public function store(Request $request)
    {
        $sale = null;

        DB::transaction(function () use ($request, &$sale) {

            $items = $this->normalizeItems($request->items);

            [$totalAmount, $change] = $this->calculateTotals(
                $items,
                $request->cash_amount
            );

            $sale = $this->createSale(
                $totalAmount,
                $request->cash_amount,
                $change
            );

            $this->storeSaleItems($sale, $items);
        });

        return redirect()->route('admin.pos.sale.order-details', ['sale' => $sale->id])
            ->with('success', 'Sale added successfully.');
    }

    private function calculateTotals(array $items, $cashAmount): array
    {
        $totalAmount = 0;

        foreach ($items as $item) {
            $product = Product::findOrFail($item['id']);
            $totalAmount += $product->selling_price * $item['qty'];
        }

        // Deduct directly
        $cashAmount = floatval($cashAmount);
        $change = $cashAmount - $totalAmount;

        // No abort, allow negative change if needed
        return [$totalAmount, $change];
    }

    private function createSale(
        float $totalAmount,
        float $cashAmount,
        float $change
    ): Sale {

        $datePrefix = now()->format('ymd');

        $lastSale = Sale::where('invoice_no', 'like', $datePrefix.'%')
            ->orderBy('invoice_no', 'desc')
            ->lockForUpdate()
            ->first();

        $sequence = $lastSale
            ? intval(substr($lastSale->invoice_no, -4)) + 1
            : 1;

        $invoiceNo = $datePrefix.str_pad($sequence, 4, '0', STR_PAD_LEFT);

        return Sale::create([
            'invoice_no' => $invoiceNo,
            'sale_date' => now(),
            'total_amount' => $totalAmount,
            'amount_paid' => $cashAmount,
            'change' => $change,
            'cashier_id' => auth()->id(),
        ]);
    }

    private function storeSaleItems(Sale $sale, array $items): void
    {
        foreach ($items as $item) {
            $product = Product::findOrFail($item['id']);

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $product->id,
                'quantity' => $item['qty'],
                'selling_price' => $product->selling_price,
            ]);

            StockMovement::create([
                'product_id' => $product->id,
                'supplier_id' => null,
                'user_id' => auth()->id(),
                'type' => 'out',
                'quantity' => $item['qty'],
                'remarks' => "Sold in invoice {$sale->invoice_no}",
            ]);

            // Deduct from stock balance
            $stockBalance = \App\Models\StockBalance::firstOrCreate(
                ['product_id' => $product->id],
                ['quantity_on_hand' => 0]
            );

            $stockBalance->quantity_on_hand -= $item['qty'];
            $stockBalance->save();
        }
    }

    private function normalizeItems(array $items): array
    {
        // If single item (id + qty), wrap it
        if (isset($items['id'])) {
            return [[
                'id' => $items['id'],
                'qty' => $items['qty'],
            ]];
        }

        return $items;
    }

    public function orderDetails(Sale $sale)
    {
        $sale->load('items.product', 'cashier');

        return view('pages.pos.sales.order-details', compact('sale'));
    }
}
