<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    protected $fillable = ['company_id', 'customer_id', 'estimate_number', 'status', 'subtotal', 'tax_rate', 'grand_total', 'notes', 'expires_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(EstimateItem::class);
    }
}
