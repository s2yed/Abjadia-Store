<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $query = Publisher::orderBy('name');

        if ($request->has('search')) {
            $search = $request->input('search');
            $locale = app()->getLocale();
            $query->where("name->{$locale}", 'like', "%{$search}%");
        }

        $publishers = $query->get();
        return response()->json($publishers);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'nullable|string|max:255',
            'bio' => 'nullable|array',
            'bio.en' => 'nullable|string',
            'bio.ar' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('publishers', 'public');
            $validated['image'] = $path;
        }

        $publisher = Publisher::create($validated);

        return response()->json($publisher, 201);
    }

    public function show(Publisher $publisher)
    {
        $data = $publisher->toArray();
        $data['name'] = $publisher->getTranslations('name');
        $data['bio'] = $publisher->getTranslations('bio');
        return response()->json($data);
    }

    public function update(Request $request, Publisher $publisher)
    {
        $validated = $request->validate([
            'name' => 'array',
            'name.en' => 'string|max:255',
            'name.ar' => 'nullable|string|max:255',
            'bio' => 'array',
            'bio.en' => 'nullable|string',
            'bio.ar' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($publisher->image) {
                Storage::disk('public')->delete($publisher->image);
            }
            $path = $request->file('image')->store('publishers', 'public');
            $validated['image'] = $path;
        }

        $publisher->update($validated);

        $data = $publisher->toArray();
        $data['name'] = $publisher->getTranslations('name');
        $data['bio'] = $publisher->getTranslations('bio');

        return response()->json($data);
    }

    public function destroy(Publisher $publisher)
    {
        if ($publisher->image) {
            Storage::disk('public')->delete($publisher->image);
        }
        $publisher->delete();
        return response()->json(null, 204);
    }
}
