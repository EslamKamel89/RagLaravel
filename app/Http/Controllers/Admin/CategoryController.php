<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    public function index(Request $request) {
        $filter = $request->get('filter', null);
        $categories = Category::when($filter, function ($query, $f) {
            return $query->where('name', 'like', "%{$f}%");
        })->latest()->paginate(10)->withQueryString();
        return view('admin.categories.index', compact('categories', 'filter'));
    }
    public function create() {
        return view('admin.categories.create');
    }
    public function store(Request $request) {
        $validated = $request->validate(['name' => ['required', 'string', 'max:255', 'unique:categories,name']]);
        Category::create($validated);
        return redirect()
            ->route('categories.index')
            ->with('success', 'Category created successfully.');
    }
    public function edit(Category $category) {
        return view('admin.categories.edit', compact('category'));
    }
    public function update(Request $request, Category $category) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', "unique:categories,name,{$category->id}"],
        ]);

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }
    public function destroy(Category $category) {
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
