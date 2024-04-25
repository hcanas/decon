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
//        Brand::factory(100)->create();

        // create product categories
        $file = fopen(database_path('Categories.csv'), 'r');
        $temp_category = '';

        while (($data = fgetcsv($file)) !== FALSE) {
            if (empty($temp_category) OR $temp_category->value !== $data[0]) {
                $temp_category = ProductCategory::create(['value' => $data[0]]);
            }

            if (!empty($data[1])) {
                ProductCategory::create([
                    'value' => $data[1],
                    'product_category_id' => $temp_category->id,
                ]);
            }
        }

        fclose($file);

        // create units of measurement
        $file = fopen(database_path('MeasurementUnits.csv'), 'r');

        while (($data = fgetcsv($file)) !== FALSE) {
            MeasurementUnit::create([
                'value' => $data[0],
            ]);
        }

        fclose($file);

        // create products
        // test kits
        $file = fopen(database_path('TestKits.csv'), 'r');

        while (($data = fgetcsv($file)) !== FALSE) {
            Product::create([
                'image' => null,
                'brand_id' => null,
                'name' => $data[1],
                'description' => null,
                'price' => fake()->randomFloat(2, 100, 5000),
                'status' => ['available', 'unavailable', 'archived'][rand(0, 2)],
                'measurement_unit_id' => MeasurementUnit::where('value', $data[2])->first()->id,
                'product_category_id' => ProductCategory::where('value', $data[0])->first()->id,
                'created_at' => '2024-01-01 00:00:00',
                'updated_at' => '2024-01-01 00:00:00',
            ]);
        }

        fclose($file);

        // Vaccines
        $file = fopen(database_path('Vaccines.csv'), 'r');

        while (($data = fgetcsv($file)) !== FALSE) {
            Product::create([
                'image' => null,
                'brand_id' => null,
                'name' => $data[1],
                'description' => null,
                'price' => fake()->randomFloat(2, 100, 5000),
                'status' => ['available', 'unavailable', 'archived'][rand(0, 2)],
                'measurement_unit_id' => MeasurementUnit::where('value', $data[2])->first()->id,
                'product_category_id' => ProductCategory::where('value', $data[0])->first()->id,
                'created_at' => '2024-01-01 00:00:00',
                'updated_at' => '2024-01-01 00:00:00',
            ]);
        }

        fclose($file);

        // Medicine Supplies
        $file = fopen(database_path('MedicineSupplies.csv'), 'r');

        while (($data = fgetcsv($file)) !== FALSE) {
            Product::create([
                'image' => null,
                'brand_id' => null,
                'name' => $data[0],
                'description' => null,
                'price' => fake()->randomFloat(2, 100, 5000),
                'status' => ['available', 'unavailable', 'archived'][rand(0, 2)],
                'measurement_unit_id' => MeasurementUnit::where('value', $data[1])->first()->id,
                'product_category_id' => ProductCategory::where('value', 'Medicine Supplies')->first()->id,
                'created_at' => '2024-01-01 00:00:00',
                'updated_at' => '2024-01-01 00:00:00',
            ]);
        }

        fclose($file);

        DB::commit();
    }
}
