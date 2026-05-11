<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['quizzes', 'questions'])->latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:10',
            'color'       => 'required|string|max:20',
            'is_active'   => 'boolean',
        ]);

        $validated['slug']      = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        Category::create($validated);
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'icon'        => 'nullable|string|max:10',
            'color'       => 'required|string|max:20',
            'is_active'   => 'boolean',
        ]);

        $validated['slug']      = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted.');
    }

    public function show(Category $category) { abort(404); }
}
