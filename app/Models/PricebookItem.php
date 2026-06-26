<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricebookItem extends Model
{
    use SoftDeletes, BelongsToTenant;

    protected $guarded = [];

    /**
     * Re-bind core lifecycle constructor footprints to track prefix sequences.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $defaultConnection = config('database.default', 'sqlite');
        $configuredPrefix = config("database.connections.{$defaultConnection}.prefix");
        $prefix = !empty($configuredPrefix) ? $configuredPrefix : 'sc_';

        $this->setTable($prefix . 'pricebook_items');
    }

    /**
     * Map workspace records straight back up to parent company corporate anchors.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
