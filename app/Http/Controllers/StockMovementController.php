<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStockMovementRequest;
use App\Models\Product;
use App\Models\StockBalance;
use App\Models\StockMovement;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {

        $query = Product::query();

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Stock In this month
        $stockIn = StockMovement::where('type', 'in')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('quantity');

        // Stock Out this month (as positive number)
        $stockOut = StockMovement::where('type', 'out')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('quantity');
        $stockOut = abs($stockOut);

        $lowStockCount = StockBalance::join('products', 'stock_balances.product_id', '=', 'products.id')
            ->whereRaw('stock_balances.quantity_on_hand > (products.reorder_level / 2)')
            ->whereRaw('stock_balances.quantity_on_hand < products.reorder_level')
            ->count();

        $criticalStockCount = StockBalance::join('products', 'stock_balances.product_id', '=', 'products.id')
            ->whereRaw('stock_balances.quantity_on_hand <= (products.reorder_level / 2)')
            ->count();

        $recentMovements = StockMovement::with(['product', 'user'])
            ->latest() // order by created_at DESC
            ->take(5)   // only 5 records
            ->get();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('product_code', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('unit', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $products = $query
            ->leftJoin('stock_balances', 'products.id', '=', 'stock_balances.product_id')
            ->select('products.*')
            ->with(['category', 'unit', 'stockBalance'])
            ->orderByRaw('COALESCE(stock_balances.quantity_on_hand, 0) ASC')
            ->orderBy('products.created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        $suppliers = Supplier::all();

        return view('pages.stockmovement.index', compact('products', 'suppliers', 'recentMovements', 'stockIn',
            'stockOut',
            'lowStockCount',
            'criticalStockCount'));
    }

    public function store(StoreStockMovementRequest $request)
    {
        $data = $request->validated();

        $productId = $data['product_id'];
        $type = $data['type'];

        // Always force positive quantity
        $requestedQty = abs($data['quantity']);

        // Get current stock first
        $stockBalance = StockBalance::firstOrCreate(
            ['product_id' => $productId],
            ['quantity_on_hand' => 0]
        );

        if ($type === 'out' && $requestedQty > $stockBalance->quantity_on_hand) {

            return redirect(auth()->user()->roleRoute('stockmovement.index'))
                ->with('error', 'Insufficient stock. Current stock is '.$stockBalance->quantity_on_hand.'');
        }

        // Convert quantity based on type
        // $quantity = $type === 'out' ? -$requestedQty : $requestedQty;
        $quantity = $requestedQty;
        // Create stock movement
        StockMovement::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'type' => $type,
            'quantity' => $quantity,
            'supplier_id' => $data['supplier_id'] ?? null,
            'remarks' => $data['remarks'] ?? null,
        ]);

        // Update stock balance
        if ($type === 'in') {
            $stockBalance->quantity_on_hand += $quantity;
        } else {
            $stockBalance->quantity_on_hand -= $quantity;
        }
        $stockBalance->save();

        return redirect(auth()->user()->roleRoute('stockmovement.index'))
            ->with('success', $type === 'in'
                ? 'Stock added successfully.'
                : 'Stock removed successfully.'
            );
    }

    public function show(Request $request)
    {
        $query = StockMovement::with(['product', 'user', 'supplier'])->latest('created_at');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                // Search by product name or code
                $q->whereHas('product', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('product_code', 'like', "%{$search}%");
                })
                // Search by supplier name
                    ->orWhereHas('supplier', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                // Search by user name
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }
        // Paginate and keep query string
        $movements = $query->paginate(25)->withQueryString();

        return view('pages.stockmovement.show', compact('movements'));
    }
}
