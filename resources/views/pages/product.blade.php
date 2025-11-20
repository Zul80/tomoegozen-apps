@extends('layouts.app')

@section('title', $product->name . ' — Tomoe Gozen')

@section('content')
    <section class="max-w-6xl mx-auto px-4 py-16 grid lg:grid-cols-2 gap-10">
        <div class="space-y-6" data-product-options data-product-id="{{ $product->id }}">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                class="w-full rounded-3xl border border-white/10 shadow-2xl shadow-red-900/20">
            <div class="stat-glass flex flex-wrap gap-4 text-xs uppercase tracking-[0.4em]">
                <span>SKU {{ $product->sku }}</span>
                <span>{{ count($product->colors ?? []) }} Colors</span>
                <span>{{ count($product->sizes ?? []) }} Sizes</span>
            </div>
        </div>

        <div class="space-y-6">
            <div>
                <p class="text-white/60 uppercase tracking-[0.4em] text-[10px]">
                    {{ $product->category->name ?? 'Drop' }}
                </p>
                <h1 class="text-4xl font-semibold">{{ $product->name }}</h1>
            </div>
            <p class="text-white/70 leading-relaxed">{{ $product->description }}</p>
            <div class="flex items-center gap-4">
                <span class="text-3xl font-bold text-red-500">Rp.. {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}</span>
                @if ($product->sale_price)
                    <span class="line-through text-white/40">Rp.. {{ number_format($product->price, 0, ',', '.') }}</span>
                @endif
            </div>
            <div class="space-y-3">
                <label class="text-xs uppercase tracking-[0.4em] text-white/60">Color</label>
                <div class="flex flex-wrap gap-3">
                    @foreach ($product->colors ?? [] as $color)
                        <button class="px-4 py-2 border border-white/10 rounded-full text-sm uppercase tracking-[0.3em] transition hover:border-red-500"
                            data-color-option="{{ $color }}">{{ $color }}</button>
                    @endforeach
                </div>
            </div>
            <div class="space-y-3">
                <label class="text-xs uppercase tracking-[0.4em] text-white/60">Size</label>
                <div class="flex flex-wrap gap-3">
                    @foreach ($product->sizes ?? [] as $size)
                        <button class="px-4 py-2 border border-white/10 rounded-full text-sm uppercase tracking-[0.3em] transition hover:border-red-500"
                            data-size-option="{{ $size }}">{{ $size }}</button>
                    @endforeach
                </div>
            </div>
            <button class="btn btn-primary w-full justify-center text-lg" data-add-to-cart data-product-id="{{ $product->id }}">
                Add to cart
            </button>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-4 py-12">
        <h2 class="text-2xl font-semibold mb-6">You may also like</h2>
        <div class="grid md:grid-cols-4 sm:grid-cols-2 gap-6">
            @foreach ($related as $item)
                <article class="product-card">
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="rounded-2xl border border-white/5">
                    <div>
                        <h3 class="font-semibold">{{ $item->name }}</h3>
                        <p class="text-sm text-white/60 h-10 overflow-hidden">{{ $item->description }}</p>
                    </div>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="font-bold text-red-400">
                            Rp. {{ number_format($item->sale_price ?? $item->price, 0, ',', '.') }}
                        </span>
                        @if ($item->sale_price)
                            <span class="line-through text-white/40 text-sm">
                                Rp. {{ number_format($item->price, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('product.show', $item) }}" class="btn btn-ghost justify-center">View</a>
                </article>
            @endforeach
        </div>
    </section>
@endsection

