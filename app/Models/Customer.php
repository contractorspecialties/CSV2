<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    /**
     * Dynamically return the correctly prefixed table name across all execution contexts.
     */
    public function getTable(): string
    {
        $defaultConnection = config('database.default', 'mysql');
        $configuredPrefix = config("database.connections.{$defaultConnection}.prefix");
        $prefix = !empty($configuredPrefix) ? $configuredPrefix : 'sc_';

        return $prefix . 'customers';
    }

    /**
     * Relationship mapping back to the anchoring parent corporate profile.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relationship mapping to track historic and current service evaluations.
     */
    public function estimates(): HasMany
    {
        return $this->hasMany(Estimate::class);
    }
}
