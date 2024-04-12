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
        'status',
        'measurement_unit_id',
        'product_category_id',
    ];

    public function toSearchableArray() : array
    {
        $category = $this->category()->first();

        return [
            'id' => $this->id,
            'brand' => $this->brand()->first()->value,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'status' => $this->status,
            'measurement_unit' => $this->measurementUnit()->first()->value,
            'category' => $category->product_category_id ? $category->parentCategory()->first()->value : $category->value,
            'sub_category' => $category->product_category_id ? $category->value : null,
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

    public function quotationItems()
    {
        return $this->hasMany(QuotationItem::class);
    }
}
