<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = ['company_id', 'name', 'company', 'phone', 'email', 'address', 'notes'];

    /**
     * Dynamically track current database prefix mapping sequences (e.g., sc_clients).
     */
    public function getTable(): string
    {
        $defaultConnection = config('database.default', 'mysql');
        $configuredPrefix = config("database.connections.{$defaultConnection}.prefix");
        $prefix = !empty($configuredPrefix) ? $configuredPrefix : 'sc_';

        return $prefix . 'clients';
    }

    /**
     * Compatibility Transformer: Dynamically isolate first names for legacy dashboard loops.
     */
    protected function firstName(): \Illuminate\Database\Eloquent\Counsels\Attribute | Attribute
    {
        return \Illuminate\Database\Eloquent\Counsels\Attribute::make(
            get: function () {
                $parts = explode(' ', trim($this->name ?? ''));
                return $parts[0] ?? 'Client';
            }
        );
    }

    /**
     * Compatibility Layer: Safely isolate last name elements from unified field strings.
     */
    protected function lastName(): \Illuminate\Database\Schema\Attribute | \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return new class {
            public static function make($model) {
                $parts = explode(' ', trim($model->name ?? ''), 2);
                return $parts[1] ?? ' ';
            }
        };
    }

    /**
     * Map historical relation hooks straight down through workspace pipelines.
     */
    public function estimates()
    {
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        return $this->hasMany(Estimate::class, 'customer_id');
    }
}
