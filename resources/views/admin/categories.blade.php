@extends('layouts.admin')

@section('title', 'Admin Categories — Tomoe Gozen')

@section('content')
    <section class="max-w-6xl mx-auto space-y-8">
        <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-white/60 uppercase tracking-[0.4em] text-[10px]">Admin</p>
                <h1 class="text-3xl font-semibold">Category Management</h1>
            </div>
        </header>

        <div class="border border-white/5 rounded-3xl p-6 space-y-4">
            <h2 class="text-xl font-semibold">Create Category</h2>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data"
                class="grid md:grid-cols-2 gap-4 text-xs uppercase tracking-[0.2em]">
                @csrf
                <label class="space-y-1">
                    <span class="text-white/60">Name</span>
                    <input name="name" required 
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Slug (auto-generated if empty)</span>
                    <input name="slug" 
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case"
                        placeholder="signature-series">
                </label>
                <label class="space-y-1 md:col-span-2">
                    <span class="text-white/60">Description</span>
                    <textarea name="description" rows="3"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case"></textarea>
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Hero Image URL</span>
                    <input name="hero_image" 
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case"
                        placeholder="/images/collections/signature-series.svg">
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Upload Image</span>
                    <input type="file" name="image" accept="image/*"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Active?</span>
                    <select name="is_active" 
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>
                </label>
                <div class="md:col-span-2 flex justify-end">
                    <button class="btn btn-primary">Create Category</button>
                </div>
            </form>
        </div>

        @if ($editing)
            <div class="border border-white/5 rounded-3xl p-6 space-y-4">
                <h2 class="text-xl font-semibold">Edit Category — {{ $editing->name }}</h2>
                <form action="{{ route('admin.categories.update', $editing) }}" method="POST" enctype="multipart/form-data"
                    class="grid md:grid-cols-2 gap-4 text-xs uppercase tracking-[0.2em]">
                    @csrf
                    @method('PUT')
                    <label class="space-y-1">
                        <span class="text-white/60">Name</span>
                        <input name="name" value="{{ $editing->name }}" required
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                    </label>
                    <label class="space-y-1">
                        <span class="text-white/60">Slug</span>
                        <input name="slug" value="{{ $editing->slug }}" required
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                    </label>
                    <label class="space-y-1 md:col-span-2">
                        <span class="text-white/60">Description</span>
                        <textarea name="description" rows="3"
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">{{ $editing->description }}</textarea>
                    </label>
                    <label class="space-y-1">
                        <span class="text-white/60">Hero Image URL</span>
                        <input name="hero_image" value="{{ $editing->hero_image }}"
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                    </label>
                    <label class="space-y-1">
                        <span class="text-white/60">Replace Image</span>
                        <input type="file" name="image" accept="image/*"
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                    </label>
                    <label class="space-y-1">
                        <span class="text-white/60">Active?</span>
                        <select name="is_active" 
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                            <option value="1" @selected($editing->is_active)>Yes</option>
                            <option value="0" @selected(!$editing->is_active)>No</option>
                        </select>
                    </label>
                    <div class="md:col-span-2 flex justify-between">
                        <a href="{{ route('admin.categories') }}" class="btn btn-ghost">Cancel</a>
                        <button class="btn btn-primary">Update Category</button>
                    </div>
                </form>
            </div>
        @endif

        <div class="overflow-x-auto border border-white/5 rounded-2xl">
            <table class="w-full text-sm">
                <thead class="text-white/60 uppercase tracking-[0.4em] text-[10px]">
                    <tr>
                        <th class="text-left p-4">Name</th>
                        <th class="text-left p-4">Slug</th>
                        <th class="text-left p-4">Products</th>
                        <th class="text-left p-4">Status</th>
                        <th class="text-right p-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr class="border-t border-white/5">
                            <td class="p-4 font-semibold">{{ $category->name }}</td>
                            <td class="p-4 text-white/70">{{ $category->slug }}</td>
                            <td class="p-4 text-white/70">{{ $category->products()->count() }}</td>
                            <td class="p-4">
                                @if ($category->is_active)
                                    <span class="text-green-400 uppercase tracking-[0.3em] text-xs">Active</span>
                                @else
                                    <span class="text-white/40 uppercase tracking-[0.3em] text-xs">Inactive</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.categories', ['edit' => $category->id]) }}"
                                        class="btn btn-ghost px-3 py-1 text-xs">Edit</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                        onsubmit="return confirm('Delete {{ $category->name }}? This will fail if it has products.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-primary px-3 py-1 text-xs bg-red-600">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-white/60">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $categories->withQueryString()->links() }}
    </section>
@endsection

