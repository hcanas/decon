<?php

namespace Database\Seeders;

use App\Models\PurchaseOrder;
use App\Models\Quotation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = Quotation::where('status', 'confirmed')->get()->count();
        PurchaseOrder::factory($count)->create();
    }
}
