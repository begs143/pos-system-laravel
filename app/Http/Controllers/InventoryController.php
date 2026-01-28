<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInventoryRequest;
use App\Models\Categories;
use App\Models\Unit;

class InventoryController extends Controller
{
    public function index()
    {
        return view('pages.inventory.index');
    }

    public function create()
    {
        $units = Unit::orderby('name')->get();
        $categories = Categories::orderby('name')->get();

        if ($categories->isEmpty()) {
            return redirect()
                ->route('category.index')
                ->with('error', 'Please add a category first.');
        }

        if ($units->isEmpty()) {
            return redirect()
                ->route('unit.index')
                ->with('error', 'Please add a unit first.');
        }

        return view('pages.inventory.create', compact('categories', 'units'));
    }

    public function store(StoreInventoryRequest $request)
    {

        dd($request);
        // $data = $request->validated();

        // // Handle image upload
        // if ($request->hasFile('product_image')) {
        //     $data['product_image'] = $request->file('product_image')
        //         ->store('products', 'public');
        // }

        // Product::create($data);

        // return redirect()
        //     ->route('inventory.index')
        //     ->with('success', 'Product added successfully.');
    }
}
