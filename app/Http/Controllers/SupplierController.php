<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('contact_person', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%");
        }

        $suppliers = $query->paginate(10)->withQueryString();

        return view('pages.supplier.index', compact('suppliers'));

    }

    public function create()
    {
        return view('pages.supplier.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        try {
            // Create supplier
            Supplier::create($request->validated());

            return redirect(auth()->user()->roleRoute('supplier.index'))
                ->with('success', 'Supplier created successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Supplier creation failed: '.$e->getMessage());

            // Redirect back with old input and friendly error message
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while creating the supplier.');
        }
    }

    public function edit(Supplier $supplier)
    {

        return view('pages.supplier.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        try {
            // Update supplier
            $supplier->update($request->validated());

            return redirect(auth()->user()->roleRoute('supplier.index'))
                ->with('success', 'Supplier updated successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Supplier update failed: '.$e->getMessage());

            // Redirect back with old input and friendly error message
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while updating the supplier.');
        }
    }

    public function destroy($supplierId)
    {
        try {
            // Find the supplier
            $supplier = Supplier::findOrFail($supplierId);

            // Delete the supplier
            $supplier->delete();

            return redirect(auth()->user()->roleRoute('supplier.index'))
                ->with('success', 'Supplier deleted successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Supplier deletion failed: '.$e->getMessage());

            // Redirect back with friendly error message
            return redirect()->back()
                ->with('error', 'Something went wrong while deleting the supplier.');
        }
    }
}
