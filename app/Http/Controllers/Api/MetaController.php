<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Brand;
use Illuminate\Http\Request;

class MetaController extends Controller
{
    public function brands()
    {
        return response()->json(['data' => Brand::all()]);
    }

    public function authors()
    {
        return response()->json(['data' => Author::all()]);
    }
}
