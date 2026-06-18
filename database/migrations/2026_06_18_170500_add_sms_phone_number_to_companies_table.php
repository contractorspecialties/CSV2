<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Dropped the positional constraint to ensure a safe structural execution
        Schema::table('sc_companies', function (Blueprint $blueprint) {
            $blueprint->string('sms_phone_number', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sc_companies', function (Blueprint $blueprint) {
            $blueprint->dropColumn('sms_phone_number');
        });
    }
};
