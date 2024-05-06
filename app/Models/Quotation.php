<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Quotation extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'customer_id',
        'status',
    ];

    public function toSearchableArray()
    {
        return [
            'customer' => $this->customer()->first(),
            'status' => $this->status,
            'created_at' => strtotime($this->created_at),
            'purchase_order' => $this->purchaseOrder()->first(),
        ];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function purchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::class);
    }
}
