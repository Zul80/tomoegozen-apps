<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'currency' => $this->currency,
            'colors' => $this->colors,
            'sizes' => $this->sizes,
            'stock_by_size' => $this->stock_by_size,
            'tags' => $this->tags,
            'image_url' => $this->image_url,
            'is_featured' => $this->is_featured,
            'category' => $this->whenLoaded('category', fn () => [
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ]),
            'collection' => $this->whenLoaded('collection', fn () => [
                'title' => $this->collection->title,
                'slug' => $this->collection->slug,
            ]),
        ];
    }
}

