<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;

class ImageApiController extends Controller
{
    public function all (Request $request) {
        $page = $request->query('page', 1);
        $limit = $request->query('limit', 10);

        $images = Image::paginate($limit, ['*'], 'page', $page);
        return response()->json($images);
    }

    public function get ($id) {
        $image = Image::find((int)$id);

        if(!$image) return response()->json(['error' => 'Image not found'], 404);

        return response()->json($image);
    }
}
