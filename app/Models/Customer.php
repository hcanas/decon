<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'email',
        'contact_number',
        'viber_id',
    ];

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}
