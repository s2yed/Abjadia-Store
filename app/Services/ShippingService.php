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
        $settings = Setting::first();

        if ($zoneId) {
            $zone = ShippingZone::find($zoneId);
            if ($zone) {
                $zoneName = $zone->name;
                
                // Find matching rate for this zone (precise range match)
                $rate = ShippingRate::where('shipping_zone_id', $zoneId)
                    ->where(function ($query) use ($subtotal, $totalWeight) {
                        $query->where(function ($q) use ($subtotal) {
                            $q->where('calculation_type', 'price')
                              ->where('min_value', '<=', $subtotal)
                              ->where(function ($inner) use ($subtotal) {
                                  $inner->whereNull('max_value')->orWhere('max_value', '>=', $subtotal);
                              });
                        })->orWhere(function ($q) use ($totalWeight) {
                            $q->where('calculation_type', 'weight')
                              ->where('min_value', '<=', $totalWeight)
                              ->where(function ($inner) use ($totalWeight) {
                                  $inner->whereNull('max_value')->orWhere('max_value', '>=', $totalWeight);
                              });
                        })->orWhere(function ($q) use ($subtotal) {
                            $q->where('calculation_type', 'flat')
                              ->where('min_value', '<=', $subtotal)
                              ->where(function ($inner) use ($subtotal) {
                                  $inner->whereNull('max_value')->orWhere('max_value', '>=', $subtotal);
                              });
                        });
                    })
                    ->orderBy('cost', 'asc')
                    ->first();

                // Fallback: If no exact range match, use the highest tier reached (best fit)
                if (!$rate) {
                    $rate = ShippingRate::where('shipping_zone_id', $zoneId)
                        ->where(function($q) use ($subtotal, $totalWeight) {
                             $q->where(function($qq) use ($subtotal) {
                                 $qq->whereIn('calculation_type', ['price', 'flat'])
                                    ->where('min_value', '<=', $subtotal);
                             })->orWhere(function($qq) use ($totalWeight) {
                                 $qq->where('calculation_type', 'weight')
                                    ->where('min_value', '<=', $totalWeight);
                             });
                        })
                        ->orderBy('min_value', 'desc')
                        ->first();
                }

                if ($rate) {
                    $cost = (float) $rate->cost;
                    
                    // Check rate-specific free shipping threshold
                    if ($rate->free_shipping_threshold && $subtotal >= $rate->free_shipping_threshold) {
                        $isFree = true;
                        $cost = 0;
                    }
                } else {
                    // Final fallback: use the cheapest rate in the zone if nothing else matches
                    $fallbackRate = ShippingRate::where('shipping_zone_id', $zoneId)->orderBy('cost', 'asc')->first();
                    if ($fallbackRate) {
                        $cost = (float)$fallbackRate->cost;
                    } else {
                        // Global fallback from settings
                        $cost = (float)($settings->default_shipping_cost ?? 0);
                    }
                }
            }
        } else {
            // No zone selected - return minimum available cost from all zones or global default
            $minRate = ShippingRate::orderBy('cost', 'asc')->first();
            if ($minRate) {
                $cost = (float) $minRate->cost;
            } else {
                $cost = (float)($settings->default_shipping_cost ?? 0);
            }
        }

        // Determine what threshold to return to the frontend for the progress bar
        $activeThreshold = 0;
        $globalThreshold = $settings->free_shipping_threshold ?? 0;
        
        if ($zoneId && isset($rate) && $rate->free_shipping_threshold) {
            $activeThreshold = (float) $rate->free_shipping_threshold;
        } else {
            $activeThreshold = (float) $globalThreshold;
        }

        // Check global threshold (if not already free by rate)
        if (!$isFree && $globalThreshold && $subtotal >= $globalThreshold) {
            $isFree = true;
            $cost = 0;
        }

        $currency = $settings->currency ?? 'SAR';
        $label = $isFree ? __('Free') : $cost . ' ' . $currency;

        return [
            'cost' => $cost,
            'is_free' => $isFree,
            'label' => $label,
            'zone_name' => $zoneName,
            'threshold' => $activeThreshold
        ];
    }
}
