<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-black text-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Admin Panel — Tomoe Gozen')">
    <title>@yield('title', 'Admin — Tomoe Gozen')</title>
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
<body class="font-inter bg-gradient-to-b from-black via-gray-950 to-black text-white min-h-screen" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0 bg-black/80 border-r border-white/5">
            <div class="flex-1 flex flex-col pt-6 pb-4 overflow-y-auto">
                <div class="flex items-center flex-shrink-0 px-6 mb-8">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                        <img src="{{ asset('images/tomoe_logo4.png') }}" alt="Tomoe Gozen Logo" style="width: 100px; height: auto;">
                        <span class="text-xs uppercase tracking-[0.4em] text-white/60">Admin</span>
                    </a>
                </div>
                <nav class="flex-1 px-4 space-y-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-red-600/20 text-red-400 border border-red-600/30' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <span class="mr-3">📊</span>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.products') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.products*') ? 'bg-red-600/20 text-red-400 border border-red-600/30' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <span class="mr-3">🛍️</span>
                        Products
                    </a>
                    <a href="{{ route('admin.categories') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.categories*') ? 'bg-red-600/20 text-red-400 border border-red-600/30' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <span class="mr-3">📁</span>
                        Categories
                    </a>
                    <a href="{{ route('admin.collections') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.collections*') ? 'bg-red-600/20 text-red-400 border border-red-600/30' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <span class="mr-3">🎨</span>
                        Collections
                    </a>
                    <a href="{{ route('admin.orders') }}" 
                       class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.orders*') ? 'bg-red-600/20 text-red-400 border border-red-600/30' : 'text-white/70 hover:bg-white/5 hover:text-white' }}">
                        <span class="mr-3">📦</span>
                        Orders
                    </a>
                </nav>
                <div class="px-4 pt-4 border-t border-white/5 space-y-2">
                    <a href="{{ route('home') }}" class="flex items-center px-4 py-3 text-sm font-medium text-white/60 hover:text-white rounded-xl transition-colors hover:bg-white/5">
                        <span class="mr-3">🏠</span>
                        Back to Store
                    </a>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-3 text-sm font-medium text-white/60 hover:text-white rounded-xl transition-colors hover:bg-white/5">
                            <span class="mr-3">🚪</span>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Mobile sidebar -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 md:hidden" 
             x-on:click="sidebarOpen = false">
            <div class="fixed inset-y-0 left-0 w-64 bg-black/95 border-r border-white/5">
                <div class="flex-1 flex flex-col pt-6 pb-4 overflow-y-auto">
                    <div class="flex items-center justify-between px-6 mb-8">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                            <img src="{{ asset('images/tomoe_logo4.png') }}" alt="Tomoe Gozen Logo" style="width: 100px; height: auto;">
                            <span class="text-xs uppercase tracking-[0.4em] text-white/60">Admin</span>
                        </a>
                        <button x-on:click="sidebarOpen = false" class="text-white/60 hover:text-white">✕</button>
                    </div>
                    <nav class="flex-1 px-4 space-y-2">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-red-600/20 text-red-400 border border-red-600/30' : 'text-white/70 hover:bg-white/5 hover:text-white' }}"
                           x-on:click="sidebarOpen = false">
                            <span class="mr-3">📊</span>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.products') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.products*') ? 'bg-red-600/20 text-red-400 border border-red-600/30' : 'text-white/70 hover:bg-white/5 hover:text-white' }}"
                           x-on:click="sidebarOpen = false">
                            <span class="mr-3">🛍️</span>
                            Products
                        </a>
                        <a href="{{ route('admin.categories') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.categories*') ? 'bg-red-600/20 text-red-400 border border-red-600/30' : 'text-white/70 hover:bg-white/5 hover:text-white' }}"
                           x-on:click="sidebarOpen = false">
                            <span class="mr-3">📁</span>
                            Categories
                        </a>
                        <a href="{{ route('admin.collections') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.collections*') ? 'bg-red-600/20 text-red-400 border border-red-600/30' : 'text-white/70 hover:bg-white/5 hover:text-white' }}"
                           x-on:click="sidebarOpen = false">
                            <span class="mr-3">🎨</span>
                            Collections
                        </a>
                        <a href="{{ route('admin.orders') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ request()->routeIs('admin.orders*') ? 'bg-red-600/20 text-red-400 border border-red-600/30' : 'text-white/70 hover:bg-white/5 hover:text-white' }}"
                           x-on:click="sidebarOpen = false">
                            <span class="mr-3">📦</span>
                            Orders
                        </a>
                    </nav>
                    <div class="px-4 pt-4 border-t border-white/5 space-y-2">
                        <a href="{{ route('home') }}" 
                           class="flex items-center px-4 py-3 text-sm font-medium text-white/60 hover:text-white rounded-xl transition-colors hover:bg-white/5"
                           x-on:click="sidebarOpen = false">
                            <span class="mr-3">🏠</span>
                            Back to Store
                        </a>
                        <form action="{{ route('admin.logout') }}" method="POST" x-on:click="sidebarOpen = false">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-3 text-sm font-medium text-white/60 hover:text-white rounded-xl transition-colors hover:bg-white/5">
                                <span class="mr-3">🚪</span>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex-1 md:ml-64">
            <!-- Top bar -->
            <header class="sticky top-0 z-30 backdrop-blur bg-black/70 border-b border-white/5">
                <div class="flex items-center justify-between px-4 py-4">
                    <button x-on:click="sidebarOpen = !sidebarOpen" class="md:hidden text-white">
                        <span class="sr-only">Toggle menu</span>
                        ☰
                    </button>
                    <div class="flex-1"></div>
                    <div class="text-xs text-white/60 uppercase tracking-[0.3em]">
                        Admin Panel
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="p-4 md:p-8">
                @if (session('status'))
                    <div class="mb-6 stat-glass text-sm text-green-400">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 stat-glass text-sm text-red-400">
                        {!! implode('<br>', $errors->all()) !!}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>

