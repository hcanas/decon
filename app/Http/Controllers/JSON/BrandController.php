<?php

namespace App\Http\Controllers\JSON;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __invoke() :JsonResponse
    {
        return response()->json(Brand::query()
            ->withCount('products')
            ->orderBy('value')
            ->get());
    }
}
