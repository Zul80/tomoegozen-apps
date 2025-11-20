@extends('layouts.app')

@section('title', 'Catalog — Tomoe Gozen')

@section('content')
    <section class="max-w-6xl mx-auto px-4 py-12">
        <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-10">
            <div>
                <p class="text-white/60 uppercase tracking-[0.4em] text-[10px]">Catalog</p>
                <h1 class="text-3xl font-semibold">Oversize T-Shirts — Japanese Streetwear</h1>
            </div>
            <form method="GET" action="{{ route('catalog') }}" class="flex flex-wrap gap-3 text-sm">
                <input type="text" name="search" placeholder="Search" value="{{ request('search') }}"
                    class="bg-black/50 border border-white/10 rounded-full px-4 py-2 focus:border-red-500 outline-none" />
                <select name="category" class="bg-black/50 border border-white/10 rounded-full px-4 py-2">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="color" placeholder="Color" value="{{ request('color') }}"
                    class="bg-black/50 border border-white/10 rounded-full px-4 py-2 w-32">
                <input type="text" name="size" placeholder="Size" value="{{ request('size') }}"
                    class="bg-black/50 border border-white/10 rounded-full px-4 py-2 w-20 uppercase">
                <input type="number" name="price_min" placeholder="Min" value="{{ request('price_min') }}"
                    class="bg-black/50 border border-white/10 rounded-full px-4 py-2 w-24">
                <input type="number" name="price_max" placeholder="Max" value="{{ request('price_max') }}"
                    class="bg-black/50 border border-white/10 rounded-full px-4 py-2 w-24">
                <select name="sort" class="bg-black/50 border border-white/10 rounded-full px-4 py-2">
                    <option value="">Sort</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Price ↑</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Price ↓</option>
                    <option value="newest" @selected(request('sort') === 'newest')>Newest</option>
                </select>
                <button class="btn btn-primary">Filter</button>
            </form>
        </header>

        <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-6">
            @foreach ($products as $product)
                <article class="product-card" data-animate="card">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded-2xl border border-white/5">
                    <div class="space-y-2">
                        <p class="text-xs uppercase tracking-[0.4em] text-white/60">{{ $product->category->name ?? 'Drop' }}</p>
                        <h3 class="text-xl font-semibold">{{ $product->name }}</h3>
                        <p class="text-sm text-white/60 h-12 overflow-hidden">{{ $product->description }}</p>
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-red-400">Rp. {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}</span>
                            @if ($product->sale_price)
                                <span class="text-white/40 text-sm line-through">Rp. {{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('product.show', $product) }}" class="btn btn-ghost flex-1 justify-center">Detail</a>
                        <button class="btn btn-primary flex-1 justify-center" data-add-to-cart data-product-id="{{ $product->id }}">
                            Add
                        </button>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $products->links() }}
        </div>
    </section>
@endsection

