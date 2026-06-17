<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobAttachment extends Model
{
    protected $fillable = ['estimate_id', 'appointment_id', 'file_path', 'file_type', 'caption'];

    public function getTable(): string
    {
        $defaultConnection = config('database.default', 'mysql');
        $configuredPrefix = config("database.connections.{$defaultConnection}.prefix");
        $prefix = !empty($configuredPrefix) ? $configuredPrefix : 'sc_';
        return $prefix . 'job_attachments';
    }

    public function estimate(): BelongsTo
    {
        return $this->belongsTo(Estimate::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }
}
