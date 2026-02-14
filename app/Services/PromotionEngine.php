<?php

namespace App\Services;

use App\Models\Offer;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Collection;

class PromotionEngine
{
    /**
     * Apply valid offers to the cart items.
     *
     * @param  Collection  $cartItems
     * @return array  ['items' => Collection, 'total_discount' => float]
     */
    public function applyOffers(Collection $cartItems)
    {
        $offers = Offer::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->orderBy('priority', 'desc')
            ->get();

        $totalDiscount = 0;
        // Clone items to avoid modifying original references directly if needed elsewhere
        // But here we want to modify them to show discounts.
        // We might add a 'discounted_price' and 'applied_offer' field to items.

        $freeShipping = false;

        foreach ($offers as $offer) {
            if ($this->checkCondition($offer, $cartItems)) {
                $result = $this->applyAction($offer, $cartItems);
                $totalDiscount += $result['total_discount'];
                if ($result['free_shipping']) {
                    $freeShipping = true;
                }
            }
        }

        return [
            'items' => $cartItems,
            'total_discount' => $totalDiscount,
            'free_shipping' => $freeShipping,
            'free_shipping_threshold' => $this->getFreeShippingThreshold()
        ];
    }

    public function getFreeShippingThreshold()
    {
        // Find an active offer that provides free shipping based on subtotal
        $offer = Offer::where('is_active', true)
            ->where('actions->type', 'free_shipping')
            ->where('conditions->type', 'min_subtotal')
            ->first();

        if ($offer) {
            return (float) ($offer->conditions['value'] ?? 0);
        }

        // Fallback to global settings threshold
        $settings = Setting::first();
        if ($settings && $settings->free_shipping_threshold !== null) {
            return (float) $settings->free_shipping_threshold;
        }

        // Default fallback if no setting is found
        return 200.0; 
    }

    protected function checkCondition(Offer $offer, Collection $cartItems)
    {
        $conditions = $offer->conditions;

        // Filter out free/reward items from condition checks to prevent double-dipping
        // We only want to evaluate promotions based on paid items.
        $eligibleItems = $cartItems->filter(function($item) {
            return !($item->is_reward ?? false) && !($item->is_free ?? false) && $item->price > 0;
        });
        
        // Example: Check Minimum Quantity of Specific Product
        // { "type": "min_qty", "product_id": 1, "value": 2 }
        if (isset($conditions['type']) && $conditions['type'] === 'min_qty') {
            $count = $eligibleItems->where('id', $conditions['product_id'])->sum('quantity');
            return $count >= $conditions['value'];
        }

        // Example: Minimum Subtotal
        // { "type": "min_subtotal", "value": 500 }
        if (isset($conditions['type']) && $conditions['type'] === 'min_subtotal') {
            $subtotal = $eligibleItems->sum(function($item) {
                return $item->price * $item->quantity;
            });
            return $subtotal >= $conditions['value'];
        }

        return false;
    }

    protected function applyAction(Offer $offer, Collection $cartItems)
    {
        $actions = $offer->actions;
        $discount = 0;
        $freeShipping = false;

        // Filter out reward items for discount calculations
        $eligibleItems = $cartItems->filter(function($item) {
            return !($item->is_reward ?? false) && !($item->is_free ?? false) && $item->price > 0;
        });

        // Example: Percentage Off
        // { "type": "percentage_off", "value": 10 }
        if (isset($actions['type']) && $actions['type'] === 'percentage_off') {
            foreach ($eligibleItems as $item) {
                $itemDiscount = ($item->price * $item->quantity) * ($actions['value'] / 100);
                $discount += $itemDiscount;
            }
        }

        // Example: Fixed Amount Off
        // { "type": "fixed_amount", "value": 50 }
        if (isset($actions['type']) && $actions['type'] === 'fixed_amount') {
             $discount += $actions['value'];
        }

        // Example: Free Product (Buy X Get Y)
        // { "type": "free_product", "free_product_id": 1, "value": 1 }
        if (isset($actions['type']) && $actions['type'] === 'free_product') {
            $freeProductId = $actions['free_product_id'] ?? null;
            $freeQty = $actions['value'] ?? 1;

            // ALWAYS add as a new virtual item to the collection so it doesn't merge with paid items
            $freeProduct = Product::find($freeProductId);
            if ($freeProduct) {
                // Clone to avoid reference issues
                $freeProductItem = clone $freeProduct;
                $freeProductItem->quantity = $freeQty;
                $freeProductItem->is_reward = true; // Mark as reward
                $freeProductItem->is_free = true;   // For backwards compatibility in views
                $freeProductItem->price = 0;        // Set to 0 so subtotal is clean
                $freeProductItem->from_offer = $offer->id;
                
                // Do NOT add to discount to avoid "double discount" confusion in the UI
                //$discount += $freeProduct->price * $freeQty;

                // Push as a separate line item
                $cartItems->push($freeProductItem);
            }
        }

        // Example: Free Shipping
        if (isset($actions['type']) && $actions['type'] === 'free_shipping') {
            $freeShipping = true;
        }

        return [
            'total_discount' => $discount,
            'free_shipping' => $freeShipping
        ];
    }
}
