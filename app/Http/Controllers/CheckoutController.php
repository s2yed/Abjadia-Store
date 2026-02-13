<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingZone;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    protected $shippingService;

    public function __construct(ShippingService $shippingService)
    {
        $this->shippingService = $shippingService;
    }

    public function index()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('Your cart is empty.'));
        }

        $cartItems = collect();
        foreach ($cart as $id => $details) {
            $product = \App\Models\Product::find($details['id'] ?? $id);
            if ($product) {
                $product->quantity = $details['quantity'];
                $cartItems->push($product);
            }
        }

        $promotionEngine = app(\App\Services\PromotionEngine::class);
        $result = $promotionEngine->applyOffers($cartItems);
        $cartItems = $result['items'];

        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $couponCode = Session::get('coupon_code');
        $couponDiscount = 0;
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
            }
        }

        $total = $subtotal - ($result['total_discount'] + $couponDiscount);
        if ($total < 0) $total = 0;

        $selectedZoneId = Session::get('shipping_zone_id');
        $totalWeight = $cartItems->sum(function($item) {
            return ($item->weight ?? 0) * $item->quantity;
        });
        $shippingEstimation = $this->shippingService->calculate($total, $totalWeight, $selectedZoneId);
        $totalWithShipping = $total + $shippingEstimation['cost'];

        return view('checkout.index', compact(
            'cart', 
            'total', // This is subtotal after discount
            'totalWithShipping',
            'subtotal', 
            'couponDiscount', 
            'result',
            'shippingEstimation'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string',
            'phone' => 'required|string',
            'payment_method' => 'required|in:COD,Card,Wallet',
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('Your cart is empty.'));
        }

        $cartItems = collect();
        foreach ($cart as $id => $details) {
            $product = \App\Models\Product::find($details['id'] ?? $id);
            if (!$product || $product->stock < $details['quantity']) {
                return redirect()->route('cart.index')->with('error', __('One or more items are out of stock.'));
            }
            $product->quantity = $details['quantity'];
            $cartItems->push($product);
        }

        $promotionEngine = app(\App\Services\PromotionEngine::class);
        $result = $promotionEngine->applyOffers($cartItems);
        $cartItems = $result['items'];

        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        $couponCode = Session::get('coupon_code');
        $couponDiscount = 0;
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
            }
        }

        $total = $subtotal - ($result['total_discount'] + $couponDiscount);
        if ($total < 0) $total = 0;

        $selectedZoneId = Session::get('shipping_zone_id');
        $totalWeight = $cartItems->sum(function($item) {
            return ($item->weight ?? 0) * $item->quantity;
        });
        $shippingEstimation = $this->shippingService->calculate($total, $totalWeight, $selectedZoneId);
        $totalWithShipping = $total + $shippingEstimation['cost'];

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id() ?? null,
                'status' => 'pending',
                'subtotal_after_discount' => $total,
                'shipping_cost' => $shippingEstimation['cost'],
                'shipping_zone_id' => $selectedZoneId,
                'total_price' => $totalWithShipping,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'shipping_address' => $request->address . ' | ' . $request->phone . ' | ' . $request->name,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'product_name' => $item->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity,
                ]);
            }

            DB::commit();
            Session::forget('cart');
            Session::forget('coupon_code');
            Session::save();

            if ($request->payment_method === 'COD') {
                $order->update(['status' => 'processing']);
                try {
                    $recipient =  $request->email ?? $order->user?->email;
                    if ($recipient) {
                        \Illuminate\Support\Facades\Mail::to($recipient)->send(new \App\Mail\OrderPlacedEmail($order));
                    }
                } catch (\Exception $e) {   
                    \Illuminate\Support\Facades\Log::error("Failed to send COD order placed email: " . $e->getMessage());
                }
            }

            if ($request->payment_method === 'Card') {
                return redirect()->route('moyasar.pay', $order->id);
            }

            return redirect()->route('customer.orders.show', $order->id)->with('success', __('Order placed successfully!'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Something went wrong. Please try again.') . ' ' . $e->getMessage());
        }
    }
}
