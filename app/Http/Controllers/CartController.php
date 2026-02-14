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

    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);
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
        $couponCode = $request->session()->get('coupon_code');
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
                $request->session()->forget('coupon_code'); // Invalid
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
        $selectedZoneId = $request->session()->get('shipping_zone_id');
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
        $cart = $request->session()->get('cart', []);

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

        $request->session()->put('cart', $cart);

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

        $cart = $request->session()->get('cart', []);

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
            $request->session()->put('cart', $cart);
        }

        if ($request->wantsJson()) {
            return $this->getCartDataResponse($request);
        }

        return redirect()->route('cart.index')->with('success', __('Cart updated!'));
    }

    public function getCartDataResponse(Request $request)
    {
        $cart = $request->session()->get('cart', []);
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

        $couponCode = $request->session()->get('coupon_code');
        
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
                $request->session()->forget('coupon_code');
            }
        }

        $offerDiscount = $result['total_discount'];
        $totalDiscount = $offerDiscount + $couponDiscount;
        $total = $subtotal - $totalDiscount;
        if ($total < 0) $total = 0;

        $totalWeight = $cartItems->sum(function($item) {
            return ($item->weight ?? 0) * $item->quantity;
        });

        $selectedZoneId = $request->session()->get('shipping_zone_id');
        $shippingEstimation = $this->shippingService->calculate($total, $totalWeight, $selectedZoneId);
        $totalWithShipping = $total + $shippingEstimation['cost'];

        // Fetch Recommended Products
        $recommended = Product::where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get()
            ->map(function ($product) {
                 return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => (float)$product->price,
                    'image' => $product->image,
                    'slug' => $product->slug,
                    'type' => $product->type, // Optional: helpful for display
                ];
            });

        return response()->json([
            'items' => $cartItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => (float)$item->price,
                    'quantity' => (int)$item->quantity,
                    'image' => $item->image,
                    'type' => $item->type,
                    'is_free' => (bool)($item->is_free ?? false),
                    'original_price' => (float)($item->original_price ?? $item->price),
                    'total' => (float)($item->price * $item->quantity)
                ];
            }),
            'subtotal' => round($subtotal, 2),
            'offer_discount' => round($offerDiscount, 2),
            'coupon_discount' => round($couponDiscount, 2),
            'total_discount' => round($totalDiscount, 2),
            'total' => round($totalWithShipping, 2),
            'subtotal_after_discount' => round($total, 2),
            'currency' => \App\Models\Setting::first()->currency ?? 'SAR',
            'free_shipping' => (bool)($result['free_shipping'] ?? false || $shippingEstimation['is_free']),
            'free_shipping_threshold' => (float)($shippingEstimation['threshold'] ?? 0),
            'shipping_label' => $shippingEstimation['label'] ?? '0.00',
            'shipping_cost' => (float)($shippingEstimation['cost'] ?? 0),
            'shipping_is_free' => (bool)($shippingEstimation['is_free'] ?? false),
            'coupon_code' => $couponCode ?? null,
            'selected_zone_id' => $selectedZoneId,
            'recommended' => $recommended,
        ]);
    }

    public function updateZone(Request $request)
    {
        $request->validate(['zone_id' => 'nullable|exists:shipping_zones,id']);
        $request->session()->put('shipping_zone_id', $request->zone_id);
        $request->session()->save();

        return $this->getCartDataResponse($request);
    }

    public function destroy(Request $request, $id)
    {
        $cart = $request->session()->get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            $request->session()->put('cart', $cart);
        }

        if ($request->wantsJson()) {
            return $this->getCartDataResponse($request);
        }

        return redirect()->route('cart.index')->with('success', __('Product removed from cart!'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);
        
        $coupon = \App\Models\Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            if ($request->wantsJson()) {
                return response()->json(['message' => __('Invalid coupon code')], 422);
            }
            return redirect()->back()->with('error', __('Invalid coupon code'));
        }

        // Use the consolidated isValid method
        if (!$coupon->isValid()) {
             if ($request->wantsJson()) {
                return response()->json(['message' => __('Invalid, expired, or inactive coupon.')], 422);
             }
             return redirect()->back()->with('error', __('Invalid, expired, or inactive coupon.'));
        }

        $request->session()->put('coupon_code', $coupon->code);
        $request->session()->save();

        if ($request->wantsJson()) {
            return $this->getCartDataResponse($request);
        }

        return redirect()->route('cart.index')->with('success', __('Coupon applied!'));
    }

    public function removeCoupon(Request $request)
    {
          
        // Use pull() to retrieve and remove in one operation
        $request->session()->pull('coupon_code');
        $request->session()->pull('coupon_discount');
        
        if ($request->wantsJson()) {
            return $this->getCartDataResponse($request);
        }

        return redirect()->route('cart.index')->with('success', __('Coupon removed!'));
    }

}
