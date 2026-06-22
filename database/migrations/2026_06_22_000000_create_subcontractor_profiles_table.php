<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the structural database updates for the local SEO directory engine.
     */
    public function up(): void
    {
        Schema::create('subcontractor_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->index();

            // Core Identity Properties
            $table->string('business_name');
            $table->string('slug')->unique();
            $table->string('primary_specialty')->index();
            $table->text('service_description')->nullable();

            // Geocoding Coordinates Matrix Nodes (Programmatic Local SEO Infrastructure)
            $table->string('street_address')->nullable();
            $table->string('city')->index();
            $table->string('state', 50)->index();
            $table->string('zip_code', 20)->index();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Credential Verification Metrics
            $table->boolean('is_licensed')->default(false);
            $table->boolean('is_insured')->default(false);
            $table->string('license_number')->nullable();

            // Programmatic JSON-LD Schema Engine Storage
            $table->text('json_ld_schema')->nullable();

            // CRITICAL SYSTEM SCOPE SWITCH TOGGLES
            // Keeps the sub-directory completely invisible to public web spiders until SEO optimization is complete
            $table->boolean('is_public')->default(false)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcontractor_profiles');
    }
};
