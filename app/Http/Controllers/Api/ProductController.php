<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with(['category', 'collection']);

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $likeTerm = '%' . $search . '%';
            $query->where(function ($builder) use ($likeTerm) {
                $builder->where('name', 'like', $likeTerm)
                    ->orWhere('description', 'like', $likeTerm);
            });
        }

        $category = trim((string) $request->query('category', ''));
        if ($category !== '') {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        $color = trim((string) $request->query('color', ''));
        if ($color !== '') {
            $query->whereJsonContains('colors', strtolower($color));
        }

        $size = trim((string) $request->query('size', ''));
        if ($size !== '') {
            $query->whereJsonContains('sizes', strtoupper($size));
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', (int) $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', (int) $request->input('price_max'));
        }

        $sort = trim((string) $request->query('sort', ''));
        if ($sort !== '') {
            $query->when($sort === 'price_asc', fn ($q) => $q->orderBy('price'))
                ->when($sort === 'price_desc', fn ($q) => $q->orderByDesc('price'))
                ->when($sort === 'newest', fn ($q) => $q->latest());
        } else {
            $query->orderByDesc('is_featured')->orderBy('name');
        }

        $peRpage = (int) $request->input('per_page', 12);
        $peRpage = max(6, min($peRpage, 48));
        $products = $query->paginate($peRpage)->withQueryString();

        return ProductResource::collection($products)->additional([
            'meta' => [
                'filters' => $request->only(['search', 'category', 'color', 'size', 'price_min', 'price_max', 'sort']),
            ],
        ]);
    }

    public function show(Product $product): ProductResource
    {
        return new ProductResource($product->load(['category', 'collection']));
    }
}

