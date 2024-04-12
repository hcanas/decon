<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class PurchaseOrder extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'reference_number',
        'quotation_id',
        'payment_details',
        'delivery_date',
        'status',
    ];

    public function toSearchableArray()
    {
        return [
            'reference_number' => $this->reference_number,
            'payment_details' => $this->payment_details,
            'delivery_date' => strtotime($this->delivery_date),
            'status' => $this->status,
            'customer' => $this->quotation()->first()->customer()->first(),
            'created_at' => strtotime($this->created_at),
            'updated_at' => strtotime($this->updated_at),
        ];
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}
