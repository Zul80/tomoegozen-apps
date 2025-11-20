<?php

use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => ['status' => 'ok']);

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product:slug}', [ProductController::class, 'show']);

Route::get('/cart', [CartController::class, 'show']);
Route::post('/cart', [CartController::class, 'store']);
Route::delete('/cart/items/{cartItem}', [CartController::class, 'destroy']);

Route::post('/checkout', [CheckoutController::class, 'store']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', fn () => [
            'total_sales' => \App\Models\Order::sum('grand_total'),
            'order_count' => \App\Models\Order::count(),
            'customer_count' => \App\Models\User::where('role', 'customer')->count(),
            'product_count' => \App\Models\Product::count(),
        ]);

        Route::get('/orders', [AdminOrderController::class, 'index']);

        Route::get('/products', [AdminProductController::class, 'index']);
        Route::post('/products', [AdminProductController::class, 'store']);
        Route::post('/products/import', [AdminProductController::class, 'import']);
        Route::get('/products/export', [AdminProductController::class, 'export']);
        Route::put('/products/{product}', [AdminProductController::class, 'update']);
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy']);
    });
});

