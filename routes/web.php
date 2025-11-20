<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrdeRpageController;
use App\Http\Controllers\Admin\ProductCrudController;
use App\Http\Controllers\Admin\ProductCsvController;
use App\Http\Controllers\Admin\ProductPageController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/catalog', [StorefrontController::class, 'catalog'])->name('catalog');
Route::get('/collections', [StorefrontController::class, 'collections'])->name('collections.index');
Route::get('/products/{product:slug}', [StorefrontController::class, 'show'])->name('product.show');
Route::get('/cart', [StorefrontController::class, 'cart'])->name('cart.show');
Route::get('/checkout', [StorefrontController::class, 'checkout'])->name('checkout.show');

Route::prefix('admin')->name('admin.')->middleware('auth.basic')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/products', [ProductPageController::class, 'index'])->name('products');
    Route::post('/products', [ProductCrudController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductCrudController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductCrudController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/import', [ProductCsvController::class, 'import'])->name('products.import');
    Route::get('/products/export', [ProductCsvController::class, 'export'])->name('products.export');
    Route::get('/orders', [OrdeRpageController::class, 'index'])->name('orders');
});
