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
    public function orders(Request $request)
    {
        $orders = Auth::user()->orders()->withCount('items')->latest()->paginate(5);
        
        if ($request->wantsJson()) {
            return response()->json($orders);
        }
        
        return view('customer.orders', compact('orders'));
    }

    public function orderDetails(Request $request, $id)
    {
        $order = Auth::user()->orders()->with('items.product')->findOrFail($id);
        
        if ($request->wantsJson()) {
            return response()->json($order);
        }
        
        return view('customer.order-details', compact('order'));
    }

    /**
     * Display the user's favorite products.
     */
    public function favorites(Request $request)
    {
        $favorites = Auth::user()->favorites()->with('product.category')->paginate(12);
        
        if ($request->wantsJson()) {
            return response()->json($favorites);
        }
        
        return view('customer.favorites', compact('favorites'));
    }
}
