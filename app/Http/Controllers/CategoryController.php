<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Categories::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $categories = $query->paginate(10)->withQueryString();

        return view('pages.category.index', compact('categories'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        Categories::create($request->validated());

        return redirect(auth()->user()->roleRoute('category.index'))
            ->with('success', 'Category created successfully.');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Categories $category)
    {

        $category->update($request->validated());

        return redirect(auth()->user()->roleRoute('category.index'))
            ->with('success', 'Category updated successfully.');

    }

    public function destroy($categoryId)
    {
        $category = Categories::findOrFail($categoryId);
        $category->delete();

        return redirect(auth()->user()->roleRoute('category.index'))
            ->with('success', 'Category deleted successfully.');
    }
}
