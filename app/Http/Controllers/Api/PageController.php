<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::all();
        return response()->json($pages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|unique:pages,slug',
            'title' => 'required|array',
            'content' => 'required|array',
            'meta_description' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('pages', 'public');
            $validated['image_path'] = $path;
        }

        $page = Page::create($validated);

        return response()->json($page, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $page = Page::findOrFail($id);
        return response()->json($page);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'slug' => 'required|unique:pages,slug,' . $id,
            'title' => 'required|array',
            'content' => 'required|array',
            'meta_description' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($page->image_path) {
                Storage::disk('public')->delete($page->image_path);
            }
            $path = $request->file('image')->store('pages', 'public');
            $validated['image_path'] = $path;
        }

        $page->update($validated);

        return response()->json($page);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $page = Page::findOrFail($id);

        if ($page->image_path) {
            Storage::disk('public')->delete($page->image_path);
        }

        $page->delete();

        return response()->json(null, 204);
    }
}
