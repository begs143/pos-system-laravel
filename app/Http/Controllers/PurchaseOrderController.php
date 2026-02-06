<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('supplier')->orderBy('created_at', 'desc');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $purchaseOrders = $query->paginate(10)->withQueryString();

        return view('pages.purchase-orders.index', compact('purchaseOrders'));
    }

    public function create(Request $request)
    {
        $query = Product::query();

        $poNumber = $this->generatePoNumber();

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

        $suppliers = Supplier::orderBy('name')->get();

        return view('pages.purchase-orders.create', compact('products', 'suppliers', 'poNumber'));

    }

    public function store(Request $request)
    {
        // Validate
        $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'status' => 'required|in:pending,sent,received,cancelled',
            'items' => 'required|string',
        ]);

        //  Decode items JSON
        $items = json_decode($request->items, true);

        if (empty($items)) {
            return back()->withErrors('No items found.');
        }

        DB::transaction(function () use ($request, $items) {

            // Generate PO Number
            $poNumber = $this->generatePoNumber();

            // Create Purchase Order
            $purchaseOrder = PurchaseOrder::create([
                'po_number' => $poNumber,
                'supplier_id' => $request->supplier_id,
                'po_date' => now(),
                'status' => $request->status,
                'created_by' => auth()->id(),
                'total_amount' => 0, // temporary
            ]);

            $totalAmount = 0;

            // Store Items
            foreach ($items as $item) {
                $subtotal = $item['price'] * $item['qty'];

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['qty'],
                    'cost_price' => $item['price'],
                    'subtotal' => $subtotal,
                ]);

                $totalAmount += $subtotal;
            }

            // Update total_amount
            $purchaseOrder->update([
                'total_amount' => $totalAmount,
            ]);
        });

        return redirect()
            ->route('admin.purchase-orders.index')
            ->with('success', 'Purchase Order created successfully!');
    }

    private function generatePoNumber(): string
    {
        $year = now()->year;

        $lastPo = PurchaseOrder::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;

        if ($lastPo) {
            $lastNumber = intval(substr($lastPo->po_number, -5));
            $nextNumber = $lastNumber + 1;
        }

        return 'PO-'.$year.'-'.str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    public function update(Request $request, PurchaseOrder $id)
    {
        $request->validate([
            'status' => 'required|in:pending,sent,received,cancelled',
        ]);

        // Only update the status
        $id->status = $request->status;
        $id->save();

        return redirect()->back()->with('success', 'Purchase order status updated successfully.');
    }

    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $purchaseOrder->items()->delete();

        $purchaseOrder->delete();

        return redirect()->back()->with('success', 'Purchase order status updated successfully.');
    }
}
