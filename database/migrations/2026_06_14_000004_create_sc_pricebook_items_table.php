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
        Schema::create($this->prefix . 'pricebook_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('category');
            $table->string('unit_type'); // flat_rate, sqft, linear_ft, hourly
            $table->decimal('base_unit_cost', 12, 2)->default(0.00);
            $table->decimal('markup_percentage', 5, 2)->default(0.00);
            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')
                  ->references('id')
                  ->on($this->prefix . 'companies')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix . 'pricebook_items');
    }
};
