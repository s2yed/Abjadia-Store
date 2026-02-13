<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class ShippingRateController extends Controller
{
    public function index(Request $request)
    {
        $query = ShippingRate::with(['company', 'zone']);
        
        if ($request->has('company_id')) {
            $query->where('shipping_company_id', $request->company_id);
        }
        
        if ($request->has('zone_id')) {
            $query->where('shipping_zone_id', $request->zone_id);
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_company_id' => 'required|exists:shipping_companies,id',
            'shipping_zone_id' => 'required|exists:shipping_zones,id',
            'calculation_type' => 'required|in:weight,price,flat',
            'min_value' => 'required|numeric',
            'max_value' => 'nullable|numeric',
            'cost' => 'required|numeric',
            'free_shipping_threshold' => 'nullable|numeric',
        ]);

        $rate = ShippingRate::create($validated);
        return response()->json($rate, 201);
    }

    public function show(ShippingRate $shippingRate)
    {
        return $shippingRate->load(['company', 'zone']);
    }

    public function update(Request $request, ShippingRate $shippingRate)
    {
        $validated = $request->validate([
            'shipping_company_id' => 'sometimes|exists:shipping_companies,id',
            'shipping_zone_id' => 'sometimes|exists:shipping_zones,id',
            'calculation_type' => 'sometimes|in:weight,price,flat',
            'min_value' => 'sometimes|numeric',
            'max_value' => 'nullable|numeric',
            'cost' => 'sometimes|numeric',
            'free_shipping_threshold' => 'nullable|numeric',
        ]);

        $shippingRate->update($validated);
        return response()->json($shippingRate);
    }

    public function destroy(ShippingRate $shippingRate)
    {
        $shippingRate->delete();
        return response()->json(null, 204);
    }
}
