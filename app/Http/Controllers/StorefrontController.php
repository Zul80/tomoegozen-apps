<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function home()
    {
        $featured = Product::with('category')->where('is_featured', true)->take(6)->get();
        $collections = Collection::withCount('products')->get();

        return view('pages.home', compact('featured', 'collections'));
    }

    public function catalog(Request $request)
    {
        $query = Product::query()->with('category');

        if ($categorySlug = $request->query('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($color = $request->query('color')) {
            $query->whereJsonContains('colors', strtolower($color));
        }

        if ($size = $request->query('size')) {
            $query->whereJsonContains('sizes', strtoupper($size));
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', (int) $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', (int) $request->input('price_max'));
        }

        if ($sort = $request->query('sort')) {
            match ($sort) {
                'price_asc' => $query->orderBy('price'),
                'price_desc' => $query->orderByDesc('price'),
                'newest' => $query->latest(),
                default => null,
            };
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('pages.catalog', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $related = Product::where('category_id', $product->category_id)
            ->whereKeyNot($product->getKey())
            ->take(4)
            ->get();

        return view('pages.product', compact('product', 'related'));
    }

    public function collections()
    {
        $collections = Collection::with('products')->get();

        return view('pages.collections', compact('collections'));
    }

    public function cart()
    {
        return view('pages.cart');
    }

    public function checkout()
    {
        return view('pages.checkout');
    }
}

