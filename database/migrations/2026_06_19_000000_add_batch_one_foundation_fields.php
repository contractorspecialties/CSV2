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
        // Maintain consistency with your custom sc_ prefix tracking setup
        $this->prefix = !empty($configuredPrefix) ? $configuredPrefix : 'sc_';
    }

    /**
     * Run the database updates to prepare tables for new features.
     */
    public function up(): void
    {
        // 1. Upgrade Company Settings for Onboarding & Saved Message Templates
        Schema::table($this->prefix . 'companies', function (Blueprint $table) {
            if (!Schema::hasColumn($this->prefix . 'companies', 'sms_phone_number')) {
                $table->string('sms_phone_number')->nullable()->after('logo_path');
            }
            $table->json('sms_templates')->nullable()->after('sms_phone_number');
            $table->boolean('is_onboarding_complete')->default(false)->after('sms_templates');
        });

        // 2. Upgrade User Profiles to support Text-Based Security Logins (2FA)
        // Checking for prefix safety on the base users structure
        $userTable = Schema::hasTable($this->prefix . 'users') ? $this->prefix . 'users' : 'users';
        Schema::table($userTable, function (Blueprint $table) {
            $table->string('phone_2fa')->nullable()->after('last_name');
            $table->string('two_factor_code', 6)->nullable()->after('token_expires_at');
            $table->timestamp('two_factor_expires_at')->nullable()->after('two_factor_code');
        });

        // 3. Upgrade Customer Files for long-term internal job notes
        Schema::table($this->prefix . 'customers', function (Blueprint $table) {
            $table->text('internal_notes')->nullable()->after('lifetime_value');
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
