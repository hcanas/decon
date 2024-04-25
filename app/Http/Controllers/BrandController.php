<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\JsonResponse;

class BrandController extends Controller
{
    public function __invoke() :JsonResponse
    {
        return response()->json(Brand::query()
            ->orderBy('value')
            ->get());
    }
}
