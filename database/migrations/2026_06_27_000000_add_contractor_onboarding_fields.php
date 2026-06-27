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
        // 🏗️ Dynamically extract the prefix from the active User model table name
        $usersTableReal = (new \App\Models\User())->getTable();
        $prefix = str_contains($usersTableReal, '_') ? explode('_', $usersTableReal)[0] . '_' : '';
        $companiesTableReal = $prefix . 'companies';

        Schema::table($usersTableReal, function (Blueprint $table) use ($usersTableReal) {
            if (!Schema::hasColumn($usersTableReal, 'current_onboarding_step')) {
                $table->unsignedInteger('current_onboarding_step')->default(1)->after('company_id');
            }
        });

        Schema::table($companiesTableReal, function (Blueprint $table) use ($companiesTableReal) {
            if (!Schema::hasColumn($companiesTableReal, 'owner_name')) {
                $table->string('owner_name')->nullable()->after('name');
                $table->string('business_phone')->nullable()->after('owner_name');
                $table->string('base_city')->nullable()->after('business_phone');
                $table->unsignedInteger('service_radius_miles')->default(25)->after('base_city');
                $table->string('primary_specialty')->nullable()->after('service_radius_miles');

                // Brand Identity & Compliance Parameters
                $table->string('logo_path')->nullable()->after('primary_specialty');
                $table->unsignedInteger('years_experience')->nullable()->after('logo_path');
                $table->string('license_number')->nullable()->after('years_experience');
                $table->boolean('is_insured')->default(false)->after('license_number');
                $table->text('company_bio_short')->nullable()->after('is_insured');
                $table->text('company_bio_long')->nullable()->after('company_bio_short');

                // Advanced Structured JSON Content Arrays for Programmatic SEO Engine
                $table->json('service_tags')->nullable()->after('company_bio_long');
                $table->boolean('emergency_availability')->default(false)->after('service_tags');
                $table->json('media_gallery')->nullable()->after('emergency_availability');
                $table->json('social_links')->nullable()->after('media_gallery');
                $table->json('service_descriptions')->nullable()->after('social_links');

                // Operational SaaS Workflows
                $table->string('crew_structure')->default('solo')->after('service_descriptions');
                $table->string('invoice_preferences')->nullable()->after('crew_structure');
                $table->text('deposit_rules')->nullable()->after('invoice_preferences');
                $table->json('faq_blocks')->nullable()->after('deposit_rules');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $usersTableReal = (new \App\Models\User())->getTable();
        $prefix = str_contains($usersTableReal, '_') ? explode('_', $usersTableReal)[0] . '_' : '';
        $companiesTableReal = $prefix . 'companies';

        Schema::table($usersTableReal, function (Blueprint $table) {
            $table->dropColumn('current_onboarding_step');
        });

        Schema::table($companiesTableReal, function (Blueprint $table) {
            $table->dropColumn([
                'owner_name', 'business_phone', 'base_city', 'service_radius_miles', 'primary_specialty',
                'logo_path', 'years_experience', 'license_number', 'is_insured', 'company_bio_short', 'company_bio_long',
                'service_tags', 'emergency_availability', 'media_gallery', 'social_links', 'service_descriptions',
                'crew_structure', 'invoice_preferences', 'deposit_rules', 'faq_blocks'
            ]);
        });
    }
};
