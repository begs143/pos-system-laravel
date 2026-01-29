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
        Supplier::create($request->validated());

        return redirect(auth()->user()->roleRoute('supplier.index'))
            ->with('success', 'Category created successfully.');
    }

    public function edit(Supplier $supplier)
    {

        return view('pages.supplier.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {

        $supplier->update($request->validated());

        return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        $supplier->delete();

        return redirect()->route('supplier.index')->with('success', 'Supplier deleted successfully.');
    }
}
