<?php

namespace App\Http\Controllers\JSON;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;

class ProductCategoryController extends Controller
{
    public function __invoke() : JsonResponse
    {
        return response()->json(ProductCategory::query()
            ->whereNull('product_category_id')
            ->with(['subCategories' => function ($query) {
                return $query->orderBy('value');
            }])
            ->get());
    }
}
