<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);

        if ($request->has('search')) {
            $search = $request->input('search');
            $locale = app()->getLocale();
            $query->where("name->{$locale}", 'like', "%{$search}%");
        }

        $products = $query->paginate(10);
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'nullable|string|max:255',
            'description' => 'nullable|array',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:book,supply',
            'image' => 'nullable|image|max:2048',
            'brand_id' => 'nullable|exists:brands,id',
            'isbn' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'pages' => 'nullable|integer',
            'stock' => 'required|integer',
            'discount_price' => 'nullable|numeric|lt:price',
            'seo_title' => 'nullable|array',
            'seo_description' => 'nullable|array',
            'seo_keywords' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['name']['en']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path; // Store relative path
        }

        $product = Product::create($validated);

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'variants', 'authors']);
        // Inject translations for editing
        $data = $product->toArray();
        $data['name'] = $product->getTranslations('name');
        $data['description'] = $product->getTranslations('description');
        $data['seo_title'] = $product->getTranslations('seo_title');
        $data['seo_description'] = $product->getTranslations('seo_description');
        $data['seo_keywords'] = $product->getTranslations('seo_keywords');
        return response()->json($data);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'array',
            'name.en' => 'string|max:255',
            'name.ar' => 'nullable|string|max:255',
            'description' => 'array',
            'price' => 'numeric',
            'category_id' => 'exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'brand_id' => 'nullable|exists:brands,id',
            'isbn' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'pages' => 'nullable|integer',
            'stock' => 'integer',
            'discount_price' => 'nullable|numeric|lt:price',
            'seo_title' => 'nullable|array',
            'seo_description' => 'nullable|array',
            'seo_keywords' => 'nullable|array',
        ]);

        if (isset($validated['name']['en'])) {
            $validated['slug'] = Str::slug($validated['name']['en']);
        }

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        // Return with translations
        $data = $product->toArray();
        $data['name'] = $product->getTranslations('name');
        $data['description'] = $product->getTranslations('description');
        $data['seo_title'] = $product->getTranslations('seo_title');
        $data['seo_description'] = $product->getTranslations('seo_description');
        $data['seo_keywords'] = $product->getTranslations('seo_keywords');

        return response()->json($data);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
