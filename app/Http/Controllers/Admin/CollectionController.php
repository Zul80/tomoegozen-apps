<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::latest()->paginate(15);
        $editing = null;
        
        if ($editId = request()->query('edit')) {
            $editing = Collection::find($editId);
        }

        return view('admin.collections', compact('collections', 'editing'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:collections,slug',
            'description' => 'nullable|string',
            'hero_image' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('collections', 'public');
            $validated['hero_image'] = Storage::url($path);
        }

        Collection::create($validated);

        return back()->with('status', 'Collection created successfully.');
    }

    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:collections,slug,' . $collection->id,
            'description' => 'nullable|string',
            'hero_image' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
        ]);

        // Auto-generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($collection->hero_image && Storage::disk('public')->exists(str_replace('/storage/', '', $collection->hero_image))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $collection->hero_image));
            }
            $path = $request->file('image')->store('collections', 'public');
            $validated['hero_image'] = Storage::url($path);
        }

        $collection->update($validated);

        return back()->with('status', 'Collection updated successfully.');
    }

    public function destroy(Collection $collection)
    {
        // Check if collection has products
        if ($collection->products()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete collection with associated products.']);
        }

        // Delete image if exists
        if ($collection->hero_image && Storage::disk('public')->exists(str_replace('/storage/', '', $collection->hero_image))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $collection->hero_image));
        }

        $collection->delete();

        return back()->with('status', 'Collection deleted successfully.');
    }
}

