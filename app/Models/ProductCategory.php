<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class ProductCategory extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'value',
        'product_category_id',
    ];

    public function toSearchableArray() : array
    {
        return [
            'id' => $this->id,
            'value' => $this->product_category_id
                ? $this->parentCategory()->first()->value . ':' . $this->value
                : $this->value,
        ];
    }

    public function products() : HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function parentCategory() : BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function subCategories() : HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }
}
