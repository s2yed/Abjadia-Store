<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->discount_price ?? $product->price,
                'quantity' => 1,
                'image' => $product->image,
                'slug' => $product->slug,
                'type' => $product->type,
            ];
        }

        Session::put('cart', $cart);

        // Return JSON for AJAX or redirect
        if ($request->wantsJson()) {
            $totalQuantity = array_sum(array_column($cart, 'quantity'));
            return response()->json(['message' => __('Product added to cart!'), 'cartCount' => $totalQuantity]);
        }

        return redirect()->back()->with('success', __('Product added to cart!'));
    }

    public function update(Request $request, $id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $request->quantity;
            Session::put('cart', $cart);

            if ($request->wantsJson()) {
                $itemTotal = $cart[$id]['price'] * $cart[$id]['quantity'];
                $total = 0;
                foreach ($cart as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
                $cartCount = array_sum(array_column($cart, 'quantity'));

                return response()->json([
                    'success' => true,
                    'cartCount' => $cartCount,
                    'itemTotal' => $itemTotal,
                    'total' => $total
                ]);
            }
        }

        return redirect()->route('cart.index')->with('success', __('Cart updated!'));
    }

    public function remove($id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', __('Product removed from cart!'));
    }
}
