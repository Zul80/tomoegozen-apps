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

// Admin Login (must be before protected routes)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    
    // Products
    Route::get('/products', [ProductPageController::class, 'index'])->name('products');
    Route::post('/products', [ProductCrudController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductCrudController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductCrudController::class, 'destroy'])->name('products.destroy');
    Route::post('/products/import', [ProductCsvController::class, 'import'])->name('products.import');
    Route::get('/products/export', [ProductCsvController::class, 'export'])->name('products.export');
    
    // Categories
    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories');
    Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
    
    // Collections
    Route::get('/collections', [\App\Http\Controllers\Admin\CollectionController::class, 'index'])->name('collections');
    Route::post('/collections', [\App\Http\Controllers\Admin\CollectionController::class, 'store'])->name('collections.store');
    Route::put('/collections/{collection}', [\App\Http\Controllers\Admin\CollectionController::class, 'update'])->name('collections.update');
    Route::delete('/collections/{collection}', [\App\Http\Controllers\Admin\CollectionController::class, 'destroy'])->name('collections.destroy');
    
    // Orders
    Route::get('/orders', [OrdeRpageController::class, 'index'])->name('orders');
});
