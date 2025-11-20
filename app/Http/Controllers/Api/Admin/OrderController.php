<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['items', 'user'])->latest()->paginate(
            (int) $request->input('per_page', 15)
        );

        return OrderResource::collection($orders);
    }
}

