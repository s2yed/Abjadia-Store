<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        return response()->json(Offer::latest()->paginate(20));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'conditions' => 'nullable|array',
            'actions' => 'nullable|array',
            'priority' => 'integer',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $offer = Offer::create($validated);
        return response()->json($offer, 201);
    }

    public function show(Offer $offer)
    {
        return response()->json($offer);
    }

    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'type' => 'required|string',
            'conditions' => 'nullable|array',
            'actions' => 'nullable|array',
            'priority' => 'integer',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        $offer->update($validated);
        return response()->json($offer);
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        return response()->json(null, 204);
    }
}
