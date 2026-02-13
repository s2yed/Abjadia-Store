<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        return response()->json(Coupon::with(['products', 'categories', 'shippingZones'])->latest()->paginate(20));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:1',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount_value' => 'nullable|numeric|min:0',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'shipping_zone_ids' => 'nullable|array',
            'shipping_zone_ids.*' => 'exists:shipping_zones,id',
            'is_active' => 'boolean'
        ]);

        $coupon = Coupon::create($validated);

        if (isset($validated['product_ids'])) {
            $coupon->products()->sync($validated['product_ids']);
        }
        
        if (isset($validated['category_ids'])) {
            $coupon->categories()->sync($validated['category_ids']);
        }

        if (isset($validated['shipping_zone_ids'])) {
            $coupon->shippingZones()->sync($validated['shipping_zone_ids']);
        }

        return response()->json($coupon->load(['products', 'categories', 'shippingZones']), 201);
    }

    public function show(Coupon $coupon)
    {
        return response()->json($coupon->load(['products', 'categories', 'shippingZones']));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'usage_limit' => 'nullable|integer|min:1',
            'min_order_value' => 'nullable|numeric|min:0',
            'max_discount_value' => 'nullable|numeric|min:0',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'exists:products,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'shipping_zone_ids' => 'nullable|array',
            'shipping_zone_ids.*' => 'exists:shipping_zones,id',
            'is_active' => 'boolean'
        ]);

        $coupon->update($validated);

        if (isset($validated['product_ids'])) {
            $coupon->products()->sync($validated['product_ids']);
        }
        
        if (isset($validated['category_ids'])) {
            $coupon->categories()->sync($validated['category_ids']);
        }

        if (isset($validated['shipping_zone_ids'])) {
            $coupon->shippingZones()->sync($validated['shipping_zone_ids']);
        }

        return response()->json($coupon->load(['products', 'categories', 'shippingZones']));
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return response()->json(null, 204);
    }
}
