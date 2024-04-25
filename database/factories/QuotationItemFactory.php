<?php

namespace Database\Factories;

use App\Models\MeasurementUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuotationItem>
 */
class QuotationItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quotation_id' => rand(1, 1000),
            'product_id' => rand(1, 7164),
            'price' => fake()->randomFloat(2, 1, 100),
            'qty' => rand(1, 100),
            'measurement_unit' => MeasurementUnit::query()->inRandomOrder()->first()->value,
            'status' => ['available', 'unavailable'][rand(0, 1)],
        ];
    }
}
