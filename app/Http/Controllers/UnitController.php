<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('abbreviation', 'like', "%{$search}%");
        }
        $units = $query->paginate(10)->withQueryString();

        return view('pages.units.index', compact('units'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitRequest $request)
    {
        try {
            // Create the unit
            Unit::create($request->validated());

            return redirect(auth()->user()->roleRoute('units.index'))
                ->with('success', 'Unit created successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Unit creation failed: '.$e->getMessage());

            // Redirect back with old input and friendly error message
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while creating the unit.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        try {
            // Update the unit
            $unit->update($request->validated());

            return redirect(auth()->user()->roleRoute('units.index'))
                ->with('success', 'Unit updated successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Unit update failed: '.$e->getMessage());

            // Redirect back with old input and friendly error message
            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while updating the unit.');
        }
    }

    public function destroy($unitId)
    {
        try {
            // Find the unit
            $unit = Unit::findOrFail($unitId);

            $unit->delete();

            return redirect(auth()->user()->roleRoute('units.index'))
                ->with('success', 'Unit deleted successfully.');

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Unit deletion failed: '.$e->getMessage());

            // Redirect back with friendly error message
            return redirect()->back()
                ->with('error', 'Something went wrong while deleting the unit.');
        }
    }
}
