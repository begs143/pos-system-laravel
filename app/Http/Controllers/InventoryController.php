<?php

namespace App\Http\Controllers;

use App\Exports\ExportProduct;
use App\Http\Requests\StoreInventoryRequest;
use App\Http\Requests\UpdateInventoryRequest;
use App\Models\Categories;
use App\Models\Product;
use App\Models\Unit;
use Excel;
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

            return redirect(auth()->user()->roleRoute('units.index'))
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
        try {
            DB::transaction(function () use ($request) {

                $data = $request->validated();

                // Handle product image
                if ($request->hasFile('product_image')) {
                    $data['product_image'] = $request->file('product_image')
                        ->store('products', 'public');
                }

                // Create product
                $product = Product::create($data);

                // Create initial stock balance
                $stockBalance = $product->stockBalance()->create([
                    'quantity_on_hand' => $request->input('quantity_on_hand', 0),
                ]);

                // Log activity
                activity('inventory')
                    ->causedBy(auth()->user())
                    ->performedOn($product)
                    ->withProperties([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_code' => $product->product_code ?? null,
                        'initial_stock' => $stockBalance->quantity_on_hand,
                        'price' => $product->selling_price ?? null,
                    ])
                    ->log('Product created');

            });

            return redirect(auth()->user()->roleRoute('inventory.index'))
                ->with('success', 'Product added successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Inventory product creation failed: '.$e->getMessage());

            // Redirect back with input and friendly error
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while adding the product.');
        }
    }

    public function destroy($productId)
    {
        try {
            // Find the product
            $product = Product::findOrFail($productId);

            // Log activity BEFORE delete
            activity('inventory')
                ->causedBy(auth()->user())
                ->performedOn($product)
                ->withProperties([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_code' => $product->product_code ?? null,
                    'price' => $product->selling_price ?? null,
                    'stock' => optional($product->stockBalance)->quantity_on_hand,
                ])
                ->log('Product deleted');

            // Delete product image if exists
            if ($product->product_image && Storage::disk('public')->exists($product->product_image)) {
                Storage::disk('public')->delete($product->product_image);
            }

            // Delete the product
            $product->delete();

            return redirect(auth()->user()->roleRoute('inventory.index'))
                ->with('success', 'Product deleted successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Inventory product deletion failed: '.$e->getMessage());

            // Redirect back with friendly error message
            return redirect()->back()
                ->with('error', 'Something went wrong while deleting the product.');
        }
    }

    // Update product
    public function update(UpdateInventoryRequest $request, Product $product)
    {
        try {
            $data = $request->validated();

            // Save old product data for activity log
            $oldProduct = $product->only([
                'name',
                'product_code',
                'selling_price',
                'cost_price',
                'unit_id',
                'category_id',
            ]);

            $oldStock = optional($product->stockBalance)->quantity_on_hand ?? 0;

            // Handle product image upload
            if ($request->hasFile('product_image')) {
                // Delete old image from storage if it exists
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
                ['product_id' => $product->id],
                ['quantity_on_hand' => $request->quantity_on_hand ?? 0]
            );

            $newStock = $product->stockBalance->quantity_on_hand;

            // Log activity if product data or stock changed
            if ($product->wasChanged() || $oldStock != $newStock) {
                activity('inventory')
                    ->causedBy(auth()->user())
                    ->performedOn($product)
                    ->withProperties([
                        'old' => array_merge($oldProduct, [
                            'quantity_on_hand' => $oldStock,
                        ]),
                        'new' => array_merge(
                            $product->only(array_keys($oldProduct)),
                            ['quantity_on_hand' => $newStock]
                        ),
                    ])
                    ->log('Product updated');
            }

            return redirect()->route('admin.inventory.index')
                ->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Inventory product update failed: '.$e->getMessage());

            // Redirect back with old input and friendly error message
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while updating the product.');
        }
    }

    public function export()
    {
        return Excel::download(new ExportProduct, 'product.xls');
    }
}
