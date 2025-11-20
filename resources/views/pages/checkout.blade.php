@extends('layouts.app')

@section('title', 'Checkout — Tomoe Gozen')

@section('content')
    <section class="max-w-3xl mx-auto px-4 py-16 space-y-6">
        <header>
            <p class="text-white/60 uppercase tracking-[0.4em] text-[10px]">Checkout</p>
            <h1 class="text-3xl font-semibold">Mock payment — no gateway required.</h1>
        </header>
        <form id="checkout-form" class="space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
                <label class="space-y-2 text-sm uppercase tracking-[0.3em]">
                    <span class="text-white/60">Address Line</span>
                    <input type="text" name="shipping_address[line1]" required
                        class="w-full bg-black/50 border border-white/10 rounded-2xl px-4 py-3">
                </label>
                <label class="space-y-2 text-sm uppercase tracking-[0.3em]">
                    <span class="text-white/60">City</span>
                    <input type="text" name="shipping_address[city]" required
                        class="w-full bg-black/50 border border-white/10 rounded-2xl px-4 py-3">
                </label>
                <label class="space-y-2 text-sm uppercase tracking-[0.3em]">
                    <span class="text-white/60">Postal</span>
                    <input type="text" name="shipping_address[postal_code]" required
                        class="w-full bg-black/50 border border-white/10 rounded-2xl px-4 py-3">
                </label>
                <label class="space-y-2 text-sm uppercase tracking-[0.3em]">
                    <span class="text-white/60">Country</span>
                    <input type="text" name="shipping_address[country]" required value="ID"
                        class="w-full bg-black/50 border border-white/10 rounded-2xl px-4 py-3">
                </label>
            </div>
            <label class="space-y-2 text-sm uppercase tracking-[0.3em]">
                <span class="text-white/60">Notes</span>
                <textarea name="notes" rows="3" class="w-full bg-black/50 border border-white/10 rounded-2xl px-4 py-3"></textarea>
            </label>
            <button class="btn btn-primary w-full justify-center text-lg">Simulate Checkout</button>
        </form>
        <div id="checkout-result" class="stat-glass hidden"></div>
    </section>
@endsection

@push('scripts')
    <script>
        const axiosInstance = window.axios;
        const form = document.getElementById('checkout-form');
        const result = document.getElementById('checkout-result');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const formData = new FormData(form);
            const payload = Object.fromEntries(formData.entries());

            payload['shipping_address'] = {
                line1: formData.get('shipping_address[line1]'),
                city: formData.get('shipping_address[city]'),
                postal_code: formData.get('shipping_address[postal_code]'),
                country: formData.get('shipping_address[country]'),
            };

            delete payload['shipping_address[line1]'];
            delete payload['shipping_address[city]'];
            delete payload['shipping_address[postal_code]'];
            delete payload['shipping_address[country]'];

            payload.session_id = localStorage.getItem('tgz_cart_session');

            try {
                const { data } = await axiosInstance.post('/api/checkout', payload);
                result.classList.remove('hidden');
                result.innerHTML = `
                    <p class="text-lg font-semibold text-red-400">${data.message}</p>
                    <p class="text-white/60 text-sm">Order #${data.data?.order_number ?? ''} — Total Rp ${new Intl.NumberFormat('id-ID').format(data.data?.grand_total ?? 0)}</p>
                `;
            } catch (error) {
                alert('Checkout failed: ' + (error.response?.data?.message ?? error.message));
            }
        });
    </script>
@endpush

