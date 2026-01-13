<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function stats()
    {
        $totalUsers = \App\Models\User::count();
        $totalCustomers = \App\Models\User::where('role', 'customer')->count();
        $totalOrders = \App\Models\Order::count();
        $totalRevenue = \App\Models\Order::sum('total_price'); // Assuming total_price is the column
        $totalProducts = \App\Models\Product::count();
        $lowStockProducts = \App\Models\Product::where('stock', '<', 5)->count();

        // Recent Orders
        $recentOrders = \App\Models\Order::with('user')->latest()->take(5)->get();

        return response()->json([
            'total_users' => $totalUsers,
            'total_customers' => $totalCustomers,
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'total_products' => $totalProducts,
            'low_stock_products' => $lowStockProducts,
            'recent_orders' => $recentOrders,
        ]);
    }
}
