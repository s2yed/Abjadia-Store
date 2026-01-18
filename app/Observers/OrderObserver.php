<?php

namespace App\Observers;

use App\Models\Order;
use App\Mail\OrderStatusChangedEmail;
use Illuminate\Support\Facades\Mail;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        if ($order->status === 'processing') {
            $this->reduceStock($order);
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->isDirty('status')) {
             // 1. Stock Reduction (Priority)
             if ($order->status === 'processing' && $order->getOriginal('status') !== 'processing') {
                 $this->reduceStock($order);
             }

             // 2. Send email to user (Try-catch to prevent blocking business logic)
             try {
                 $recipient = $order->user?->email ?? $order->email ?? null;
                 if ($recipient) {
                    Mail::to($recipient)->send(new OrderStatusChangedEmail($order));
                 }
             } catch (\Exception $e) {
                 \Illuminate\Support\Facades\Log::error("Failed to send status update email for order {$order->id}: " . $e->getMessage());
             }
        }
    }

    /**
     * Reduce stock for all items in the order.
     */
    protected function reduceStock(Order $order): void
    {
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->decrement('stock', $item->quantity);
            }
        }
    }
}
