<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estimate extends Model
{
    use BelongsToTenant;

    protected $fillable = ['company_id', 'customer_id', 'estimate_number', 'status', 'subtotal', 'tax_rate', 'grand_total', 'notes', 'expires_at'];

    /**
     * Dynamically return the correctly prefixed table name across all execution contexts.
     */
    public function getTable(): string
    {
        $defaultConnection = config('database.default', 'mysql');
        $configuredPrefix = config("database.connections.{$defaultConnection}.prefix");
        $prefix = !empty($configuredPrefix) ? $configuredPrefix : 'sc_';

        return $prefix . 'estimates';
    }

    /**
     * Bind estimates securely to the unified field clients table workspace partition.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'customer_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(EstimateItem::class);
    }
}
