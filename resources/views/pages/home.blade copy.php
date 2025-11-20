@extends('layouts.app')

@section('title', 'Tomoe Gozen — Oversize Japanese Streetwear')

@section('content')
    <!-- Hero Section with Video Background -->
    <section class="relative min-h-[100vh] flex items-center justify-center overflow-hidden"></section>
        <video 
            autoplay 
            loop 
            muted 
            playsinline 
            class="absolute inset-0 w-full h-full object-cover brightness-75"
            poster="{{ asset('images/hero-poster.jpg') }}"
            id="hero-video"
        >
            <source src="{{ asset('video/tomoeatwar_bright.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="relative z-10 text-center text-white px-6">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow-lg">Oversize Japanese Streetwear<br><span class="text-red-500">Tomoe Gozen</span></h1>
            <p class="text-lg md:text-2xl text-white/70 mb-8 max-w-2xl mx-auto">Bold designs &amp; iconic drops, inspired by Tokyo’s legendary warrior. Make your mark in the streetwear culture.</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('catalog') }}" class="btn btn-primary px-8 py-4 text-lg">Shop Catalog</a>
                <a href="{{ route('collections.index') }}" class="btn btn-ghost px-8 py-4 text-lg">View Collections</a>
            </div>
        </div>
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/30 to-black/90"></div>
    </section>
    <!-- Featured Products Section -->
    <section class="max-w-7xl mx-auto px-4 py-20 bg-gradient-to-b from-black to-gray-950">
        <div class="text-center mb-12">
            <p class="text-sm uppercase tracking-[0.4em] text-white/60 mb-2">Featured Collection</p>
            <h2 class="text-4xl md:text-5xl font-bold mb-4">Latest Drops</h2>
            <p class="text-white/70 max-w-2xl mx-auto">Discover our most popular oversize Japanese streetwear pieces</p>
        </div>
        <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-8 mb-8">
            @foreach ($featured as $product)
                <article class="product-card group cursor-pointer" data-animate="card">
                    <div class="relative overflow-hidden rounded-2xl mb-4">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                            class="w-full h-80 object-cover rounded-2xl border border-white/5 transition-transform duration-500 group-hover:scale-110">
                        @if ($product->sale_price)
                            <span class="absolute top-4 right-4 bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold">
                                SALE
                            </span>
                        @endif
                    </div>
                    <div class="space-y-3">
                        <p class="text-xs uppercase tracking-[0.4em] text-white/60">{{ $product->category->name ?? 'Drop' }}</p>
                        <h3 class="text-xl font-bold hover:text-red-500 transition-colors">{{ $product->name }}</h3>
                        <p class="text-sm text-white/60 line-clamp-2">{{ $product->description }}</p>
                        <div class="flex items-center gap-3 pt-2">
                             <span class="font-bold text-xl text-red-400">Rp. {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}</span>
                            @if ($product->sale_price)
                                 <span class="text-white/40 text-sm line-through">Rp. {{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-3 mt-4">
                        <a href="{{ route('product.show', $product) }}" class="btn btn-ghost flex-1 text-center justify-center">
                            View Details
                        </a>
                        <button class="btn btn-primary flex-1 justify-center" data-add-to-cart data-product-id="{{ $product->id }}">
                            Add to Cart
                        </button>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="text-center">
            <a href="{{ route('catalog') }}" class="btn btn-ghost px-10 py-4">
                View All Products
            </a>
        </div>
    </section>

    <!-- Collections Section -->
    <section class="max-w-7xl mx-auto px-4 py-20 bg-black">
        <div class="text-center mb-12">
            <p class="text-sm uppercase tracking-[0.4em] text-white/60 mb-2">Our Collections</p>
            <h2 class="text-4xl md:text-5xl font-bold mb-4">Curated Drops</h2>
            <p class="text-white/70 max-w-2xl mx-auto">Explore our carefully curated collections inspired by Japanese street culture</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($collections as $collection)
                <article class="group relative overflow-hidden rounded-2xl border border-white/10 hover:border-red-500/50 transition-all duration-300 cursor-pointer">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $collection->hero_image }}" alt="{{ $collection->title }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    </div>
                    <div class="p-6 space-y-2">
                        <h3 class="text-xl font-bold group-hover:text-red-500 transition-colors">{{ $collection->title }}</h3>
                        <p class="text-sm text-white/60 line-clamp-2">{{ $collection->description }}</p>
                        <p class="text-xs text-white/40 tracking-[0.4em] uppercase pt-2">
                            {{ $collection->products_count ?? $collection->products->count() }} Products
                        </p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <!-- Brand Story Section -->
    <section class="max-w-7xl mx-auto px-4 py-20 bg-gradient-to-b from-gray-950 to-black">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <p class="text-sm uppercase tracking-[0.4em] text-white/60 mb-4">About Us</p>
                <h2 class="text-4xl md:text-5xl font-bold mb-6">The Tomoe Gozen Story</h2>
                <p class="text-white/70 text-lg mb-4 leading-relaxed">
                    Inspired by the legendary warrior Tomoe Gozen, our brand embodies strength, elegance, and bold individuality. 
                    We craft oversize Japanese streetwear that merges traditional aesthetics with modern street culture.
                </p>
                <p class="text-white/70 text-lg mb-6 leading-relaxed">
                    Each piece is designed for those who aren't afraid to stand out. From Tokyo's vibrant streets to your wardrobe, 
                    experience the fusion of Japanese craftsmanship and contemporary style.
                </p>
                <a href="{{ route('catalog') }}" class="btn btn-primary inline-flex">
                    Explore Our Story
                </a>
            </div>
            <div class="relative">
                <div class="aspect-square rounded-2xl overflow-hidden border border-white/10">
                    <img src="{{ asset('images/tomoe_logo4.png') }}" alt="Tomoe Gozen Brand" 
                        class="w-full h-full object-cover opacity-80">
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="max-w-4xl mx-auto px-4 py-20 bg-gradient-to-r from-red-900/20 to-black border-y border-white/5">
        <div class="text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Stay Updated</h2>
            <p class="text-white/70 mb-8 max-w-xl mx-auto">
                Be the first to know about new drops, exclusive collections, and special offers.
            </p>
            <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                <input 
                    type="email" 
                    placeholder="Enter your email" 
                    class="flex-1 px-6 py-4 rounded-full bg-white/5 border border-white/10 text-white placeholder-white/50 focus:outline-none focus:border-red-500 transition-colors"
                    required
                >
                <button type="submit" class="btn btn-primary px-8 py-4 whitespace-nowrap">
                    Subscribe
                </button>
            </form>
        </div>
    </section>

    @push('scripts')
        <script>
            // Video background error handling
            document.addEventListener('DOMContentLoaded', function() {
                const video = document.querySelector('video');
                if (video) {
                    video.addEventListener('error', function() {
                        console.log('Video failed to load, using fallback');
                        this.style.display = 'none';
                    });
                }
            });
        </script>
    @endpush
@endsection
