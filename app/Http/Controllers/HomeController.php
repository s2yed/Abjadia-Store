<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Featured Books (Latest)
        $featuredBooks = Product::where('type', 'book')
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        // 2. Best Selling (Random for demo, would be order_items count)
        $bestSelling = Product::where('is_active', true)
            ->inRandomOrder()
            ->take(8)
            ->get();

        // 3. Most Viewed (Random for demo, would be views count)
        $mostViewed = Product::where('is_active', true)
            ->inRandomOrder()
            ->take(8)
            ->get();

        // 4. Recommended (Random for demo)
        $recommended = Product::where('type', 'book')
            ->where('is_active', true)
            ->where('price', '>', 50)
            ->inRandomOrder()
            ->take(8)
            ->get();

        // 5. Suggestions (Random mix)
        $suggestions = Product::where('is_active', true)
            ->inRandomOrder()
            ->take(8)
            ->get();

        // Specific supplies list if needed, or we can use one of the above
        $supplies = Product::where('type', 'supply')
            ->where('is_active', true)
            ->take(4)
            ->get();

        $categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->take(6)
            ->get();

        $banners = \App\Models\Banner::where('is_active', true)
            ->orderBy('order')
            ->get()
            ->groupBy('position');

        return view('home', compact(
            'featuredBooks',
            'bestSelling',
            'mostViewed',
            'recommended',
            'suggestions',
            'supplies',
            'categories',
            'banners'
        ));
    }
}
