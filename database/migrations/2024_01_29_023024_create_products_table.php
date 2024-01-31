<?php

use App\Models\Brand;
use App\Models\MeasurementUnit;
use App\Models\ProductCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Brand::class);
            $table->string('name')->unique();
            $table->string('description');
            $table->unsignedDecimal('price');
            $table->unsignedInteger('stock');
            $table->foreignIdFor(MeasurementUnit::class);
            $table->foreignIdFor(ProductCategory::class);
            $table->timestamps();

            $table->fullText('name');
            $table->fullText('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
