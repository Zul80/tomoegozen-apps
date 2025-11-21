<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-black text-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — Tomoe Gozen</title>
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
<body class="font-inter bg-gradient-to-b from-black via-gray-950 to-black text-white min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-4">
        <div class="text-center mb-8">
            <img src="{{ asset('images/tomoe_logo4.png') }}" alt="Tomoe Gozen Logo" class="mx-auto mb-4" style="width: 150px; height: auto;">
            <p class="text-white/60 uppercase tracking-[0.4em] text-[10px]">Admin Panel</p>
            <h1 class="text-3xl font-semibold mt-2">Login</h1>
        </div>

        <div class="border border-white/5 rounded-3xl p-8 space-y-6">
            @if ($errors->any())
                <div class="stat-glass text-sm text-red-400">
                    {!! implode('<br>', $errors->all()) !!}
                </div>
            @endif

            <form action="{{ route('admin.login') }}" method="POST" class="space-y-4">
                @csrf

                <label class="block space-y-2">
                    <span class="text-white/60 text-xs uppercase tracking-[0.2em]">Email</span>
                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required 
                        autofocus
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 normal-case focus:outline-none focus:border-red-500/50 focus:ring-1 focus:ring-red-500/50 transition-colors"
                        placeholder="admin@example.com">
                </label>

                <label class="block space-y-2">
                    <span class="text-white/60 text-xs uppercase tracking-[0.2em]">Password</span>
                    <input 
                        type="password" 
                        name="password" 
                        required
                        class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 normal-case focus:outline-none focus:border-red-500/50 focus:ring-1 focus:ring-red-500/50 transition-colors"
                        placeholder="••••••••">
                </label>

                <label class="flex items-center gap-2 text-sm text-white/70">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        class="rounded border-white/20 bg-black/40 text-red-600 focus:ring-red-500/50">
                    <span>Remember me</span>
                </label>

                <button type="submit" class="w-full btn btn-primary">
                    Login
                </button>
            </form>

            <div class="pt-4 border-t border-white/5 text-center">
                <p class="text-xs text-white/60">
                    Default credentials:<br>
                    <span class="font-mono text-white/80">admin@example.com / Admin123!</span>
                </p>
            </div>

            <div class="text-center">
                <a href="{{ route('home') }}" class="text-sm text-white/60 hover:text-red-400 transition-colors">
                    ← Back to Store
                </a>
            </div>
        </div>
    </div>
</body>
</html>

