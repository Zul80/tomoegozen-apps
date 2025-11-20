@extends('layouts.app')

@section('title', 'Admin Products — Tomoe Gozen')

@section('content')
    <section class="max-w-6xl mx-auto px-4 py-12 space-y-8">
        <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-white/60 uppercase tracking-[0.4em] text-[10px]">Admin</p>
                <h1 class="text-3xl font-semibold">Product Management</h1>
            </div>
            <div class="flex gap-3">
                <form action="{{ route('admin.products.export') }}" method="GET">
                    <button class="btn btn-ghost" type="submit">Export CSV</button>
                </form>
                <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="btn btn-primary cursor-pointer">
                        Import CSV
                        <input type="file" name="file" class="hidden" onchange="this.form.submit()">
                    </label>
                </form>
            </div>
        </header>

        @if (session('status'))
            <div class="stat-glass text-sm text-green-400">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="stat-glass text-sm text-red-400">
                {!! implode('<br>', $errors->all()) !!}
            </div>
        @endif

        <div class="border border-white/5 rounded-3xl p-6 space-y-4">
            <h2 class="text-xl font-semibold">Create Product</h2>
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
                class="grid md:grid-cols-2 gap-4 text-xs uppercase tracking-[0.2em]">
                @csrf
                <label class="space-y-1">
                    <span class="text-white/60">Name</span>
                    <input name="name" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">SKU</span>
                    <input name="sku" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Slug</span>
                    <input name="slug" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Price (IDR)</span>
                    <input type="number" name="price" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Sale Price</span>
                    <input type="number" name="sale_price" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Category</span>
                    <select name="category_id" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Collection ID</span>
                    <input type="number" name="collection_id" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Image URL</span>
                    <input name="image_url" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case"
                        placeholder="/images/products/tg01.svg">
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Colors (csv)</span>
                    <input name="colors_csv" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case"
                        placeholder="black, red">
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Sizes (csv)</span>
                    <input name="sizes_csv" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case"
                        placeholder="M, L, XL">
                </label>
                <label class="space-y-1 md:col-span-2">
                    <span class="text-white/60">Stock JSON</span>
                    <textarea name="stock_by_size_json" rows="2"
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case"
                        placeholder='{"M":10,"L":8}'></textarea>
                </label>
                <label class="space-y-1 md:col-span-2">
                    <span class="text-white/60">Tags (csv)</span>
                    <input name="tags_csv" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case"
                        placeholder="sakura, drop">
                </label>
                <label class="space-y-1 md:col-span-2">
                    <span class="text-white/60">Description</span>
                    <textarea name="description" rows="2" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case"></textarea>
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Featured?</span>
                    <select name="is_featured" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </label>
                <label class="space-y-1">
                    <span class="text-white/60">Upload image</span>
                    <input type="file" name="image" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                </label>
                <input type="hidden" name="currency" value="IDR">
                <div class="md:col-span-2 flex justify-end">
                    <button class="btn btn-primary">Create Product</button>
                </div>
            </form>
        </div>

        @if ($editing)
            <div class="border border-white/5 rounded-3xl p-6 space-y-4">
                <h2 class="text-xl font-semibold">Edit Product — {{ $editing->name }}</h2>
                <form action="{{ route('admin.products.update', $editing) }}" method="POST" enctype="multipart/form-data"
                    class="grid md:grid-cols-2 gap-4 text-xs uppercase tracking-[0.2em]">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="currency" value="{{ $editing->currency }}">
                    <label class="space-y-1">
                        <span class="text-white/60">Name</span>
                        <input name="name" value="{{ $editing->name }}" required
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                    </label>
                    <label class="space-y-1">
                        <span class="text-white/60">SKU</span>
                        <input name="sku" value="{{ $editing->sku }}" required
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                    </label>
                    <label class="space-y-1">
                        <span class="text-white/60">Slug</span>
                        <input name="slug" value="{{ $editing->slug }}" required
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                    </label>
                    <label class="space-y-1">
                        <span class="text-white/60">Price</span>
                        <input type="number" name="price" value="{{ $editing->price }}" required
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                    </label>
                    <label class="space-y-1">
                        <span class="text-white/60">Sale Price</span>
                        <input type="number" name="sale_price" value="{{ $editing->sale_price }}"
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                    </label>
                    <label class="space-y-1">
                        <span class="text-white/60">Category</span>
                        <select name="category_id" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected($editing->category_id === $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <label class="space-y-1">
                        <span class="text-white/60">Collection ID</span>
                        <input type="number" name="collection_id" value="{{ $editing->collection_id }}"
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                    </label>
                    <label class="space-y-1">
                        <span class="text-white/60">Image URL</span>
                        <input name="image_url" value="{{ $editing->image_url }}"
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                    </label>
                    <label class="space-y-1">
                        <span class="text-white/60">Colors (csv)</span>
                        <input name="colors_csv" value="{{ implode(', ', $editing->colors ?? []) }}"
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                    </label>
                    <label class="space-y-1">
                        <span class="text-white/60">Sizes (csv)</span>
                        <input name="sizes_csv" value="{{ implode(', ', $editing->sizes ?? []) }}"
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                    </label>
                    <label class="space-y-1 md:col-span-2">
                        <span class="text-white/60">Stock JSON</span>
                        <textarea name="stock_by_size_json" rows="2"
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">{{ json_encode($editing->stock_by_size ?? (object) [], JSON_PRETTY_PRINT) }}</textarea>
                    </label>
                    <label class="space-y-1 md:col-span-2">
                        <span class="text-white/60">Tags (csv)</span>
                        <input name="tags_csv" value="{{ implode(', ', $editing->tags ?? []) }}"
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                    </label>
                    <label class="space-y-1 md:col-span-2">
                        <span class="text-white/60">Description</span>
                        <textarea name="description" rows="2"
                            class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">{{ $editing->description }}</textarea>
                    </label>
                    <label class="space-y-1">
                        <span class="text-white/60">Featured?</span>
                        <select name="is_featured" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                            <option value="0" @selected(! $editing->is_featured)>No</option>
                            <option value="1" @selected($editing->is_featured)>Yes</option>
                        </select>
                    </label>
                    <label class="space-y-1">
                        <span class="text-white/60">Replace image</span>
                        <input type="file" name="image" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 normal-case">
                    </label>
                    <div class="md:col-span-2 flex justify-between">
                        <a href="{{ route('admin.products') }}" class="btn btn-ghost">Cancel</a>
                        <button class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        @endif

        <div class="overflow-x-auto border border-white/5 rounded-2xl">
            <table class="w-full text-sm">
                <thead class="text-white/60 uppercase tracking-[0.4em] text-[10px]">
                    <tr>
                        <th class="text-left p-4">SKU</th>
                        <th class="text-left p-4">Name</th>
                        <th class="text-left p-4">Category</th>
                        <th class="text-right p-4">Price</th>
                        <th class="text-right p-4">Stock (sum)</th>
                        <th class="text-right p-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr class="border-t border-white/5">
                            <td class="p-4 font-semibold">{{ $product->sku }}</td>
                            <td class="p-4">{{ $product->name }}</td>
                            <td class="p-4 text-white/70">{{ $product->category->name ?? '—' }}</td>
                            <td class="p-4 text-right text-red-400 font-semibold">
                                Rp {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-right">{{ array_sum($product->stock_by_size ?? []) }}</td>
                            <td class="p-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.products', ['edit' => $product->id]) }}"
                                        class="btn btn-ghost px-3 py-1 text-xs">Edit</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                        onsubmit="return confirm('Delete {{ $product->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-primary px-3 py-1 text-xs bg-red-600">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $products->withQueryString()->links() }}
    </section>
@endsection

