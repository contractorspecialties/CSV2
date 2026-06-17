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
        Schema::create($this->prefix . 'customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');

            // Financial Revenue Tracking Metrics
            $table->decimal('lifetime_value', 12, 2)->default(0.00); // Auto-accumulates as jobs are paid

            $table->timestamps();
            $table->softDeletes();

            // Prevents duplicate customer profiles within a single company workspace
            $table->unique(['company_id', 'email', 'phone'], 'idx_unique_company_customer_contact');

            $table->foreign('company_id')
                  ->references('id')
                  ->on($this->prefix . 'companies')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix . 'customers');
    }
};
