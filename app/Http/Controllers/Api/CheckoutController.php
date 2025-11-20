<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function __construct(private readonly CartService $cartService)
    {
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'session_id' => ['nullable', 'uuid'],
            'shipping_address.line1' => ['required', 'string', 'max:255'],
            'shipping_address.city' => ['required', 'string', 'max:120'],
            'shipping_address.postal_code' => ['required', 'string', 'max:20'],
            'shipping_address.country' => ['required', 'string', 'max:3'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $cart = $this->cartService->getOrCreate(
            $request->user()?->id,
            $validated['session_id'] ?? null
        )->load('items.product');

        if ($cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 422);
        }

        $order = Order::create([
            'order_number' => 'TGZ-' . Str::upper(Str::random(8)),
            'user_id' => $request->user()?->id,
            'status' => 'processing',
            'payment_status' => 'paid',
            'subtotal' => $cart->subtotal,
            'grand_total' => $cart->subtotal,
            'currency' => $cart->currency,
            'items_count' => $cart->items_count,
            'shipping_address' => $validated['shipping_address'],
            'meta' => [
                'notes' => $validated['notes'] ?? null,
                'simulated_payment' => true,
            ],
        ]);

        foreach ($cart->items as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'sku' => $cartItem->sku,
                'name' => $cartItem->name,
                'color' => $cartItem->color,
                'size' => $cartItem->size,
                'quantity' => $cartItem->quantity,
                'unit_price' => $cartItem->unit_price,
                'line_total' => $cartItem->line_total,
            ]);
        }

        $cart->items()->delete();
        $this->cartService->refreshCartTotals($cart->fresh());

        return (new OrderResource($order->load('items')))
            ->additional(['message' => 'Order created (mock payment confirmed)']);
    }
}

