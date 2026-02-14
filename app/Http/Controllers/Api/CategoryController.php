<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::orderBy('name');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $categories = $query->get();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|array',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']['en']);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    public function show(Category $category)
    {
        $category->load('children');
        $data = $category->toArray();
        $data['name'] = $category->getTranslations('name');
        return response()->json($data);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|array',
            'name.en' => 'required_with:name|string|max:255',
            'name.ar' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|array',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->filled('slug')) {
            $validated['slug'] = Str::slug($request->input('slug'));
        } elseif (isset($validated['name']['en']) && !$category->slug) {
            // Optional: update slug if name changes
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                $oldPath = str_replace('/storage/', '', $category->image);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('categories', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        $category->update($validated);

        $data = $category->toArray();
        $data['name'] = $category->getTranslations('name');

        return response()->json($data);
    }

    public function destroy(Category $category)
    {
        if ($category->image) {
            $path = str_replace('/storage/', '', $category->image);
            \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
        }
        $category->delete();
        return response()->json(null, 204);
    }
}
