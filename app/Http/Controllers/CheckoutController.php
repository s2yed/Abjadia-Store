<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('Your cart is empty.'));
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout.index', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string',
            'phone' => 'required|string',
            'payment_method' => 'required|in:COD,Card',
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('Your cart is empty.'));
        }

        $total = 0;
        foreach ($cart as $item) {
            $product = \App\Models\Product::find($item['id']);
            if (!$product || $product->stock < $item['quantity']) {
                return redirect()->route('cart.index')->with('error', __('One or more items are out of stock.'));
            }
            $total += $item['price'] * $item['quantity'];
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id() ?? null,
                'status' => 'pending',
                'total_price' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'shipping_address' => $request->address . ' | ' . $request->phone . ' | ' . $request->name,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();
            Session::forget('cart');

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
