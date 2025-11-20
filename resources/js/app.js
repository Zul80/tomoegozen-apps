import './bootstrap';
import Alpine from 'alpinejs';
import axios from 'axios';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import lottie from 'lottie-web';

window.Alpine = Alpine;
Alpine.start();

gsap.registeRplugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    animateHero();
    animateProducts();
    hydrateHeroLottie();
    bootstrapProductGrid();
    bindCartButtons();
    setupOptionButtons();
});

function animateHero() {
    const heroElements = document.querySelectorAll('[data-animate="hero"]');
    if (!heroElements.length) {
        return;
    }

    gsap.from(heroElements, {
        y: 40,
        opacity: 0,
        stagger: 0.15,
        duration: 1,
        ease: 'power3.out',
    });
}

function animateProducts() {
    const cards = document.querySelectorAll('[data-animate="card"]');
    if (!cards.length) {
        return;
    }

    gsap.from(cards, {
        scrollTrigger: {
            trigger: cards[0],
            start: 'top 80%',
        },
        y: 80,
        opacity: 0,
        stagger: 0.08,
        duration: 0.85,
        ease: 'power2.out',
    });
}

function hydrateHeroLottie() {
    const container = document.querySelector('[data-lottie="hero"]');
    if (!container) {
        return;
    }

    lottie.loadAnimation({
        container,
        renderer: 'svg',
        loop: true,
        autoplay: true,
        path: '/lottie/hero.json',
    });
}

async function bootstrapProductGrid() {
    const grid = document.querySelector('[data-products-grid]');
    if (!grid || !grid.dataset.source) {
        return;
    }

    try {
        const params = new URLSearchParams({
            per_page: grid.dataset.peRpage || 6,
            sort: grid.dataset.sort || 'newest',
            search: grid.dataset.search || '',
        });

        const { data } = await axios.get(`/api/products?${params.toString()}`);
        grid.innerHTML = '';
        data.data.forEach((product) => grid.appendChild(rendeRproductCard(product)));
    } catch (error) {
        grid.innerHTML = `<p class="text-red-400">Failed to load products. ${error.message}</p>`;
    }
}

function rendeRproductCard(product) {
    const card = document.createElement('article');
    card.className = 'product-card';
    card.innerHTML = `
        <div class="relative overflow-hidden rounded-2xl border border-white/5">
            <img src="${product.image_url}" alt="${product.name}" class="w-full aspect-[4/3] object-cover" loading="lazy">
            <span class="absolute top-4 left-4 text-[10px] tracking-[0.4em] uppercase bg-black/70 px-3 py-1 rounded-full">
                ${product.is_featured ? 'Featured' : 'Drop'}
            </span>
        </div>
        <div class="flex flex-col gap-2">
            <h3 class="font-semibold text-lg">${product.name}</h3>
            <p class="text-white/60 text-sm">${product.description?.slice(0, 90) ?? ''}...</p>
            <div class="flex items-center gap-3 text-sm">
                <span class="text-red-400 font-bold">Rp. ${formatCurrency(product.sale_price ?? product.price)}</span>
                ${product.sale_price ? `<span class="line-through text-white/40 text-xs">Rp. ${formatCurrency(product.price)}</span>` : ''}
            </div>
        </div>
        <a href="/products/${product.slug}" class="btn btn-ghost justify-center">View Detail</a>
    `;
    return card;
}

function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID').format(value);
}

function bindCartButtons() {
    const buttons = document.querySelectorAll('[data-add-to-cart]');
    if (!buttons.length) {
        return;
    }

    buttons.forEach((button) =>
        button.addEventListener('click', async () => {
            const wrapper = button.closest('[data-product-options]');
            const payload = {
                product_id: button.dataset.productId,
                quantity: Number(button.dataset.quantity || 1),
                size: wrapper?.dataset.selectedSize ?? button.dataset.size ?? null,
                color: wrapper?.dataset.selectedColor ?? button.dataset.color ?? null,
                session_id: localStorage.getItem('tgz_cart_session'),
            };

            try {
                const { data } = await axios.post('/api/cart', payload);
                if (data.session_id) {
                    localStorage.setItem('tgz_cart_session', data.session_id);
                }
                button.innerText = 'Added!';
                setTimeout(() => (button.innerText = 'Add to cart'), 2000);
            } catch (error) {
                alert('Failed to add to cart: ' + error.message);
            }
        })
    );
}

function setupOptionButtons() {
    const wrappers = document.querySelectorAll('[data-product-options]');
    wrappers.forEach((wrapper) => {
        wrapper.querySelectorAll('[data-size-option]').forEach((button) => {
            button.addEventListener('click', () => {
                wrapper.dataset.selectedSize = button.dataset.sizeOption;
                toggleOptionState(wrapper.querySelectorAll('[data-size-option]'), button);
            });
        });

        wrapper.querySelectorAll('[data-color-option]').forEach((button) => {
            button.addEventListener('click', () => {
                wrapper.dataset.selectedColor = button.dataset.colorOption;
                toggleOptionState(wrapper.querySelectorAll('[data-color-option]'), button);
            });
        });
    });
}

function toggleOptionState(buttons, activeButton) {
    buttons.forEach((btn) => {
        btn.classList.remove('border-red-500', 'text-red-400');
    });
    activeButton.classList.add('border-red-500', 'text-red-400');
}
