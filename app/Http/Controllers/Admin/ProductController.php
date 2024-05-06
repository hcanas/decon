<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ActivityLog;
use App\Models\Brand;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'nullable|numeric',
            'keyword' => 'nullable|max:255',
            'brands' => 'nullable',
            'category' => 'nullable|required_with:sub_category',
            'sub_category' => 'nullable',
            'status' => [
                'nullable',
                Rule::in(['available', 'unavailable', 'archived']),
            ],
        ]);

        if ($validator->fails()) abort(404);

        $products = Product::search($request->get('keyword'), function ($meilisearch, $query, $options) use ($validator) {
            if (!empty($validator->validated()['brands'])) {
                // enclose each value in quotation marks
                $brands = array_map(function ($brand) {
                    return '"'.$brand.'"';
                }, explode(',', $validator->validated()['brands']));

                $filters[] = 'brand IN ['.implode(',', $brands).']';
            }

            if (!empty($validator->validated()['category'])) {
                $filters[] = 'category = "'.$validator->validated()['category'].'"';
            }

            if (!empty($validator->validated()['sub_category'])) {
                $filters[] = 'sub_category = "'.$validator->validated()['sub_category'].'"';
            }

            if (!empty($validator->validated()['status'])) {
                $filters[] = 'status = '.$validator->validated()['status'];
            }

            // merge filters
            if (isset($filters) AND count($filters)) {
                $options['filter'] = implode(' AND ', $filters);
            }

            return $meilisearch->search($query, $options);
        });

        return Inertia::render('Products/AdminIndex', [
            'filters' => $request->query(),
            'products' => $products->paginate($request->get('per_page', 10))
                ->withQueryString()
                ->through(fn ($product) => [
                    'id' => $product->id,
                    'image' => $product->image,
                    'brand' => $product->brand?->value,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'measurement_unit' => $product->measurementUnit->value,
                    'category' => $product->category->product_category_id
                        ? $product->category->parentCategory->value
                        : $product->category->value,
                    'sub_category' => $product->category->product_category_id
                        ? $product->category->value
                        : null,
                    'status' => $product->status,
                ]),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProductRequest $request)
    {
        DB::beginTransaction();

        // get brand
        if ($request->validated()['brand']) {
            $brand = Brand::firstOrCreate(['value' => $request->validated()['brand']]);
        }

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

        if ($request->validated()['image']) {
            $file = $request->file('image');
            $filename = bin2hex(random_bytes(8)).'.webp';
        }

        Product::create([
            'image' => isset($filename) ? $filename : null,
            'brand_id' => isset($brand) ? $brand->id : null,
            'name' => $request->validated()['name'],
            'description' => $request->validated()['description'],
            'price' => $request->validated()['price'],
            'status' => 'available',
            'measurement_unit_id' => $measurementUnit->id,
            'product_category_id' => $productCategory->id,
        ]);

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'category' => 'products',
            'description' => 'Added a new product - '.$request->validated()['name'].'.',
        ]);

        if (isset($file)) {
            $image = Image::read($file);
            $image->resize(300, 300);
            Storage::put('public/images/'.$filename, (string) $image->encode());
        }

        DB::commit();

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        DB::beginTransaction();

        // get brand
        if ($request->validated('brand')) {
            $brand = Brand::firstOrCreate(['value' => $request->validated('brand')]);
            $product->fill(['brand_id' => $brand->id]);
        }

        // get measurement unit
        if ($request->validated('measurement_unit')) {
            $measurementUnit = MeasurementUnit::firstOrCreate(['value' => $request->validated('measurement_unit')]);
            $product->fill(['measurement_unit_id' => $measurementUnit->id]);
        }

        // get product category
        // if input meets main:sub category format, data will be in $arr[0] and $arr[1] respectively
        if ($request->validated('product_category')) {
            $arr = explode(':', $request->validated('product_category'));
            $productCategory = ProductCategory::firstOrCreate([
                'value' => $arr[1] ?? $arr[0],
                'product_category_id' => isset($arr[1])
                    ? ProductCategory::firstOrCreate(['value' => $arr[0]])->id
                    : null,
            ]);
            $product->fill(['product_category_id' => $productCategory->id]);
        }

        if ($request->validated('name')) {
            $product->fill(['name' => $request->validated('name')]);
        }

        if ($request->validated('description')) {
            $product->fill(['description' => $request->validated('description')]);
        }

        if ($request->validated('price')) {
            $product->fill(['price' => $request->validated('price')]);
        }

        if ($request->validated('status')) {
            $product->fill(['status' => $request->validated('status')]);
        }

        if ($request->validated('image')) {
            $file = $request->file('image');
            $filename = $product->image ?? bin2hex(random_bytes(8)).'.webp';
            $product->fill(['image' => $filename]);
        }

        $product->save();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'category' => 'products',
            'description' => 'Updated product details of '.$product->name.'.',
        ]);

        if (isset($file)) {
            $image = Image::read($file);
            $image->resize(300, 300);
            Storage::put('public/images/'.$filename, (string) $image->encode());
        }

        DB::commit();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();

        $product->delete();

        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'category' => 'products',
            'description' => 'Deleted product - '.$product->name.'.',
        ]);

        DB::commit();

        return redirect()->back();
    }
}
