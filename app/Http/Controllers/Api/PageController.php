<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

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
        $request->validate([
            'slug' => 'required|unique:pages,slug',
            'title' => 'required|array',
            'content' => 'required|array',
        ]);

        $page = Page::create($request->all());

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

        $request->validate([
            'slug' => 'required|unique:pages,slug,' . $id,
            'title' => 'required|array',
            'content' => 'required|array',
            'is_active' => 'boolean',
        ]);

        $page->update($request->all());

        return response()->json($page);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return response()->json(null, 204);
    }
}
