<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveProductRequest;
use App\Models\Brand;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        return Inertia::render('Admin/Products/Index', [
            'filters' => $request->query(),
            'products' => $products->paginate($request->get('per_page', 10))
                ->withQueryString()
                ->through(fn ($product) => [
                    'id' => $product->id,
                    'brand' => $product->brand->value,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'measurement_unit' => $product->measurementUnit->value,
                    'product_category' => $product->category->product_category_id
                        ? $product->category->parentCategory->value . ':' . $product->category->value
                        : $product->category->value,
                ]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaveProductRequest $request)
    {
        DB::beginTransaction();

        // get brand
        $brand = Brand::firstOrCreate(['value' => $request->validated()['brand']]);

        // get measurement unit
        $measurementUnit = MeasurementUnit::firstOrCreate(['value' => $request->validated()['measurement_unit']]);

        // get product category
        // if input meets main:sub category format, data will be in $arr[0] and $arr[1] respectively
        $arr = explode(':', $request->validated()['product_category']);
        $productCategory = ProductCategory::firstOrCreate([
            'value' => $arr[1] ?? $arr[0],
            'product_category_id' => isset($arr[1])
                ? ProductCategory::firstOrCreate(['value' => $arr[0]])->id
                : null,
        ]);

        Product::create([
            'brand_id' => $brand->id,
            'name' => $request->validated()['name'],
            'description' => $request->validated()['description'],
            'price' => $request->validated()['price'],
            'stock' => $request->validated()['stock'],
            'measurement_unit_id' => $measurementUnit->id,
            'product_category_id' => $productCategory->id,
        ]);

        DB::commit();

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SaveProductRequest $request, Product $product)
    {
        DB::beginTransaction();

        // get brand
        // get brand
        $brand = Brand::firstOrCreate(['value' => $request->validated()['brand']]);

        // get measurement unit
        $measurementUnit = MeasurementUnit::firstOrCreate(['value' => $request->validated()['measurement_unit']]);

        // get product category
        // if input meets main:sub category format, data will be in $arr[0] and $arr[1] respectively
        $arr = explode(':', $request->validated()['product_category']);
        $productCategory = ProductCategory::firstOrCreate([
            'value' => $arr[1] ?? $arr[0],
            'product_category_id' => isset($arr[1])
                ? ProductCategory::firstOrCreate(['value' => $arr[0]])->id
                : null,
        ]);

        $product->fill([
            'brand_id' => $brand->id,
            'name' => $request->validated()['name'],
            'description' => $request->validated()['description'],
            'price' => $request->validated()['price'],
            'stock' => $request->validated()['stock'],
            'measurement_unit_id' => $measurementUnit->id,
            'product_category_id' => $productCategory->id,
        ])->save();

        DB::commit();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->back();
    }
}
