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
     * Enforce dynamic application table prefix boundaries upon object assembly.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $defaultConnection = config('database.default', 'sqlite');
        $configuredPrefix = config("database.connections.{$defaultConnection}.prefix");
        $prefix = !empty($configuredPrefix) ? $configuredPrefix : 'sc_';

        $this->setTable($prefix . 'customers');
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
