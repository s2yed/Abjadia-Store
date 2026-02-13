<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class MoyasarController extends Controller
{
    public function pay(Order $order)
    {
        if ($order->payment_status === 'paid') {
            return redirect()->route('customer.orders.show', $order->id);
        }

        return view('checkout.payment', compact('order'));
    }

    public function callback(Request $request)
    {
        $paymentId = $request->id;
        $orderId = $request->order_id; 

        if (!$paymentId) {
             return redirect()->route('home')->with('error', __('Invalid payment callback.'));
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withBasicAuth(config('services.moyasar.secret'), '')
                ->get("https://api.moyasar.com/v1/payments/{$paymentId}");

            if ($response->failed()) {
                \Illuminate\Support\Facades\Log::error('Moyasar API Error', ['body' => $response->body()]);
                return redirect()->route('home')->with('error', __('Payment verification failed.'));
            }

            $payment = $response->json();
            
            $metadataOrderId = $payment['metadata']['order_id'] ?? $orderId;
            
            if (!$metadataOrderId) {
                 \Illuminate\Support\Facades\Log::error('Moyasar Payment missing Order ID', ['payment' => $payment]);
                 return redirect()->route('home')->with('error', __('Could not find associated order for this payment.'));
            }

            $order = Order::findOrFail($metadataOrderId);

            if ($payment['status'] === 'paid') {
                $amount = $payment['amount'] / 100; // Moyasar uses hallas
                
                \Illuminate\Support\Facades\DB::transaction(function () use ($order, $payment, $amount) {
                    // Update Order
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing', 
                        'transaction_id' => $payment['id'],
                        'paid_amount' => $order->paid_amount + $amount,
                        'remaining_amount' => max(0, $order->total_price - ($order->paid_amount + $amount))
                    ]);

                    // Find online default bank account
                    $bankAccount = \App\Models\BankAccount::where('is_online_default', true)->first();

                    // Create Payment Record
                    $order->payments()->create([
                        'amount' => $amount,
                        'payment_method' => $payment['source']['type'] ?? 'card',
                        'transaction_id' => $payment['id'],
                        'status' => 'approved',
                        'paid_at' => now(),
                        'bank_account_id' => $bankAccount?->id
                    ]);
                });

                try {
                    $recipient = $order->user?->email ?? $order->email ?? null;
                    if ($recipient) {
                        \Illuminate\Support\Facades\Mail::to($recipient)->send(new \App\Mail\OrderPlacedEmail($order));
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Failed to send order placed email (Moyasar): " . $e->getMessage());
                }

                return redirect()->route('customer.orders.show', $order->id)->with('success', __('Payment successful!'));
            } else {
                 $message = $payment['source']['message'] ?? __('Unknown error');
                 return redirect()->route('customer.orders.show', $order->id)->with('error', __('Payment failed: ') . $message);
            }

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Moyasar Callback Error: ' . $e->getMessage());
            if ($request->has('order_id')) {
                 return redirect()->route('customer.orders.show', $request->order_id)->with('error', __('Payment verification failed. Please contact support.'));
            }
            return redirect()->route('home')->with('error', __('Payment verification failed.'));
        }
    }

    public function webhook(Request $request)
    {
        $data = $request->all();

        \Illuminate\Support\Facades\Log::info('Moyasar Webhook Received', $data);

        $paymentId = $data['id'] ?? null;
        $status = $data['status'] ?? null;

        if (!$paymentId || !$status) {
            return response()->json(['message' => 'Invalid data'], 400);
        }

        // Verify authenticity by fetching from API again (safest)
        // Or if we trust the webhook signature (not standard in basic Moyasar docs, better to fetch)
        
        try {
             $response = \Illuminate\Support\Facades\Http::withBasicAuth(config('services.moyasar.secret'), '')
                ->get("https://api.moyasar.com/v1/payments/{$paymentId}");

            if ($response->failed()) {
                 \Illuminate\Support\Facades\Log::error('Moyasar Webhook: Could not verify payment', ['id' => $paymentId]);
                 return response()->json(['message' => 'Verification failed'], 400);
            }

            $payment = $response->json();
            
            if ($payment['status'] === 'paid') {
                $orderId = $payment['metadata']['order_id'] ?? null;
                
                if ($orderId) {
                    $order = Order::find($orderId);
                    if ($order && $order->payment_status !== 'paid') {
                        $amount = $payment['amount'] / 100;
                        
                        \Illuminate\Support\Facades\DB::transaction(function () use ($order, $payment, $amount) {
                            $order->update([
                                'payment_status' => 'paid',
                                'status' => 'processing',
                                'transaction_id' => $payment['id'],
                                'paid_amount' => $order->paid_amount + $amount,
                                'remaining_amount' => max(0, $order->total_price - ($order->paid_amount + $amount))
                            ]);

                            // Find online default bank account
                            $bankAccount = \App\Models\BankAccount::where('is_online_default', true)->first();

                            // Create Payment Record
                            $order->payments()->create([
                                'amount' => $amount,
                                'payment_method' => $payment['source']['type'] ?? 'card',
                                'transaction_id' => $payment['id'],
                                'status' => 'approved',
                                'paid_at' => now(),
                                'bank_account_id' => $bankAccount?->id
                            ]);
                        });

                        try {
                            $recipient = $order->user?->email ?? $order->email ?? null;
                            if ($recipient) {
                                \Illuminate\Support\Facades\Mail::to($recipient)->send(new \App\Mail\OrderPlacedEmail($order));
                            }
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error("Failed to send order placed email (Moyasar Webhook): " . $e->getMessage());
                        }

                        \Illuminate\Support\Facades\Log::info("Order {$order->id} paid via webhook and email status update attempted");
                    }
                }
            }

            return response()->json(['message' => 'Processed']);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Moyasar Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Server Error'], 500);
        }
    }
}
