<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProductCategorySearchController extends Controller
{
    public function __invoke(Request $request) : JsonResponse
    {
        $categories = ProductCategory::search($request->get('keyword'))
            ->query(function ($query) {
                return $query->with('parentCategory')
                    ->withCount('products');
            })
            ->take(5)
            ->get();

        $categories = Arr::map($categories->toArray(), function ($category) {
            return $category['product_category_id']
                    ? $category['parent_category']['value'] . ':' . $category['value']
                    : $category['value'];
        });

        return response()->json($categories);
    }
}
