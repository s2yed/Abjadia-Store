<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Translator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TranslatorController extends Controller
{
    public function index(Request $request)
    {
        $query = Translator::orderBy('name');

        if ($request->has('search')) {
            $search = $request->input('search');
            $locale = app()->getLocale();
            $query->where("name->{$locale}", 'like', "%{$search}%");
        }

        $translators = $query->get();
        return response()->json($translators);
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
            $path = $request->file('image')->store('translators', 'public');
            $validated['image'] = $path;
        }

        $translator = Translator::create($validated);

        return response()->json($translator, 201);
    }

    public function show(Translator $translator)
    {
        $data = $translator->toArray();
        $data['name'] = $translator->getTranslations('name');
        $data['bio'] = $translator->getTranslations('bio');
        return response()->json($data);
    }

    public function update(Request $request, Translator $translator)
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
            if ($translator->image) {
                Storage::disk('public')->delete($translator->image);
            }
            $path = $request->file('image')->store('translators', 'public');
            $validated['image'] = $path;
        }

        $translator->update($validated);

        $data = $translator->toArray();
        $data['name'] = $translator->getTranslations('name');
        $data['bio'] = $translator->getTranslations('bio');

        return response()->json($data);
    }

    public function destroy(Translator $translator)
    {
        if ($translator->image) {
            Storage::disk('public')->delete($translator->image);
        }
        $translator->delete();
        return response()->json(null, 204);
    }
}
