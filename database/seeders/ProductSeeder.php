<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\MeasurementUnit;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        // create brands
        Brand::factory(100)->create();

        // create product categories
        ProductCategory::factory(20)->create();

        // create units of measurement
        MeasurementUnit::factory(50)->create();

        // create products
        Product::factory(200)->create();

        DB::commit();
    }
}
