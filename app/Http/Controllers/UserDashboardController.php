<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function orders()
    {
        $orders = Auth::user()->orders()
            ->with(['orderItems.product', 'address'])
            ->latest()
            ->get();
        return view('frontend.orders', compact('orders'));
    }

    public function orderDetail($id)
    {
        $order = Auth::user()->orders()
            ->with(['orderItems.product', 'address'])
            ->findOrFail($id);
        return view('frontend.order-detail', compact('order'));
    }
}