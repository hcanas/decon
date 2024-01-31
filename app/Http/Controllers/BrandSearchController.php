<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandSearchController extends Controller
{
    public function __invoke(Request $request) : JsonResponse
    {
        return response()->json(Brand::search($request->get('keyword'))
            ->take(5)
            ->get()
            ->pluck('value')
        );
    }
}
