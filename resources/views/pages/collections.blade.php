@extends('layouts.app')

@section('title', 'Collections — Tomoe Gozen')

@section('content')
    <section class="max-w-6xl mx-auto px-4 py-12 space-y-10">
        <header>
            <p class="text-white/60 uppercase tracking-[0.4em] text-[10px]">Collections</p>
            <h1 class="text-3xl font-semibold">Curated capsules inspired by Tokyo street culture.</h1>
        </header>

        <div class="space-y-10">
            @foreach ($collections as $collection)
                <article class="border border-white/5 rounded-3xl overflow-hidden shadow-2xl shadow-red-900/10">
                    <img src="{{ $collection->hero_image }}" alt="{{ $collection->title }}" class="w-full h-72 object-cover">
                    <div class="p-8 grid md:grid-cols-2 gap-6">
                        <div>
                            <h2 class="text-2xl font-semibold">{{ $collection->title }}</h2>
                            <p class="text-white/60">{{ $collection->description }}</p>
                            <p class="text-xs uppercase tracking-[0.4em] text-white/40 mt-4">
                                {{ $collection->products->count() }} Products
                            </p>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            @foreach ($collection->products->take(2) as $product)
                                <div class="bg-black/40 border border-white/5 rounded-2xl p-4 space-y-2">
                                    <p class="text-sm text-white/60 uppercase tracking-[0.4em]">{{ $product->sku }}</p>
                                    <p class="font-semibold">{{ $product->name }}</p>
                                    <p class="text-sm text-white/60 h-12 overflow-hidden">{{ $product->description }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection

