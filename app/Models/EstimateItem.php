<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstimateItem extends Model
{
    protected $fillable = ['estimate_id', 'description', 'quantity', 'unit_price', 'total_price'];

    /**
     * Dynamically return the correctly prefixed table name across all execution contexts.
     */
    public function getTable(): string
    {
        $defaultConnection = config('database.default', 'mysql');
        $configuredPrefix = config("database.connections.{$defaultConnection}.prefix");
        $prefix = !empty($configuredPrefix) ? $configuredPrefix : 'sc_';

        return $prefix . 'estimate_items';
    }

    public function estimate(): BelongsTo
    {
        return $this->belongsTo(Estimate::class);
    }
}
