<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'sku',
        'name',
        'color',
        'size',
        'quantity',
        'unit_price',
        'line_total',
        'meta',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'integer',
        'line_total' => 'integer',
        'meta' => 'array',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

