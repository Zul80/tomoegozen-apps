<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(15);
        $editing = null;
        
        if ($editId = request()->query('edit')) {
            $editing = Category::find($editId);
        }

        return view('admin.categories', compact('categories', 'editing'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'hero_image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['hero_image'] = Storage::url($path);
        }

        Category::create($validated);

        return back()->with('status', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'hero_image' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->hero_image && Storage::disk('public')->exists(str_replace('/storage/', '', $category->hero_image))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $category->hero_image));
            }
            $path = $request->file('image')->store('categories', 'public');
            $validated['hero_image'] = Storage::url($path);
        }

        $category->update($validated);

        return back()->with('status', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete category with associated products.']);
        }

        // Delete image if exists
        if ($category->hero_image && Storage::disk('public')->exists(str_replace('/storage/', '', $category->hero_image))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $category->hero_image));
        }

        $category->delete();

        return back()->with('status', 'Category deleted successfully.');
    }
}

