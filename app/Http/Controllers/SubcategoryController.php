<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use App\Models\Category;
use App\Http\Requests\StoreSubcategoryRequest;
use App\Http\Requests\UpdateSubcategoryRequest;
use Illuminate\Http\Request;
class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::paginate(10);  // Paginate subcategories
        return view('subcategories.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Category::all(); // Fetch all categories
        return view('subcategories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        Subcategory::create($request->all());
        return redirect()->route('subcategories.index');
    }

    public function show(Subcategory $subcategory)
    {
        return view('subcategories.show', compact('subcategory'));
    }

    public function edit(Subcategory $subcategory)
    {
        return view('subcategories.edit', compact('subcategory'));
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $subcategory->update($request->all());
        return redirect()->route('subcategories.index');
    }

    public function destroy(Subcategory $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('subcategories.index');
    }
}
