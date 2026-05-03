<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleOrderController extends Controller
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

        return view('pages.sale-orders.index', compact('products'));

    }

    public function summary(Request $request)
    {
        // Get all items except 'ca'
        $itemsInput = $request->except('ca');

        // Get and decode cash amount
        $cashAmountEncoded = $request->query('ca');
        $cashAmount = $cashAmountEncoded ? (float) base64_decode($cashAmountEncoded) : 0;

        // Validate presence of items and cash amount > 0
        if (empty($itemsInput) || $cashAmount <= 0) {

            return redirect(auth()->user()->roleRoute('sale-orders.index'))
                ->with('error', 'No order data provided or invalid cash amount.');

        }

        // Check if all product IDs exist
        $productIds = array_keys($itemsInput);
        $validProductIds = \App\Models\Product::whereIn('id', $productIds)->pluck('id')->toArray();

        // If any ID is invalid, reject
        $invalidIds = array_diff($productIds, $validProductIds);
        if (! empty($invalidIds)) {
            return redirect(auth()->user()->roleRoute('sale-orders.index'))
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

            return redirect(auth()->user()->roleRoute('sale-orders.index'))
                ->with('error', 'Cash amount is less than the total order amount.');
        }

        return view('pages.sale-orders.summary', [
            'items' => $items,
            'cash_amount' => $cashAmount,
            'total_amount' => $totalAmount,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $sale = null;

            DB::transaction(function () use ($request, &$sale) {
                // Normalize the items
                $items = $this->normalizeItems($request->items);

                // Calculate totals and change
                [$totalAmount, $change] = $this->calculateTotals(
                    $items,
                    $request->cash_amount
                );

                // Create the sale
                $sale = $this->createSale(
                    $totalAmount,
                    $request->cash_amount,
                    $change
                );

                // Store sale items
                $this->storeSaleItems($sale, $items);
            });

            // Log activity after successful transaction
            activity('sales')
                ->causedBy(auth()->user())
                ->performedOn($sale)
                ->withProperties([
                    'invoice_no' => $sale->invoice_no,
                    'total_amount' => $sale->total_amount,
                    'cash_paid' => $sale->amount_paid,
                    'change' => $sale->change,
                    'items_count' => $sale->items()->count(),
                ])
                ->log('Sale order created');

            return redirect(auth()->user()->roleRoute('sale-orders.details', ['sale' => $sale->id]))
                ->with('success', 'Sale added successfully.');

        } catch (\Exception $e) {
            // Log the exception for debugging
            \Log::error('Sale creation failed: '.$e->getMessage());

            // Redirect back with input and friendly error message
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while adding the sale.');
        }
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

            $oldStock = $stockBalance->quantity_on_hand;
            $stockBalance->quantity_on_hand -= $item['qty'];
            $stockBalance->save();

            activity('sales')
                ->causedBy(auth()->user())
                ->performedOn($product)
                ->withProperties([
                    'sale_id' => $sale->id,
                    'invoice_no' => $sale->invoice_no,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'qty_sold' => $item['qty'],
                    'old_stock' => $oldStock,
                    'new_stock' => $stockBalance->quantity_on_hand,
                ])
                ->log('Product sold');
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

    public function details(Sale $sale)
    {
        $sale->load('items.product', 'cashier');

        return view('pages.sale-orders.details', compact('sale'));
    }

    public function transactions(Request $request)
    {
        $query = Sale::with('cashier')->latest('sale_date'); // Sort by latest sale date

        // Search by invoice, cashier, or date
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('invoice_no', 'like', "%{$search}%")
                ->orWhereHas('cashier', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = $request->input('start_date');
            $end = $request->input('end_date');
            $query->whereBetween('sale_date', [$start.' 00:00:00', $end.' 23:59:59']);
        }

        $sales = $query->paginate(50)->withQueryString();

        $current = $sales->currentPage();
        $last = $sales->lastPage();

        return view('pages.sale-orders.transactions', compact('sales', 'current', 'last'));
    }

    public function downloadPDF($id)
    {

    $sale = Sale::with(['items.product', 'cashier'])->findOrFail($id);

    return view('pdfs.sale-order', compact('sale'));
    }
}
