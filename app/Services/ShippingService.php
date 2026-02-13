<?php

namespace App\Services;

use App\Models\ShippingZone;
use App\Models\ShippingRate;
use App\Models\Setting;

class ShippingService
{
    /**
     * Calculate shipping cost for a given subtotal and optional zone.
     *
     * @param float $subtotal
     * @param float $totalWeight
     * @param int|null $zoneId
     * @return array ['cost' => float, 'is_free' => bool, 'label' => string, 'zone_name' => string|null]
     */
    public function calculate($subtotal, $totalWeight = 0, $zoneId = null)
    {
        $cost = 0;
        $isFree = false;
        $zoneName = null;

        if ($zoneId) {
            $zone = ShippingZone::find($zoneId);
            if ($zone) {
                $zoneName = $zone->name;
                
                // Find matching rate for this zone
                $rate = ShippingRate::where('shipping_zone_id', $zoneId)
                    ->where(function ($query) use ($subtotal, $totalWeight) {
                        $query->where(function ($q) use ($subtotal) {
                            $q->where('calculation_type', 'price_based')
                              ->where('min_value', '<=', $subtotal)
                              ->where(function ($inner) use ($subtotal) {
                                  $inner->whereNull('max_value')->orWhere('max_value', '>=', $subtotal);
                              });
                        })->orWhere(function ($q) use ($totalWeight) {
                            $q->where('calculation_type', 'weight_based')
                              ->where('min_value', '<=', $totalWeight)
                              ->where(function ($inner) use ($totalWeight) {
                                  $inner->whereNull('max_value')->orWhere('max_value', '>=', $totalWeight);
                              });
                        })->orWhere('calculation_type', 'flat_rate');
                    })
                    ->orderBy('cost', 'asc')
                    ->first();

                if ($rate) {
                    $cost = (float) $rate->cost;
                    
                    // Check rate-specific free shipping threshold
                    if ($rate->free_shipping_threshold && $subtotal >= $rate->free_shipping_threshold) {
                        $isFree = true;
                        $cost = 0;
                    }
                }
            }
        } else {
            // No zone selected - return minimum available cost from all zones as an "Estimation"
            $minRate = ShippingRate::orderBy('cost', 'asc')->first();
            if ($minRate) {
                $cost = (float) $minRate->cost;
            }
        }

        // Global threshold check (if not already free by rate)
        if (!$isFree) {
            $settings = Setting::first();
            if ($settings && $settings->free_shipping_threshold && $subtotal >= $settings->free_shipping_threshold) {
                $isFree = true;
                $cost = 0;
            }
        }

        $currency = Setting::first()->currency ?? 'SAR';
        $label = $isFree ? __('Free') : $cost . ' ' . $currency;

        return [
            'cost' => $cost,
            'is_free' => $isFree,
            'label' => $label,
            'zone_name' => $zoneName
        ];
    }
}
