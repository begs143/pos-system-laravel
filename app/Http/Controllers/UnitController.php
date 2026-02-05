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
        Unit::create($request->validated());

        return redirect(auth()->user()->roleRoute('units.index'))
            ->with('success', 'Unit created successfully.');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {

        $unit->update($request->validated());

        return redirect(auth()->user()->roleRoute('units.index'))
            ->with('success', 'Unit updated successfully.');
    }

    public function destroy($unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $unit->delete();

        return redirect(auth()->user()->roleRoute('units.index'))
            ->with('success', 'Unit deleted successfully.');
    }
}
