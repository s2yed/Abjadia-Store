<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingZoneController extends Controller
{
    public function index()
    {
        return ShippingZone::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'coverage_areas' => 'required', // Array/JSON
            'is_active' => 'boolean',
        ]);

        $zone = ShippingZone::create($validated);
        return response()->json($zone, 201);
    }

    public function show(ShippingZone $shippingZone)
    {
        return $shippingZone;
    }

    public function update(Request $request, ShippingZone $shippingZone)
    {
        $validated = $request->validate([
            'name' => 'sometimes',
            'coverage_areas' => 'sometimes',
            'is_active' => 'boolean',
        ]);

        $shippingZone->update($validated);
        return response()->json($shippingZone);
    }

    public function destroy(ShippingZone $shippingZone)
    {
        $shippingZone->delete();
        return response()->json(null, 204);
    }
}
