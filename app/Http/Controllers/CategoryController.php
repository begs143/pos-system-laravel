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

    public function store(StoreCategoryRequest $request)
    {
        try {
            Categories::create($request->validated());

            return redirect(auth()->user()->roleRoute('category.index'))
                ->with('success', 'Category created successfully.');

        } catch (\Exception $e) {

            \Log::error('Category creation failed: '.$e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while creating the category.');
        }
    }

    public function update(UpdateCategoryRequest $request, Categories $category)
    {
        try {

            $category->update($request->validated());

            return redirect(auth()->user()->roleRoute('category.index'))
                ->with('success', 'Category updated successfully.');

        } catch (\Exception $e) {

            \Log::error('Category update failed: '.$e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while updating the category.');
        }
    }

    public function destroy($categoryId)
    {
        try {

            $category = Categories::findOrFail($categoryId);

            $category->delete();

            return redirect(auth()->user()->roleRoute('category.index'))
                ->with('success', 'Category deleted successfully.');

        } catch (\Exception $e) {

            \Log::error('Category deletion failed: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong while deleting the category.');
        }
    }
}
