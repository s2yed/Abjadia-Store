<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::orderBy('name');

        if ($request->has('search')) {
            $search = $request->input('search');
            $locale = app()->getLocale();
            $query->where("name->{$locale}", 'like', "%{$search}%");
        }

        $authors = $query->get();
        return response()->json($authors);
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
            $path = $request->file('image')->store('authors', 'public');
            $validated['image'] = $path;
        }

        $author = Author::create($validated);

        return response()->json($author, 201);
    }

    public function show(Author $author)
    {
        $data = $author->toArray();
        $data['name'] = $author->getTranslations('name');
        $data['bio'] = $author->getTranslations('bio');
        return response()->json($data);
    }

    public function update(Request $request, Author $author)
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
            if ($author->image) {
                Storage::disk('public')->delete($author->image);
            }
            $path = $request->file('image')->store('authors', 'public');
            $validated['image'] = $path;
        }

        $author->update($validated);

        $data = $author->toArray();
        $data['name'] = $author->getTranslations('name');
        $data['bio'] = $author->getTranslations('bio');

        return response()->json($data);
    }

    public function destroy(Author $author)
    {
        if ($author->image) {
            Storage::disk('public')->delete($author->image);
        }
        $author->delete();
        return response()->json(null, 204);
    }
}
