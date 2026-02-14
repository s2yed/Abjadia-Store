<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Banner::orderBy('position')->orderBy('order');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        return $query->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|array',
            'title.en' => 'required|string|max:255',
            'title.ar' => 'nullable|string|max:255',
            'image' => 'required|image|max:2048', // 'image' field in form data, maps to 'image_path' in DB
            'link' => 'nullable|url',
            'position' => 'required|string',
            'is_active' => 'boolean',
            'order' => 'integer',
            'description' => 'nullable|array',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $validated['image_path'] = $path;
        }

        // Remove 'image' from validated array if it exists as we mapped it to image_path
        unset($validated['image']);

        $banner = Banner::create($validated);
        return response()->json($banner, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $banner = Banner::findOrFail($id);
        $data = $banner->toArray();
        $data['title'] = $banner->getTranslations('title');
        $data['description'] = $banner->getTranslations('description');
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $banner = Banner::findOrFail($id);

        $validated = $request->validate([
            'title' => 'array',
            'title.en' => 'string|max:255',
            'title.ar' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'link' => 'nullable|url',
            'position' => 'sometimes|required|string',
            'is_active' => 'boolean',
            'order' => 'integer',
            'description' => 'nullable|array',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image_path) {
                // remove /storage/ prefix to delete from disk
                $path = str_replace('/storage/', '', $banner->image_path);
                Storage::disk('public')->delete($path);
            }

            $path = $request->file('image')->store('banners', 'public');
            $validated['image_path'] = $path;
        }

        unset($validated['image']);

        $banner->update($validated);

        $data = $banner->toArray();
        $data['title'] = $banner->getTranslations('title');
        $data['description'] = $banner->getTranslations('description');

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $banner = Banner::findOrFail($id);

        if ($banner->image_path) {
            $path = str_replace('/storage/', '', $banner->image_path);
            Storage::disk('public')->delete($path);
        }

        $banner->delete();
        return response()->noContent();
    }
}
