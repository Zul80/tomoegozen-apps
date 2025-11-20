@extends('layouts.app')

@section('title', 'Tomoe Gozen — Oversize Japanese Streetwear')

@section('content')
    <!-- Hero Section with Video Background -->
    <section class="relative min-h-[100vh] flex items-center justify-center overflow-hidden">
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
        <div class="relative z-10 text-center text-white px-6" style="margin-top: 250px; margin-bottom: 250px; display: flex; flex-direction: column; justify-content: center;">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 drop-shadow-lg" style="font-family: 'BEBAS NEUE', sans-serif; font-size: 4rem;">
                Oversize Japanese Streetwear<br>
                <span class="text-red-500">Tomoe Gozen</span>
            </h1>
                <p class="text-lg md:text-2xl text-white/70 mb-8 max-w-2xl mx-auto">
                    Elevate your look with fearless, oversize streetwear inspired by the legendary Tomoe Gozen.<br>
                    Where bold Japanese art meets modern Tokyo fashion, every drop is a statement.<br>
                    Stand out. Wear your story. Lead the culture.
                </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="{{ route('catalog') }}" class="btn btn-primary px-8 py-4 text-lg">Shop Catalog</a>
                <a href="{{ route('collections.index') }}" class="btn btn-ghost px-8 py-4 text-lg">View Collections</a>
            </div>
        <div class="flex justify-center mt-12">
            <div id="lottie-scroll-icon" style="width: 60px; height: 60px; margin: 0 auto;"></div>
        </div>
        @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.10.2/lottie.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if(document.getElementById('lottie-scroll-icon')) {
                    lottie.loadAnimation({
                        container: document.getElementById('lottie-scroll-icon'),
                        renderer: 'svg',
                        loop: true,
                        autoplay: true,
                        path: '{{ asset('json/scroll-mouse.json') }}' // Put your Lottie JSON file here (store in public/json)
                    });
                }
            });
        </script>
        @endpush
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="max-w-7xl mx-auto px-4 py-20 bg-gradient-to-b from-black to-gray-950" style="margin-top: 100px;">
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
                        <h3 class="text-xl font-semibold">{{ $product->name }}</h3>
                        <p class="text-sm text-white/60 h-12 overflow-hidden">{{ $product->description }}</p>
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-red-400">Rp. {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}</span>
                            @if ($product->sale_price)
                                <span class="line-through text-white/40 text-sm">Rp. {{ number_format($product->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <a href="{{ route('catalog') }}?search={{ urlencode($product->name) }}" class="btn btn-ghost btn-sm mt-2">View Product</a>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="flex justify-center">
            <a href="{{ route('catalog') }}" class="btn btn-primary px-8 py-4 text-lg">Browse Full Catalog</a>
        </div>
    </section>

    <!-- About / Brand Story Section -->
    <section class="max-w-5xl mx-auto px-4 py-16 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">The Legend of Tomoe Gozen</h2>
        <p class="text-white/70 text-lg mb-6">
            Tomoe Gozen was a fearless samurai, celebrated for her prowess and honor in the legendary tales of Japan. We channel her spirit, crafting streetwear that fuses audacious design with Japanese heritage. Our garments are more than fashion—they’re modern armor for the bold.
        </p>
        <a href="{{ route('collections.index') }}" class="btn btn-ghost px-8 py-4 text-lg">Explore Collections</a>
    </section>
@endsection
