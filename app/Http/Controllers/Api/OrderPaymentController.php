<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderPaymentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
            'note' => 'nullable|string',
            'paid_at' => 'nullable|date',
            'bank_account_id' => 'nullable|exists:bank_accounts,id',
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            $order = \App\Models\Order::findOrFail($validated['order_id']);

            // Create Payment
            $payment = $order->payments()->create($validated);

            // Update Order if approved
            if ($validated['status'] === 'approved') {
                $order->paid_amount += $validated['amount'];
                $order->remaining_amount = max(0, $order->total_price - $order->paid_amount);

                if ($order->remaining_amount <= 0) {
                    $order->payment_status = 'paid';
                } elseif ($order->paid_amount > 0) {
                    $order->payment_status = 'partial';
                }
                
                $order->save();
            }

            return response()->json([
                'payment' => $payment,
                'order' => $order->fresh()
            ], 201);
        });
    }

    public function destroy($id)
    {
        $orderPayment = \App\Models\OrderPayment::findOrFail($id);
        $order = $orderPayment->order;
        
        return \Illuminate\Support\Facades\DB::transaction(function () use ($orderPayment, $order) {
            // Revert order totals if approved
            if ($orderPayment->status === 'approved') {
                $order->paid_amount -= $orderPayment->amount;
                $order->paid_amount = max(0, $order->paid_amount); // Prevent negative
                $order->remaining_amount = max(0, $order->total_price - $order->paid_amount);
                
                if ($order->remaining_amount <= 0 && $order->total_price > 0) {
                     $order->payment_status = 'paid';
                } elseif ($order->paid_amount > 0) {
                     $order->payment_status = 'partial';
                } else {
                     $order->payment_status = 'pending';
                }
                $order->save();
            }

            $orderPayment->delete();
            return response()->json(null, 204);
        });
    }
}
