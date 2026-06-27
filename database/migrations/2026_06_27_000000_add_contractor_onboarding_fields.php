<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to expand company and user operational layers defensively with prefixes.
     */
    public function up(): void
    {
        $userTableBase = (new \App\Models\User())->getTable();
        $prefix = str_contains($userTableBase, '_') ? explode('_', $userTableBase)[0] . '_' : 'sc_';

        $usersTableReal = str_contains($userTableBase, '_') ? $userTableBase : $prefix . 'users';
        $companiesTableReal = $prefix . 'companies';

        // 🏗️ Defensively calibrate the User table step tracker
        Schema::table($usersTableReal, function (Blueprint $table) use ($usersTableReal) {
            if (!Schema::hasColumn($usersTableReal, 'current_onboarding_step')) {
                $table->unsignedInteger('current_onboarding_step')->default(1)->after('company_id');
            }
        });

        // 🏗️ Individually calibrate Company columns to prevent legacy attribute collisions
        Schema::table($companiesTableReal, function (Blueprint $table) use ($companiesTableReal) {
            if (!Schema::hasColumn($companiesTableReal, 'owner_name')) {
                $table->string('owner_name')->nullable();
            }
            if (!Schema::hasColumn($companiesTableReal, 'business_phone')) {
                $table->string('business_phone')->nullable();
            }
            if (!Schema::hasColumn($companiesTableReal, 'base_city')) {
                $table->string('base_city')->nullable();
            }
            if (!Schema::hasColumn($companiesTableReal, 'service_radius_miles')) {
                $table->unsignedInteger('service_radius_miles')->default(25);
            }
            if (!Schema::hasColumn($companiesTableReal, 'primary_specialty')) {
                $table->string('primary_specialty')->nullable();
            }
            if (!Schema::hasColumn($companiesTableReal, 'logo_path')) {
                $table->string('logo_path')->nullable();
            }
            if (!Schema::hasColumn($companiesTableReal, 'years_experience')) {
                $table->unsignedInteger('years_experience')->nullable();
            }
            if (!Schema::hasColumn($companiesTableReal, 'license_number')) {
                $table->string('license_number')->nullable();
            }
            if (!Schema::hasColumn($companiesTableReal, 'is_insured')) {
                $table->boolean('is_insured')->default(false);
            }
            if (!Schema::hasColumn($companiesTableReal, 'company_bio_short')) {
                $table->text('company_bio_short')->nullable();
            }
            if (!Schema::hasColumn($companiesTableReal, 'company_bio_long')) {
                $table->text('company_bio_long')->nullable();
            }
            if (!Schema::hasColumn($companiesTableReal, 'service_tags')) {
                $table->json('service_tags')->nullable();
            }
            if (!Schema::hasColumn($companiesTableReal, 'emergency_availability')) {
                $table->boolean('emergency_availability')->default(false);
            }
            if (!Schema::hasColumn($companiesTableReal, 'media_gallery')) {
                $table->json('media_gallery')->nullable();
            }
            if (!Schema::hasColumn($companiesTableReal, 'social_links')) {
                $table->json('social_links')->nullable();
            }
            if (!Schema::hasColumn($companiesTableReal, 'service_descriptions')) {
                $table->json('service_descriptions')->nullable();
            }
            if (!Schema::hasColumn($companiesTableReal, 'crew_structure')) {
                $table->string('crew_structure')->default('solo');
            }
            if (!Schema::hasColumn($companiesTableReal, 'invoice_preferences')) {
                $table->string('invoice_preferences')->nullable();
            }
            if (!Schema::hasColumn($companiesTableReal, 'deposit_rules')) {
                $table->text('deposit_rules')->nullable();
            }
            if (!Schema::hasColumn($companiesTableReal, 'faq_blocks')) {
                $table->json('faq_blocks')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $userTableBase = (new \App\Models\User())->getTable();
        $prefix = str_contains($userTableBase, '_') ? explode('_', $userTableBase)[0] . '_' : 'sc_';

        $usersTableReal = str_contains($userTableBase, '_') ? $userTableBase : $prefix . 'users';
        $companiesTableReal = $prefix . 'companies';

        Schema::table($usersTableReal, function (Blueprint $table) use ($usersTableReal) {
            if (Schema::hasColumn($usersTableReal, 'current_onboarding_step')) {
                $table->dropColumn('current_onboarding_step');
            }
        });

        Schema::table($companiesTableReal, function (Blueprint $table) use ($companiesTableReal) {
            // Drop only the freshly introduced pipeline metrics, leaving old legacy variables untouched
            $targets = [
                'owner_name', 'business_phone', 'base_city', 'service_radius_miles', 'primary_specialty',
                'years_experience', 'license_number', 'is_insured', 'company_bio_short', 'company_bio_long',
                'service_tags', 'emergency_availability', 'media_gallery', 'social_links', 'service_descriptions',
                'crew_structure', 'invoice_preferences', 'deposit_rules', 'faq_blocks'
            ];

            foreach ($targets as $column) {
                if (Schema::hasColumn($companiesTableReal, $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
