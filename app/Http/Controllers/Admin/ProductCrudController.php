<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\ValidatesProductData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductCrudController extends Controller
{
    use ValidatesProductData;

    public function store(Request $request)
    {
        $this->prepareDynamicFields($request);
        $payload = $this->normalizeProductPayload($request->validate($this->productRules()));

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $payload['image_url'] = Storage::url($path);
        }

        Product::create($payload);

        return back()->with('status', 'Product created.');
    }

    public function update(Request $request, Product $product)
    {
        $this->prepareDynamicFields($request);
        $payload = $this->normalizeProductPayload($request->validate($this->productRules($product->id)));

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $payload['image_url'] = Storage::url($path);
        }

        $product->update($payload);

        return back()->with('status', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('status', 'Product deleted.');
    }

    private function prepareDynamicFields(Request $request): void
    {
        $request->merge([
            'colors' => $this->csvToArray($request->input('colors_csv'), 'strtolower'),
            'sizes' => $this->csvToArray($request->input('sizes_csv'), 'strtoupper'),
            'tags' => $this->csvToArray($request->input('tags_csv')),
            'stock_by_size' => $this->jsonToArray($request->input('stock_by_size_json')),
        ]);
    }

    private function csvToArray(?string $value, ?callable $transform = null): ?array
    {
        if (! $value) {
            return null;
        }

        $items = collect(explode(',', $value))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->map(fn ($item) => $transform ? $transform($item) : $item)
            ->values()
            ->all();

        return empty($items) ? null : $items;
    }

    private function jsonToArray(?string $value): ?array
    {
        if (! $value) {
            return null;
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : null;
    }
}

