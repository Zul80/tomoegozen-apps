@extends('layouts.admin')

@section('title', 'Admin Collections — Tomoe Gozen')

@section('content')
    <section class="max-w-6xl mx-auto space-y-8">
        <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-white/60 uppercase tracking-[0.4em] text-[10px]">Admin</p>
                <h1 class="text-3xl font-semibold">Collection Management</h1>
            </div>
        </header>

        <div class="border border-white/5 rounded-3xl p-6 space-y-4">
            <h2 class="text-xl font-semibold">Create Collection</h2>
            <form action="{{ route('admin.collections.store') }}" method="POST" enctype="multipart/form-data"
                class="grid md:grid-cols-2 gap-4 text-xs uppercase tracking-[0.2em]">
                @csrf
                <label class="space-y-1">
                    <span class="text-white/60">Title</span>
                    <input name="title" required 
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Slug (auto-generated if empty)</span>
                    <input name="slug" 
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case"
                        placeholder="noir-tokyo">
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
                        placeholder="/images/collections/noir-tokyo.svg">
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Upload Image</span>
                    <input type="file" name="image" accept="image/*"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Featured?</span>
                    <select name="is_featured" 
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </label>
                <div class="md:col-span-2 flex justify-end">
                    <button class="btn btn-primary">Create Collection</button>
                </div>
            </form>
        </div>

        @if ($editing)
            <div class="border border-white/5 rounded-3xl p-6 space-y-4">
                <h2 class="text-xl font-semibold">Edit Collection — {{ $editing->title }}</h2>
                <form action="{{ route('admin.collections.update', $editing) }}" method="POST" enctype="multipart/form-data"
                    class="grid md:grid-cols-2 gap-4 text-xs uppercase tracking-[0.2em]">
                    @csrf
                    @method('PUT')
                    <label class="space-y-1">
                        <span class="text-white/60">Title</span>
                        <input name="title" value="{{ $editing->title }}" required
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
                        <span class="text-white/60">Featured?</span>
                        <select name="is_featured" 
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                            <option value="0" @selected(!$editing->is_featured)>No</option>
                            <option value="1" @selected($editing->is_featured)>Yes</option>
                        </select>
                    </label>
                    <div class="md:col-span-2 flex justify-between">
                        <a href="{{ route('admin.collections') }}" class="btn btn-ghost">Cancel</a>
                        <button class="btn btn-primary">Update Collection</button>
                    </div>
                </form>
            </div>
        @endif

        <div class="overflow-x-auto border border-white/5 rounded-2xl">
            <table class="w-full text-sm">
                <thead class="text-white/60 uppercase tracking-[0.4em] text-[10px]">
                    <tr>
                        <th class="text-left p-4">Title</th>
                        <th class="text-left p-4">Slug</th>
                        <th class="text-left p-4">Products</th>
                        <th class="text-left p-4">Featured</th>
                        <th class="text-right p-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($collections as $collection)
                        <tr class="border-t border-white/5">
                            <td class="p-4 font-semibold">{{ $collection->title }}</td>
                            <td class="p-4 text-white/70">{{ $collection->slug }}</td>
                            <td class="p-4 text-white/70">{{ $collection->products()->count() }}</td>
                            <td class="p-4">
                                @if ($collection->is_featured)
                                    <span class="text-red-400 uppercase tracking-[0.3em] text-xs">Featured</span>
                                @else
                                    <span class="text-white/40 uppercase tracking-[0.3em] text-xs">—</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.collections', ['edit' => $collection->id]) }}"
                                        class="btn btn-ghost px-3 py-1 text-xs">Edit</a>
                                    <form method="POST" action="{{ route('admin.collections.destroy', $collection) }}"
                                        onsubmit="return confirm('Delete {{ $collection->title }}? This will fail if it has products.')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-primary px-3 py-1 text-xs bg-red-600">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-white/60">No collections found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $collections->withQueryString()->links() }}
    </section>
@endsection

