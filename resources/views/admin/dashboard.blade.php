@extends('layouts.admin')

@section('title', 'Admin Dashboard — Tomoe Gozen')

@section('content')
    <section class="max-w-6xl mx-auto space-y-10">
        <header class="flex items-center justify-between">
            <div>
                <p class="text-white/60 uppercase tracking-[0.4em] text-[10px]">Admin</p>
                <h1 class="text-3xl font-semibold">Store Pulse</h1>
            </div>
            <div class="text-sm text-white/60">Basic Auth (admin@example.com / Admin123!)</div>
        </header>

        <div class="grid md:grid-cols-4 sm:grid-cols-2 gap-6">
            <div class="stat-glass">
                <p class="text-white/60 text-xs uppercase tracking-[0.4em]">Total Sales</p>
                <p class="text-3xl font-bold text-red-500">Rp. {{ number_format($metrics['total_sales'], 0, ',', '.') }}</p>
            </div>
            <div class="stat-glass">
                <p class="text-white/60 text-xs uppercase tracking-[0.4em]">Orders</p>
                <p class="text-3xl font-bold">{{ $metrics['total_orders'] }}</p>
            </div>
            <div class="stat-glass">
                <p class="text-white/60 text-xs uppercase tracking-[0.4em]">Customers</p>
                <p class="text-3xl font-bold">{{ $metrics['total_customers'] }}</p>
            </div>
            <div class="stat-glass">
                <p class="text-white/60 text-xs uppercase tracking-[0.4em]">Products</p>
                <p class="text-3xl font-bold">{{ $metrics['total_products'] }}</p>
            </div>
        </div>

        <section class="grid md:grid-cols-3 gap-6">
            <a href="{{ route('admin.products') }}" class="stat-glass hover:border-red-500/60 transition-colors cursor-pointer group">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-2xl">🛍️</span>
                    <span class="text-xs text-white/60 group-hover:text-red-400 transition-colors">Manage →</span>
                </div>
                <p class="text-white/60 text-xs uppercase tracking-[0.4em] mb-1">Products</p>
                <p class="text-2xl font-bold">{{ $metrics['total_products'] }}</p>
            </a>
            <a href="{{ route('admin.categories') }}" class="stat-glass hover:border-red-500/60 transition-colors cursor-pointer group">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-2xl">📁</span>
                    <span class="text-xs text-white/60 group-hover:text-red-400 transition-colors">Manage →</span>
                </div>
                <p class="text-white/60 text-xs uppercase tracking-[0.4em] mb-1">Categories</p>
                <p class="text-2xl font-bold">{{ \App\Models\Category::count() }}</p>
            </a>
            <a href="{{ route('admin.collections') }}" class="stat-glass hover:border-red-500/60 transition-colors cursor-pointer group">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-2xl">🎨</span>
                    <span class="text-xs text-white/60 group-hover:text-red-400 transition-colors">Manage →</span>
                </div>
                <p class="text-white/60 text-xs uppercase tracking-[0.4em] mb-1">Collections</p>
                <p class="text-2xl font-bold">{{ \App\Models\Collection::count() }}</p>
            </a>
        </section>

        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold">Latest Orders</h2>
                <a href="{{ route('admin.orders') }}" class="text-sm text-white/60 hover:text-red-400 transition-colors">View All →</a>
            </div>
            <div class="overflow-x-auto border border-white/5 rounded-2xl">
                <table class="w-full text-sm">
                    <thead class="text-white/60 uppercase tracking-[0.4em] text-[10px]">
                        <tr>
                            <th class="text-left p-4">Order</th>
                            <th class="text-left p-4">Customer</th>
                            <th class="text-left p-4">Status</th>
                            <th class="text-right p-4">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($latestOrders as $order)
                            <tr class="border-t border-white/5">
                                <td class="p-4 font-semibold">{{ $order->order_number }}</td>
                                <td class="p-4 text-white/70">{{ $order->user->name ?? 'Guest' }}</td>
                                <td class="p-4 text-red-400 uppercase tracking-[0.3em]">{{ $order->status }}</td>
                                <td class="p-4 text-right font-semibold">Rp. {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-white/60">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </section>
@endsection

