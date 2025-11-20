<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Str;

class CartService
{
    public function getOrCreate(?int $userId = null, ?string $sessionId = null): Cart
    {
        if ($userId) {
            return Cart::firstOrCreate(
                ['user_id' => $userId],
                [
                    'currency' => 'IDR',
                    'session_id' => Str::uuid()->toString(),
                ],
            );
        }

        if ($sessionId) {
            return Cart::firstOrCreate(
                ['session_id' => $sessionId],
                [
                    'currency' => 'IDR',
                ],
            );
        }

        return Cart::create([
            'currency' => 'IDR',
            'session_id' => Str::uuid()->toString(),
        ]);
    }

    public function addOrUpdateItem(Cart $cart, Product $product, array $payload): Cart
    {
        $item = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'color' => $payload['color'] ?? null,
            'size' => $payload['size'] ?? null,
        ]);

        $item->fill([
            'sku' => $product->sku,
            'name' => $product->name,
            'unit_price' => $product->sale_price ?? $product->price,
            'meta' => [
                'notes' => $payload['notes'] ?? null,
            ],
        ]);

        $newQuantity = $payload['quantity']
            ?? ($item->exists ? $item->quantity + 1 : 1);

        $item->quantity = $newQuantity;
        $item->line_total = $item->unit_price * $newQuantity;
        $item->save();

        $this->refreshCartTotals($cart);

        return $cart->fresh('items');
    }

    public function refreshCartTotals(Cart $cart): void
    {
        $cart->load('items');
        $subtotal = $cart->items->sum('line_total');

        $cart->update([
            'subtotal' => $subtotal,
            'items_count' => $cart->items->sum('quantity'),
        ]);
    }
}

