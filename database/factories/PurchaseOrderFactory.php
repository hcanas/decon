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

        $date = date("Y-m-d H:i:s", mt_rand(strtotime('2024-01-01'), strtotime('2024-12-31')));
        $status = ['unpaid', 'paid', 'for delivery', 'delivered', 'cancelled'][rand(0, 4)];

        if ($status === 'unpaid' OR $status === 'cancelled') {
            $payment_details = null;
            $delivery_date = null;
        } else {
            $payment_details = ['Gcash', 'BPI', 'Maya', 'BDO', 'LBP'][rand(0, 4)].' - '.hexdec(bin2hex((random_bytes(6))));
            $delivery_date = null;

            if ($status !== 'paid') {
                $delivery_date = date("Y-m-d H:i:s", strtotime("$date +10 days"));
            }
        }

        return [
            'reference_number' => fake()->unique()->numberBetween(240420, 241231).'-'.bin2hex(random_bytes(3)),
            'quotation_id' => fake()->unique()->randomElement($ids),
            'payment_details' => $payment_details,
            'delivery_date' => $delivery_date,
            'status' => $status,
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
