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
        if ($request->has('list_only')) {
            $products = Product::select('id', 'name')->get();
            // Transform to ensure translations are available if needed,
            // though standard toArray() usually handles it.
            // We return strictly 'data' key to match pagination structure for frontend
            return response()->json(['data' => $products]);
        }

        $query = Product::with(['category', 'brand', 'publisher', 'authors', 'translators']);

        if ($request->has('search')) {
            $search = $request->input('search');
            $locale = app()->getLocale();
            $query->where("name->{$locale}", 'like', "%{$search}%");
        }

        $perPage = $request->input('per_page', 10);
        $products = $query->paginate($perPage);
        return response()->json($products);
    }

    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Product store request', $request->all());
        $validated = $request->validate([
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'nullable|string|max:255',
            'description' => 'nullable|array',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:book,supply',
            'image' => 'nullable|image|max:2048',
            'brand_id' => 'nullable|exists:brands,id',
            'isbn' => 'nullable|string|max:255',
            'publisher_id' => 'nullable|exists:publishers,id',
            'authors' => 'nullable|array',
            'authors.*' => 'exists:authors,id',
            'translators' => 'nullable|array',
            'translators.*' => 'exists:translators,id',
            'publication_year' => 'nullable|integer',
            'pages' => 'nullable|integer',
            'seo_title' => 'nullable|array',
            'seo_description' => 'nullable|array',
            'seo_keywords' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['name']['en']);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/products', 'public');
            $validated['image'] = $path; // Store relative path starting with images/
        }

        $product = Product::create($validated);

        if ($request->has('authors')) {
            $product->authors()->sync($request->authors);
        }

        if ($request->has('translators')) {
            $product->translators()->sync($request->translators);
        }

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'variants', 'authors', 'translators', 'publisher']);
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
        \Illuminate\Support\Facades\Log::info('Product update request', $request->all());
        $validated = $request->validate([
            'name' => 'array',
            'name.en' => 'string|max:255',
            'name.ar' => 'nullable|string|max:255',
            'description' => 'array',
            'price' => 'numeric',
            'discount_price' => 'nullable|numeric',
            'stock' => 'integer',
            'category_id' => 'exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'brand_id' => 'nullable|exists:brands,id',
            'isbn' => 'nullable|string|max:255',
            'publisher_id' => 'nullable|exists:publishers,id',
            'authors' => 'nullable|array',
            'authors.*' => 'exists:authors,id',
            'translators' => 'nullable|array',
            'translators.*' => 'exists:translators,id',
            'publication_year' => 'nullable|integer',
            'pages' => 'nullable|integer',
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
            $path = $request->file('image')->store('images/products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        if ($request->has('authors')) {
            $product->authors()->sync($request->authors);
        }

        if ($request->has('translators')) {
            $product->translators()->sync($request->translators);
        }

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
