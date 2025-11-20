<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrdeRpageController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(15);

        return view('admin.orders', compact('orders'));
    }
}

