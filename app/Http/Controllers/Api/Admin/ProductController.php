<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductCsvService;
use App\Traits\ValidatesProductData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use ValidatesProductData;

    public function __construct(private readonly ProductCsvService $csvService)
    {
    }

    public function index(Request $request)
    {
        $products = Product::query()
            ->latest()
            ->paginate((int) $request->input('per_page', 15));

        return ProductResource::collection($products);
    }

    public function store(Request $request): ProductResource
    {
        $validated = $this->normalizeProductPayload($this->validatePayload($request));

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        $product = Product::create($validated);

        return new ProductResource($product);
    }

    public function update(Request $request, Product $product): ProductResource
    {
        $validated = $this->normalizeProductPayload($this->validatePayload($request, $product->id));

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_url'] = Storage::url($path);
        }

        $product->update($validated);

        return new ProductResource($product->fresh());
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Product removed']);
    }

    public function export()
    {
        return $this->csvService->stream();
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $count = $this->csvService->import($request->file('file'));

        return response()->json(['message' => "Imported {$count} rows"]);
    }

    private function validatePayload(Request $request, ?int $productId = null): array
    {
        return $request->validate($this->productRules($productId));
    }
}

