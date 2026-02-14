<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingCompany;
use Illuminate\Http\Request;

class ShippingCompanyController extends Controller
{
    public function index()
    {
        return ShippingCompany::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required', // Array/JSON
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('shipping_companies', 'public');
            $validated['logo'] = $path;
        }

        $company = ShippingCompany::create($validated);
        return response()->json($company, 201);
    }

    public function show(ShippingCompany $shippingCompany)
    {
        return $shippingCompany;
    }

    public function update(Request $request, ShippingCompany $shippingCompany)
    {
        $validated = $request->validate([
            'name' => 'sometimes',
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('shipping_companies', 'public');
            $validated['logo'] = $path;
        }

        $shippingCompany->update($validated);
        return response()->json($shippingCompany);
    }

    public function destroy(ShippingCompany $shippingCompany)
    {
        $shippingCompany->delete();
        return response()->json(null, 204);
    }
}
