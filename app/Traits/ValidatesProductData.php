<?php

namespace App\Traits;

use Illuminate\Validation\Rule;

trait ValidatesProductData
{
    protected function productRules(?int $productId = null): array
    {
        return [
            'sku' => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($productId)],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('products', 'slug')->ignore($productId)],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'sale_price' => ['nullable', 'integer', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'colors' => ['nullable', 'array'],
            'colors.*' => ['string', 'max:50'],
            'sizes' => ['nullable', 'array'],
            'sizes.*' => ['string', 'max:5'],
            'stock_by_size' => ['nullable', 'array'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'image_url' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['boolean'],
            'category_id' => ['required', 'exists:categories,id'],
            'collection_id' => ['nullable', 'exists:collections,id'],
            'metadata' => ['nullable', 'array'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    protected function normalizeProductPayload(array $payload): array
    {
        if (isset($payload['colors']) && is_array($payload['colors'])) {
            $payload['colors'] = array_map('strtolower', $payload['colors']);
        }

        if (isset($payload['sizes']) && is_array($payload['sizes'])) {
            $payload['sizes'] = array_map('strtoupper', $payload['sizes']);
        }

        if (isset($payload['tags']) && is_array($payload['tags'])) {
            $payload['tags'] = array_map('strtolower', $payload['tags']);
        }

        if (isset($payload['stock_by_size']) && is_array($payload['stock_by_size'])) {
            $payload['stock_by_size'] = collect($payload['stock_by_size'])
                ->map(fn ($value) => (int) $value)
                ->toArray();
        }

        return $payload;
    }
}

