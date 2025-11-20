<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function show(Request $request): CartResource
    {
        $cart = $this->cartService->getOrCreate(
            $request->user()?->id,
            $request->input('session_id')
        );

        return new CartResource($cart->load('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:10'],
            'color' => ['nullable', 'string', 'max:50'],
            'size' => ['nullable', 'string', 'max:5'],
            'session_id' => ['nullable', 'uuid'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $cart = $this->cartService->getOrCreate(
            $request->user()?->id,
            $validated['session_id'] ?? null
        );

        $product = Product::findOrFail($validated['product_id']);

        $updated = $this->cartService->addOrUpdateItem($cart, $product, $validated);

        return (new CartResource($updated))
            ->additional([
                'message' => 'Item added to cart',
                'session_id' => $updated->session_id,
            ]);
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        $cart = $cartItem->cart;

        $ownsCart = $request->user()?->id && $cart->user_id === $request->user()->id;
        $matchesSession = $request->input('session_id') && $cart->session_id === $request->input('session_id');

        abort_if(! $ownsCart && ! $matchesSession, 403, 'Unauthorized to edit this cart.');

        $cartItem->delete();

        $this->cartService->refreshCartTotals($cart);

        return response()->json([
            'message' => 'Item removed',
            'cart' => new CartResource($cart->fresh('items')),
        ]);
    }
}

