<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'slug',
        'description',
        'price',
        'sale_price',
        'currency',
        'colors',
        'sizes',
        'stock_by_size',
        'tags',
        'image_url',
        'is_featured',
        'category_id',
        'collection_id',
        'metadata',
    ];

    protected $casts = [
        'price' => 'integer',
        'sale_price' => 'integer',
        'colors' => 'array',
        'sizes' => 'array',
        'stock_by_size' => 'array',
        'tags' => 'array',
        'metadata' => 'array',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}

