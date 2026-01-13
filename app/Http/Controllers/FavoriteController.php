<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function toggle(Request $request, \App\Models\Product $product)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $message = '';

        // Check if product is already in favorites
        if ($user->favorites()->where('product_id', $product->id)->exists()) {
            $user->favorites()->where('product_id', $product->id)->delete();
            $message = __('Product removed from favorites.');
        } else {
            $user->favorites()->create(['product_id' => $product->id]);
            $message = __('Product added to favorites.');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => $user->favorites()->where('product_id', $product->id)->exists() ? 'added' : 'removed',
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
