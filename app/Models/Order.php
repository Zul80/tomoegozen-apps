<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'payment_status',
        'subtotal',
        'discount_total',
        'tax_total',
        'grand_total',
        'currency',
        'items_count',
        'shipping_address',
        'meta',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'discount_total' => 'integer',
        'tax_total' => 'integer',
        'grand_total' => 'integer',
        'items_count' => 'integer',
        'shipping_address' => 'array',
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

