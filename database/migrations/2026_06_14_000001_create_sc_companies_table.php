<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected string $prefix;

    public function __construct()
    {
        $defaultConnection = config('database.default', 'sqlite');
        $configuredPrefix = config("database.connections.{$defaultConnection}.prefix");
        $this->prefix = !empty($configuredPrefix) ? $configuredPrefix : 'sc_';
    }

    public function up(): void
    {
        Schema::create($this->prefix . 'companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique(); // For public customer review and marketing pages

            // Public Business Contact Details
            $table->string('phone_marketed')->nullable();
            $table->string('email_marketed')->nullable();
            $table->text('geo_target_cities')->nullable(); // Target service areas for local JSON-LD SEO
            $table->string('logo_path')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix . 'companies');
    }
};
