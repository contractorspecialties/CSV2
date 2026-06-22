<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Dynamically discover the active database prefix by checking existing infrastructure.
     */
    private function getPrefixedTables(): array
    {
        $prefix = '';

        // Scan for common custom prefixes utilized across your workspace environments
        foreach (['sc_', 'cs_', 'app_', 'wp_'] as $possiblePrefix) {
            if (Schema::hasTable($possiblePrefix . 'users')) {
                $prefix = $possiblePrefix;
                break;
            }
        }

        return [
            'users'     => $prefix . 'users',
            'companies' => $prefix . 'companies'
        ];
    }

    /**
     * Run the migrations to prepare the onboarding data columns.
     */
    public function up(): void
    {
        $tables = $this->getPrefixedTables();

        // 1. Add tracking parameter to the core Users table
        Schema::table($tables['users'], function (Blueprint $table) use ($tables) {
            if (!Schema::hasColumn($tables['users'], 'onboarding_completed_at')) {
                $table->timestamp('onboarding_completed_at')->nullable()->after('remember_token');
            }
        });

        // 2. Add structural business and directory parameters to the Companies table
        Schema::table($tables['companies'], function (Blueprint $table) use ($tables) {
            if (!Schema::hasColumn($tables['companies'], 'trade')) {
                $table->string('trade')->nullable()->after('name');
            }
            if (!Schema::hasColumn($tables['companies'], 'address')) {
                $table->string('address')->nullable()->after('trade');
            }
            if (!Schema::hasColumn($tables['companies'], 'city')) {
                $table->string('city')->nullable()->after('address');
            }
            if (!Schema::hasColumn($tables['companies'], 'state')) {
                $table->string('state', 2)->nullable()->after('city');
            }
            if (!Schema::hasColumn($tables['companies'], 'city_slug')) {
                $table->string('city_slug')->nullable()->after('state');
            }
            if (!Schema::hasColumn($tables['companies'], 'state_slug')) {
                $table->string('state_slug')->nullable()->after('city_slug');
            }
            if (!Schema::hasColumn($tables['companies'], 'is_publicly_listed')) {
                $table->boolean('is_publicly_listed')->default(false)->after('state_slug');
            }
            if (!Schema::hasColumn($tables['companies'], 'default_tax_rate')) {
                $table->decimal('default_tax_rate', 5, 2)->default(8.10)->after('is_publicly_listed');
            }
            if (!Schema::hasColumn($tables['companies'], 'starting_invoice_number')) {
                $table->integer('starting_invoice_number')->default(1000)->after('default_tax_rate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = $this->getPrefixedTables();

        Schema::table($tables['users'], function (Blueprint $table) {
            $table->dropColumn(['onboarding_completed_at']);
        });

        Schema::table($tables['companies'], function (Blueprint $table) {
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
