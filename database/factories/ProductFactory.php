<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\MeasurementUnit;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'brand_id' => Brand::query()->inRandomOrder()->first(),
            'name' => fake()->unique()->words(rand(1, 3), true),
            'description' => fake()->words(rand(1, 10), true),
            'price' => fake()->randomFloat(2, 1, 1000),
            'status' => ['available', 'unavailable'][rand(0, 1)],
            'measurement_unit_id' => MeasurementUnit::query()->inRandomOrder()->first(),
            'product_category_id' => ProductCategory::query()->inRandomOrder()->first(),
        ];
    }
}
