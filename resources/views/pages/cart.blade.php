@extends('layouts.app')

@section('title', 'Cart — Tomoe Gozen')

@section('content')
    <section class="max-w-4xl mx-auto px-4 py-16 space-y-8">
        <header>
            <p class="text-white/60 uppercase tracking-[0.4em] text-[10px]">Cart</p>
            <h1 class="text-3xl font-semibold">Your oversized tees, ready to drop.</h1>
        </header>

        <div id="cart-root" class="space-y-4"></div>
        <div class="flex justify-end">
            <a href="{{ route('checkout.show') }}" class="btn btn-primary">Go to Checkout</a>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        const axiosInstance = window.axios;
        const cartRoot = document.getElementById('cart-root');
        let session = localStorage.getItem('tgz_cart_session');

        async function fetchCart() {
            try {
                const response = await axiosInstance.get('/api/cart', { params: { session_id: session } });
                const cart = response.data.data;
                if (cart?.session_id) {
                    session = cart.session_id;
                    localStorage.setItem('tgz_cart_session', session);
                }
                renderCart(cart);
            } catch (error) {
                cartRoot.innerHTML = `<p class="text-red-400">Failed to load cart: ${error.message}</p>`;
            }
        }

        function renderCart(cart) {
            if (!cart || !cart.items || !cart.items.length) {
                cartRoot.innerHTML = `
                    <div class="stat-glass text-center space-y-4">
                        <p class="text-lg font-semibold">Cart empty</p>
                        <p class="text-white/60 text-sm">Add items from the catalog to see them here.</p>
                    </div>
                `;
                return;
            }

            cartRoot.innerHTML = cart.items.map((item) => `
                <article class="border border-white/5 rounded-2xl p-4 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold">${item.name}</h3>
                        <p class="text-sm text-white/60">Size ${item.size ?? '—'} · Color ${item.color ?? '—'}</p>
                    </div>
                    <div class="text-right">
                        <p>Qty ${item.quantity}</p>
                        <p class="text-red-400 font-semibold">Rp. ${new Intl.NumberFormat('id-ID').format(item.line_total)}</p>
                    </div>
                </article>
            `).join('') + `
                <div class="flex justify-between items-center border-t border-white/5 pt-4">
                    <p class="text-white/60 uppercase tracking-[0.4em] text-[10px]">Subtotal</p>
                    <p class="text-2xl font-bold text-red-500">Rp. ${new Intl.NumberFormat('id-ID').format(cart.subtotal)}</p>
                </div>
            `;
        }

        fetchCart();
    </script>
@endpush

