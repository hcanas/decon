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
            $table->string('image')->nullable()->unique();
            $table->foreignIdFor(Brand::class)->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedDecimal('price');
            $table->string('status');
            $table->foreignIdFor(MeasurementUnit::class);
            $table->foreignIdFor(ProductCategory::class);
            $table->timestamps();

            $table->unique(['name', 'brand_id']);
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
