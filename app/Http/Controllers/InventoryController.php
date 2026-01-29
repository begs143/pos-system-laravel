<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Models\Categories;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where('name', 'like', "%{$search}%")
                ->orWhere('product_code', 'like', "%{$search}%")
                ->orWhereHas('category', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('unit', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        }

        // Fetch with relations and paginate
        $products = $query->with(['category', 'unit'])
            ->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        return view('pages.inventory.index', compact('products'));
    }

    public function create()
    {
        $units = Unit::orderby('name')->get();
        $categories = Categories::orderby('name')->get();

        if ($categories->isEmpty()) {
            return redirect(auth()->user()->roleRoute('category.index'))
                ->with('error', 'Please add a category first.');
        }

        if ($units->isEmpty()) {

            return redirect(auth()->user()->roleRoute('unit.index'))
                ->with('error', 'Please add a unit first.');
        }

        return view('pages.inventory.create', compact('categories', 'units'));
    }

    public function edit(Product $inventory)
    {
        $units = Unit::orderBy('name')->get();
        $categories = Categories::orderBy('name')->get();

        $inventory->load('stockBalance', 'unit', 'category');

        return view('pages.inventory.edit', compact('inventory', 'units', 'categories'));
    }

    public function store(StoreInventoryRequest $request)
    {
        DB::transaction(function () use ($request) {

            $data = $request->validated();

            if ($request->hasFile('product_image')) {
                $data['product_image'] = $request->file('product_image')
                    ->store('products', 'public');
            }

            // 1. Create product
            $product = Product::create($data);

            $product->stockBalance()->create([
                'quantity_on_hand' => $request->input('quantity_on_hand', 0), // fallback 0
            ]);

        });

        return redirect(auth()->user()->roleRoute('inventory.index'))
            ->with('success', 'Product added successfully.');
    }

    public function destroy($productId)
    {
        $product = Product::findOrFail($productId);

        // Delete file on the public disk
        if ($product->product_image && Storage::disk('public')->exists($product->product_image)) {
            Storage::disk('public')->delete($product->product_image);
        }

        $product->delete();

        return redirect(auth()->user()->roleRoute('inventory.index'))
            ->with('success', 'Product deleted successfully.');
    }

    // Update product
    public function update(UpdateInventoryRequest $request, Product $product)
    {

        $data = $request->validated();

        // Handle product image upload
        if ($request->hasFile('product_image')) {
            // Delete old image if exists
            if ($product->product_image && Storage::disk('public')->exists($product->product_image)) {
                Storage::disk('public')->delete($product->product_image);
            }

            // Store new image
            $data['product_image'] = $request->file('product_image')->store('products', 'public');
        }

        // Update product
        $product->update($data);

        // Update or create stock balance
        $product->stockBalance()->updateOrCreate(
            ['product_id' => $product->id],  // find by product
            ['quantity_on_hand' => $request->quantity_on_hand ?? 0] // replace with new quantity
        );

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Product updated successfully.');
    }
}
