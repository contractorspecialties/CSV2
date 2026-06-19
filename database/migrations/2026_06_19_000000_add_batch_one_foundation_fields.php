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

    /**
     * Run the database updates to prepare tables for new features.
     */
    public function up(): void
    {
        // 1. Upgrade Company Settings safely (skipping columns already built)
        Schema::table($this->prefix . 'companies', function (Blueprint $table) {
            if (!Schema::hasColumn($this->prefix . 'companies', 'sms_phone_number')) {
                $table->string('sms_phone_number')->nullable();
            }
            if (!Schema::hasColumn($this->prefix . 'companies', 'sms_templates')) {
                $table->json('sms_templates')->nullable();
            }
            if (!Schema::hasColumn($this->prefix . 'companies', 'is_onboarding_complete')) {
                $table->boolean('is_onboarding_complete')->default(false);
            }
        });

        // 2. Upgrade User Profiles for text-based security codes (dropping positional rules)
        $userTable = Schema::hasTable($this->prefix . 'users') ? $this->prefix . 'users' : 'users';
        Schema::table($userTable, function (Blueprint $table) use ($userTable) {
            if (!Schema::hasColumn($userTable, 'phone_2fa')) {
                $table->string('phone_2fa')->nullable();
            }
            if (!Schema::hasColumn($userTable, 'two_factor_code')) {
                $table->string('two_factor_code', 6)->nullable();
            }
            if (!Schema::hasColumn($userTable, 'two_factor_expires_at')) {
                $table->timestamp('two_factor_expires_at')->nullable();
            }
        });

        // 3. Upgrade Customer Files for long-term internal job notes
        Schema::table($this->prefix . 'customers', function (Blueprint $table) {
            if (!Schema::hasColumn($this->prefix . 'customers', 'internal_notes')) {
                $table->text('internal_notes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations cleanly if needed.
     */
    public function down(): void
    {
        Schema::table($this->prefix . 'companies', function (Blueprint $table) {
            $table->dropColumn(['sms_phone_number', 'sms_templates', 'is_onboarding_complete']);
        });

        $userTable = Schema::hasTable($this->prefix . 'users') ? $this->prefix . 'users' : 'users';
        Schema::table($userTable, function (Blueprint $table) {
            $table->dropColumn(['phone_2fa', 'two_factor_code', 'two_factor_expires_at']);
        });

        Schema::table($this->prefix . 'customers', function (Blueprint $table) {
            $table->dropColumn('internal_notes');
        });
    }
};
