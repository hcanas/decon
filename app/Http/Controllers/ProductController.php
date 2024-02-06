<?php

namespace App\Http\Controllers;

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
            'page' => 'nullable|numeric',
            'per_page' => 'nullable|numeric',
            'sort_field' => ['nullable', Rule::in(['name', 'brand', 'price', 'stock', 'measurement_unit', 'product_category'])],
            'sort_order' => ['nullable', 'required_with:sort_field', Rule::in(['asc', 'desc'])],
            'keyword' => 'nullable|max:255',
        ]);

        if ($validator->fails()) abort(404);

        $products = Product::search($request->get('keyword'), function ($meilisearch, $query, $options) use ($request) {
            if (!empty($request->get('sort_field'))) {
                $options['sort'] = [$request->get('sort_field').':'.$request->get('sort_order')];
            }

            return $meilisearch->search($query, $options);
        });

        return Inertia::render('Products/Index', [
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
