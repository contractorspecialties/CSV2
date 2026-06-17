<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Appointment extends Model
{
    protected $fillable = ['company_id', 'customer_id', 'estimate_id', 'title', 'scheduled_at', 'status', 'notes'];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function getTable(): string
    {
        $defaultConnection = config('database.default', 'mysql');
        $configuredPrefix = config("database.connections.{$defaultConnection}.prefix");
        $prefix = !empty($configuredPrefix) ? $configuredPrefix : 'sc_';
        return $prefix . 'appointments';
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function estimate(): BelongsTo
    {
        return $this->belongsTo(Estimate::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(JobAttachment::class);
    }
}
