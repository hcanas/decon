<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class MeasurementUnit extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'value',
    ];

    public function toSearchableArray() : array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
        ];
    }

    public function products() : HasMany
    {
        return $this->hasMany(Product::class);
    }
}
