<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Favorite;

class CustomerController extends Controller
{
    /**
     * Display the user's profile overview.
     */
    public function index()
    {
        return view('customer.profile', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Display the user's orders.
     */
    public function orders()
    {
        $orders = Auth::user()->orders()->latest()->paginate(5);
        return view('customer.orders', compact('orders'));
    }

    public function orderDetails($id)
    {
        $order = Auth::user()->orders()->with('items.product')->findOrFail($id);
        return view('customer.order-details', compact('order'));
    }

    /**
     * Display the user's favorite products.
     */
    public function favorites()
    {
        $favorites = Auth::user()->favorites()->with('product.category')->paginate(12);
        return view('customer.favorites', compact('favorites'));
    }
}
