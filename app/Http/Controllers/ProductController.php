<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'nullable|numeric',
            'sort' => ['nullable', Rule::in(['relevance', 'lowest_price', 'highest_price'])],
            'keyword' => 'nullable|max:255',
            'brands' => 'nullable',
            'price_min' => 'nullable|numeric',
            'price_max' => 'nullable|numeric',
            'category' => 'nullable|required_with:sub_category',
            'sub_category' => 'nullable',
        ]);

        if ($validator->fails()) abort(404);

        $products = Product::search($request->get('keyword'), function ($meilisearch, $query, $options) use ($request) {
            if ($request->get('sort') AND $request->get('sort') === 'lowest_price') {
                $options['sort'] = ['price:asc'];
            } elseif ($request->get('sort') AND $request->get('sort') === 'highest_price') {
                $options['sort'] = ['price:desc'];
            }

            if ($request->get('brands')) {
                $filters[] = 'brand IN ['.$request->brands.']';
            }

            if ($request->get('price_min')) {
                $filters[] = 'price >= '.$request->get('price_min');
            }

            if ($request->get('price_max')) {
                $filters[] = 'price <= '.$request->get('price_max');
            }

            if ($request->get('category')) {
                $filters[] = 'category = '.$request->get('category');
            }

            if ($request->get('sub_category')) {
                $filters[] = 'sub_category = '.$request->get('sub_category');
            }

            // merge filters
            if (isset($filters) AND count($filters)) {
                $options['filter'] = implode(' AND ', $filters);
            }

            return $meilisearch->search($query, $options);
        });

        return Inertia::render('Products/Index', [
            'filters' => $request->query(),
            'products' => $products->paginate($request->get('per_page', 30))
                ->withQueryString()
                ->through(fn ($product) => [
                    'id' => $product->id,
                    'brand' => $product->brand->value,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'status' => $product->status,
                    'measurement_unit' => $product->measurementUnit->value,
                    'category' => $product->category->product_category_id
                        ? $product->category->parentCategory->value
                        : $product->category->value,
                    'sub_category' => $product->category->product_category_id
                        ? $product->category->value
                        : null,
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
