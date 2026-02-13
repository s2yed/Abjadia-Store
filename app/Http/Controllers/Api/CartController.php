<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Coupon;
use App\Services\PromotionEngine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    protected $promotionEngine;

    public function __construct(PromotionEngine $promotionEngine)
    {
        $this->promotionEngine = $promotionEngine;
    }

    // Get current cart with calculations
    public function index(Request $request)
    {
        $cart = Session::get('cart', []);
        $cartItems = collect();

        foreach ($cart as $productId => $details) {
            $product = Product::find($productId);
            if ($product) {
                // Attach quantity from session
                $product->quantity = $details['quantity'];
                $cartItems->push($product);
            }
        }

        // Apply Promotion Engine
        $result = $this->promotionEngine->applyOffers($cartItems);

        // Calculate Totals
        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        // Apply Coupon if exists
        $couponCode = Session::get('coupon_code');
        $couponDiscount = 0;
        $coupon = null;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid()) {
                // Basic coupon logic (can be moved to service later for more complex checks)
                if ($coupon->type == 'percentage') {
                    $couponDiscount = $subtotal * ($coupon->value / 100);
                    if ($coupon->max_discount_value && $couponDiscount > $coupon->max_discount_value) {
                        $couponDiscount = $coupon->max_discount_value;
                    }
                } else {
                    $couponDiscount = $coupon->value;
                }
                
                // Check Min Order Value
                if ($coupon->min_order_value && $subtotal < $coupon->min_order_value) {
                    $couponDiscount = 0;
                    // Ideally return warning
                }
            } else {
                Session::forget('coupon_code'); // Invalid
            }
        }

        $totalDiscount = $result['total_discount'] + $couponDiscount;
        $total = $subtotal - $totalDiscount;
        if ($total < 0) $total = 0;

        return response()->json([
            'items' => $cartItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => (float)$item->price,
                    'quantity' => (int)$item->quantity,
                    'is_free' => (bool)($item->is_free ?? false),
                    'total' => (float)($item->price * $item->quantity)
                ];
            }),
            'subtotal' => round($subtotal, 2),
            'offer_discount' => round($result['total_discount'], 2),
            'coupon_discount' => round($couponDiscount, 2),
            'total_discount' => round($totalDiscount, 2),
            'total' => round($total, 2),
            'free_shipping' => (bool)($result['free_shipping'] ?? false),
            'coupon_code' => $couponCode
        ]);
    }

    // Add item to cart
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Session::get('cart', []);
        $id = $validated['product_id'];
        $qty = $validated['quantity'];

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $qty;
        } else {
            $cart[$id] = [
                'quantity' => $qty
            ];
        }

        Session::put('cart', $cart);

        return $this->index($request);
    }

    // Update item quantity
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $cart = Session::get('cart', []);

        if ($validated['quantity'] == 0) {
            unset($cart[$id]);
        } elseif (isset($cart[$id])) {
            $cart[$id]['quantity'] = $validated['quantity'];
        }

        Session::put('cart', $cart);

        return $this->index($request);
    }

    // Remove item
    public function destroy($id)
    {
        $cart = Session::get('cart', []);
        unset($cart[$id]);
        Session::put('cart', $cart);

        return $this->index(request());
    }

    // Apply Coupon
    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        
        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json(['message' => 'Invalid coupon code'], 422);
        }

        // Basic validity check
        if (!$coupon->is_active) {
             return response()->json(['message' => 'Coupon is inactive'], 422);
        }
        
        // Date check
        if ($coupon->expiry_date && now()->gt($coupon->expiry_date)) {
             return response()->json(['message' => 'Coupon has expired'], 422);
        }

        Session::put('coupon_code', $coupon->code);

        return $this->index($request);
    }

    // Remove Coupon
    public function removeCoupon()
    {
        Session::forget('coupon_code');
        Session::forget('coupon_discount');
        Session::save();
        return $this->index(request());
    }
}
