@extends('layouts.admin')

@section('title', 'Admin Orders — Tomoe Gozen')

@section('content')
    <section class="max-w-6xl mx-auto space-y-8">
        <header>
            <p class="text-white/60 uppercase tracking-[0.4em] text-[10px]">Admin</p>
            <h1 class="text-3xl font-semibold">Orders</h1>
        </header>

        <div class="overflow-x-auto border border-white/5 rounded-2xl">
            <table class="w-full text-sm">
                <thead class="text-white/60 uppercase tracking-[0.4em] text-[10px]">
                    <tr>
                        <th class="p-4 text-left">Order</th>
                        <th class="p-4 text-left">Customer</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-right">Total</th>
                        <th class="p-4 text-right">Items</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="border-t border-white/5">
                            <td class="p-4 font-semibold">{{ $order->order_number }}</td>
                            <td class="p-4 text-white/70">{{ $order->user->name ?? 'Guest' }}</td>
                            <td class="p-4 text-red-400 uppercase tracking-[0.3em]">{{ $order->status }}</td>
                            <td class="p-4 text-right font-semibold">Rp. {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                            <td class="p-4 text-right">{{ $order->items_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $orders->links() }}
    </section>
@endsection

