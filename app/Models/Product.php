<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'brand_id',
        'name',
        'description',
        'price',
        'stock',
        'measurement_unit_id',
        'product_category_id',
    ];

    public function toSearchableArray() : array
    {
        return [
            'id' => $this->id,
            'brand' => $this->brand()->first()->value,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'stock' => (int) $this->stock,
            'measurement_unit' => $this->measurementUnit()->first()->value,
            'product_category' => $this->category()->first()->product_category_id
                ? $this->category()->first()->parentCategory()->first()->value . ':' . $this->category()->first()->value
                : $this->category()->first()->value,
        ];
    }

    public function brand() : BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function measurementUnit() : BelongsTo
    {
        return $this->belongsTo(MeasurementUnit::class);
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
}
