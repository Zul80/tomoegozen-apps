@extends('layouts.app')

@section('title', 'Admin Dashboard — Tomoe Gozen')

@section('content')
    <section class="max-w-6xl mx-auto px-4 py-12 space-y-10">
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

        <section class="space-y-4">
            <h2 class="text-xl font-semibold">Latest Orders</h2>
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
                        @foreach ($latestOrders as $order)
                            <tr class="border-t border-white/5">
                                <td class="p-4 font-semibold">{{ $order->order_number }}</td>
                                <td class="p-4 text-white/70">{{ $order->user->name ?? 'Guest' }}</td>
                                <td class="p-4 text-red-400 uppercase tracking-[0.3em]">{{ $order->status }}</td>
                                <td class="p-4 text-right font-semibold">Rp. {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </section>
@endsection

