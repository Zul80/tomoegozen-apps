<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductPageController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category', 'collection')->paginate(15);
        $categories = Category::all();
        $collections = \App\Models\Collection::all();

        $editing = null;
        if ($editId = $request->query('edit')) {
            $editing = Product::find($editId);
        }

        return view('admin.products', compact('products', 'categories', 'collections', 'editing'));
    }
}

