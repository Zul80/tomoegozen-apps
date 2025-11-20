<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $metrics = [
            'total_sales' => Order::sum('grand_total'),
            'total_orders' => Order::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_products' => Product::count(),
        ];

        $latestOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('metrics', 'latestOrders'));
    }
}

