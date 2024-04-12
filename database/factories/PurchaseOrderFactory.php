<?php

namespace Database\Factories;

use App\Models\Quotation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrder>
 */
class PurchaseOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $ids = Quotation::where('status', 'confirmed')
            ->get()
            ->pluck('id');

        return [
            'reference_number' => fake()->unique()->numberBetween(240420, 241231).'-'.bin2hex(random_bytes(3)),
            'quotation_id' => fake()->unique()->randomElement($ids),
            'payment_details' => null,
            'delivery_date' => null,
            'status' => 'unpaid',
        ];
    }
}
