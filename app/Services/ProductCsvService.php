<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductCsvService
{
    public function stream(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products-export.csv"',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
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
                'category_id',
                'tags',
                'image_url',
                'is_featured',
            ]);

            Product::chunk(200, function ($products) use ($handle) {
                foreach ($products as $product) {
                    fputcsv($handle, [
                        $product->sku,
                        $product->name,
                        $product->slug,
                        $product->description,
                        $product->price,
                        $product->sale_price,
                        $product->currency,
                        json_encode($product->colors),
                        json_encode($product->sizes),
                        json_encode($product->stock_by_size),
                        $product->category_id,
                        json_encode($product->tags),
                        $product->image_url,
                        $product->is_featured ? '1' : '0',
                    ]);
                }
            });

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function import(UploadedFile $file): int
    {
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);

        $created = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            $colors = json_decode($data['colors'] ?: '[]', true) ?: [];
            $sizes = json_decode($data['sizes'] ?: '[]', true) ?: [];
            $stock = json_decode($data['stock_by_size'] ?: '{}', true) ?: [];

            Product::updateOrCreate(
                ['sku' => $data['sku']],
                [
                    'name' => $data['name'],
                    'slug' => $data['slug'],
                    'description' => $data['description'],
                    'price' => (int) $data['price'],
                    'sale_price' => $data['sale_price'] ? (int) $data['sale_price'] : null,
                    'currency' => $data['currency'] ?? 'IDR',
                    'colors' => array_map('strtolower', $colors),
                    'sizes' => array_map('strtoupper', $sizes),
                    'stock_by_size' => collect($stock)->map(fn ($value) => (int) $value)->toArray(),
                    'category_id' => (int) $data['category_id'],
                    'tags' => array_map('strtolower', json_decode($data['tags'] ?: '[]', true) ?: []),
                    'image_url' => $data['image_url'],
                    'is_featured' => (bool) ($data['is_featured'] ?? false),
                ]
            );
            $created++;
        }

        fclose($handle);

        return $created;
    }
}

