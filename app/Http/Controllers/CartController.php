<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShippingZone;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    protected $promotionEngine;
    protected $shippingService;

    public function __construct(\App\Services\PromotionEngine $promotionEngine, ShippingService $shippingService)
    {
        $this->promotionEngine = $promotionEngine;
        $this->shippingService = $shippingService;
    }

    public function index()
    {
        $cart = Session::get('cart', []);
        $cartItems = collect();

        foreach ($cart as $id => $details) {
            $product = Product::find($details['id'] ?? $id);
            if ($product) {
                // Attach quantity from session
                $product->quantity = $details['quantity'];
                $product->type = $details['type'] ?? $product->type;
                $product->image = $details['image'] ?? $product->image;
                $cartItems->push($product);
            }
        }

        // Apply Promotion Engine
        $result = $this->promotionEngine->applyOffers($cartItems);
        
        $cartItems = $result['items'];

        // Calculate Subtotal (Pre-discount)
        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        // Apply Coupon if exists
        $couponCode = Session::get('coupon_code');
        $couponDiscount = 0;
        $coupon = null;
        $couponError = null;

        if ($couponCode) {
            $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid()) {
                // Basic coupon logic
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
                    $couponError = __('Minimum order value for this coupon is :amount', ['amount' => $coupon->min_order_value]);
                }
            } else {
                Session::forget('coupon_code'); // Invalid
                $couponError = __('Invalid or expired coupon.');
            }
        }

        $offerDiscount = $result['total_discount'];
        $totalDiscount = $offerDiscount + $couponDiscount;
        $total = $subtotal - $totalDiscount;
        if ($total < 0) $total = 0;

        $freeShippingThreshold = $result['free_shipping_threshold'] ?? 0;

        // Shipping Zones for Estimation
        $shippingZones = ShippingZone::where('is_active', true)->get();
        $selectedZoneId = Session::get('shipping_zone_id');
        $totalWeight = $cartItems->sum(function($item) {
            return ($item->weight ?? 0) * $item->quantity;
        });
        $shippingEstimation = $this->shippingService->calculate($total, $totalWeight, $selectedZoneId);
        
        // Add shipping to total for the final display
        $totalWithShipping = $total + $shippingEstimation['cost'];

        return view('cart.index', compact(
            'cart', 
            'cartItems', 
            'subtotal', 
            'offerDiscount', 
            'couponDiscount', 
            'total', // This is subtotal after discount
            'totalWithShipping',
            'couponCode',
            'couponError',
            'result', 
            'freeShippingThreshold',
            'shippingZones',
            'selectedZoneId',
            'shippingEstimation'
        ));
    }

    public function store(Request $request)
    {
        // Handle both 'add' form and AJAX
        // ID might come from route or request
        $id = $request->product_id ?? $request->id;
        $quantity = (int) $request->get('quantity', 1);

        $product = Product::findOrFail($id);
        $cart = Session::get('cart', []);

        // Check stock for initial add or if quantity exceeds stock
        if ($product->stock !== null && $quantity > $product->stock) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => __('Only :count units available in stock.', ['count' => $product->stock])
                ], 422);
            }
            return redirect()->back()->with('error', __('Only :count units available in stock.', ['count' => $product->stock]));
        }

        if (isset($cart[$id])) {
            // Re-check stock for total quantity if item already in cart
            $newTotalQty = $cart[$id]['quantity'] + $quantity;
            if ($product->stock !== null && $newTotalQty > $product->stock) {
                 if ($request->wantsJson()) {
                    return response()->json([
                        'message' => __('Only :count units available in stock.', ['count' => $product->stock])
                    ], 422);
                }
                return redirect()->back()->with('error', __('Only :count units available in stock.', ['count' => $product->stock]));
            }
            $cart[$id]['quantity'] = $newTotalQty;
        } else {
            $cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->discount_price ?? $product->price,
                'quantity' => $quantity,
                'image' => $product->image,
                'slug' => $product->slug,
                'type' => $product->type,
            ];
        }

        Session::put('cart', $cart);

        if ($request->wantsJson()) {
             $totalQuantity = array_sum(array_column($cart, 'quantity'));
             return response()->json(['message' => __('Product added to cart!'), 'cartCount' => $totalQuantity]);
        }

        return redirect()->back()->with('success', __('Product added to cart!'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            $product = Product::find($id);
            if ($product && $product->stock !== null && $request->quantity > $product->stock) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'message' => __('Only :count units available in stock.', ['count' => $product->stock])
                    ], 422);
                }
                return redirect()->back()->with('error', __('Only :count units available in stock.', ['count' => $product->stock]));
            }
            $cart[$id]['quantity'] = (int) $request->quantity;
            Session::put('cart', $cart);
        }

        if ($request->wantsJson()) {
            return $this->getCartDataResponse();
        }

        return redirect()->route('cart.index')->with('success', __('Cart updated!'));
    }

    protected function getCartDataResponse()
    {
        $cart = Session::get('cart', []);
        $cartItems = collect();

        foreach ($cart as $id => $details) {
            $product = Product::find($details['id'] ?? $id);
            if ($product) {
                $product->quantity = $details['quantity'];
                $product->type = $details['type'] ?? $product->type;
                $product->image = $details['image'] ?? $product->image;
                $cartItems->push($product);
            }
        }

        $result = $this->promotionEngine->applyOffers($cartItems);
        $cartItems = $result['items'];

        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $couponCode = Session::get('coupon_code');
        $couponDiscount = 0;
        $coupon = null;

        if ($couponCode) {
            $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid()) {
                 if ($coupon->type == 'percentage') {
                    $couponDiscount = $subtotal * ($coupon->value / 100);
                     if ($coupon->max_discount_value && $couponDiscount > $coupon->max_discount_value) {
                        $couponDiscount = $coupon->max_discount_value;
                    }
                } else {
                    $couponDiscount = $coupon->value;
                }
                
                if ($coupon->min_order_value && $subtotal < $coupon->min_order_value) {
                    $couponDiscount = 0;
                }
            } else {
                Session::forget('coupon_code');
            }
        }

        $offerDiscount = $result['total_discount'];
        $totalDiscount = $offerDiscount + $couponDiscount;
        $total = $subtotal - $totalDiscount;
        if ($total < 0) $total = 0;

        $totalWeight = $cartItems->sum(function($item) {
            return ($item->weight ?? 0) * $item->quantity;
        });

        $selectedZoneId = Session::get('shipping_zone_id');
        $shippingEstimation = $this->shippingService->calculate($total, $totalWeight, $selectedZoneId);
        $totalWithShipping = $total + $shippingEstimation['cost'];

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
            'offer_discount' => round($offerDiscount, 2),
            'coupon_discount' => round($couponDiscount, 2),
            'total_discount' => round($totalDiscount, 2),
            'total' => round($totalWithShipping, 2),
            'subtotal_after_discount' => round($total, 2),
            'free_shipping' => (bool)($result['free_shipping'] ?? false),
            'free_shipping_threshold' => (float)($result['free_shipping_threshold'] ?? 0),
            'shipping_label' => $shippingEstimation['label'] ?? '0.00',
            'shipping_cost' => (float)($shippingEstimation['cost'] ?? 0),
            'shipping_is_free' => (bool)($shippingEstimation['is_free'] ?? false),
        ]);
    }

    public function updateZone(Request $request)
    {
        $request->validate(['zone_id' => 'nullable|exists:shipping_zones,id']);
        Session::put('shipping_zone_id', $request->zone_id);
        Session::save();

        return $this->getCartDataResponse();
    }

    public function destroy(Request $request, $id)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }

        if ($request->wantsJson()) {
            return $this->getCartDataResponse();
        }

        return redirect()->route('cart.index')->with('success', __('Product removed from cart!'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        
        $coupon = \App\Models\Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return redirect()->back()->with('error', __('Invalid coupon code'));
        }

        // Use the consolidated isValid method
        if (!$coupon->isValid()) {
             return redirect()->back()->with('error', __('Invalid, expired, or inactive coupon.'));
        }

        Session::put('coupon_code', $coupon->code);
        Session::save();

        return redirect()->route('cart.index')->with('success', __('Coupon applied!'));
    }

    public function removeCoupon(Request $request)
    {
        $request->session()->forget('coupon_code');
        $request->session()->forget('coupon_discount');
        $request->session()->save();
        
        return redirect()->route('cart.index')->with('success', __('Coupon removed!'));
    }

}
