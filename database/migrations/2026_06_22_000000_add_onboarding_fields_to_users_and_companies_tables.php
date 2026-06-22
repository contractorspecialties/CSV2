<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to prepare the onboarding data columns.
     */
    public function up(): void
    {
        // 1. Add tracking parameter to the core Users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'onboarding_completed_at')) {
                $table->timestamp('onboarding_completed_at')->nullable()->after('remember_token');
            }
        });

        // 2. Add structural business and directory parameters to the Companies table
        Schema::table('companies', function (Blueprint $table) {
            if (!Schema::hasColumn('companies', 'trade')) {
                $table->string('trade')->nullable()->after('name');
            }
            if (!Schema::hasColumn('companies', 'address')) {
                $table->string('address')->nullable()->after('trade');
            }
            if (!Schema::hasColumn('companies', 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn('companies', 'state')) {
                $table->string('state', 2)->nullable()->after('city');
            }
            if (!Schema::hasColumn('companies', 'city_slug')) {
                $table->string('city_slug')->nullable()->after('state');
            }
            if (!Schema::hasColumn('companies', 'state_slug')) {
                $table->string('state_slug')->nullable()->after('city_slug');
            }
            if (!Schema::hasColumn('companies', 'is_publicly_listed')) {
                $table->boolean('is_publicly_listed')->default(false)->after('state_slug');
            }
            if (!Schema::hasColumn('companies', 'default_tax_rate')) {
                $table->decimal('default_tax_rate', 5, 2)->default(8.10)->after('is_publicly_listed');
            }
            if (!Schema::hasColumn('companies', 'starting_invoice_number')) {
                $table->integer('starting_invoice_number')->default(1000)->after('default_tax_rate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['onboarding_completed_at']);
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'trade',
                'address',
                'city',
                'state',
                'city_slug',
                'state_slug',
                'is_publicly_listed',
                'default_tax_rate',
                'starting_invoice_number'
            ]);
        });
    }
};
