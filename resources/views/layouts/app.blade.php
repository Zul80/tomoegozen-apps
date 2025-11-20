<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-black text-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Tomoe Gozen Oversize Japanese Streetwear Shop')">
    <title>@yield('title', config('app.name', 'Tomoe Gozen'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Kanit:wght@600&display=swap" rel="stylesheet">
    @php
        $viteManifest = public_path('build/manifest.json');
        $fallbackCss = null;
        $fallbackJs = null;

        if (! file_exists($viteManifest)) {
            $buildAssetsPath = public_path('build/assets');
            if (is_dir($buildAssetsPath)) {
                $cssMatches = glob($buildAssetsPath.'/app-*.css') ?: [];
                $jsMatches = glob($buildAssetsPath.'/app-*.js') ?: [];

                $fallbackCss = $cssMatches[0] ?? null;
                $fallbackJs = $jsMatches[0] ?? null;
            }
        }
    @endphp

    @if (file_exists($viteManifest))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        @if ($fallbackCss)
            <link rel="stylesheet" href="{{ asset('build/assets/'.basename($fallbackCss)) }}">
        @endif
        @if ($fallbackJs)
            <script type="module" src="{{ asset('build/assets/'.basename($fallbackJs)) }}" defer></script>
        @endif
    @endif
</head>
<body class="font-inter bg-gradient-to-b from-black via-gray-950 to-black text-white min-h-screen" x-data>
    <header class="sticky top-0 z-50 backdrop-blur bg-black/70 border-b border-white/5">
        <div class="max-w-6xl mx-auto flex items-center justify-between p-4">
            <a href="{{ url('/') }}" class="text-white text-xl font-bold tracking-widest flex items-center gap-2">
                <img src="{{ asset('images/tomoe_logo4.png') }}" alt="Tomoe Gozen Logo" style="width: 120px; height: auto;">
            </a>
            <nav class="hidden md:flex gap-6 font-semibold text-sm uppercase tracking-widest">
                <a href="{{ route('collections.index') }}" class="nav-link">Collections</a>
                <a href="{{ route('catalog') }}" class="nav-link">Catalog</a>
                <a href="{{ route('cart.show') }}" class="nav-link">Cart</a>
                <a href="{{ route('admin.dashboard') }}" class="nav-link text-red-500">Admin</a>
            </nav>
            <button class="md:hidden text-white" x-on:click="$dispatch('toggle-mobile-menu')">
                <span class="sr-only">Toggle menu</span>
                ☰
            </button>
        </div>
    </header>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <footer class="border-t border-white/5 bg-black">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('images/tomoe_logo4.png') }}" alt="Tomoe Gozen Logo" style="width: 180px; height: auto;">
                    </div>
                    <p class="text-white/60 text-sm leading-relaxed">
                        Japanese streetwear inspired by the legendary warrior. Bold designs for the modern street culture enthusiast.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Shop</h3>
                    <ul class="space-y-2 text-sm text-white/60">
                        <li><a href="{{ route('catalog') }}" class="hover:text-red-500 transition-colors">All Products</a></li>
                        <li><a href="{{ route('collections.index') }}" class="hover:text-red-500 transition-colors">Collections</a></li>
                        <li><a href="{{ route('cart.show') }}" class="hover:text-red-500 transition-colors">Shopping Cart</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Support</h3>
                    <ul class="space-y-2 text-sm text-white/60">
                        <li><a href="#" class="hover:text-red-500 transition-colors">Shipping Info</a></li>
                        <li><a href="#" class="hover:text-red-500 transition-colors">Returns</a></li>
                        <li><a href="#" class="hover:text-red-500 transition-colors">Size Guide</a></li>
                        <li><a href="#" class="hover:text-red-500 transition-colors">Contact Us</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Connect</h3>
                    <ul class="space-y-2 text-sm text-white/60">
                        <li><a href="#" class="hover:text-red-500 transition-colors">Instagram</a></li>
                        <li><a href="#" class="hover:text-red-500 transition-colors">Twitter</a></li>
                        <li><a href="#" class="hover:text-red-500 transition-colors">Facebook</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-white/5 pt-8 text-center text-xs tracking-[0.3em] uppercase text-white/60">
                © {{ now()->year }} Tomoe Gozen | Oversize T-Shirts — Crafted in Semarang, Indonesia. All rights reserved.
            </div>
        </div>
    </footer>

    <div id="lottie-hero" class="hidden"></div>
    @stack('scripts')
</body>
</html>

