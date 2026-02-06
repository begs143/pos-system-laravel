<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        return view('pages.purchase-orders.index');
    }

    public function create(Request $request)
    {
        $query = Product::query();

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

        // Get last PO number from DB
        $lastPo = PurchaseOrder::orderBy('id', 'desc')->first();

        $nextNumber = $lastPo
            ? intval(substr($lastPo->po_number, 3)) + 1
            : 1;

        $poNumber = 'PO-'.str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        return view('pages.purchase-orders.create', compact('products', 'suppliers', 'poNumber'));

    }
}
