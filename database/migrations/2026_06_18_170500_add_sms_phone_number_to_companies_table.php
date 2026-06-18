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
        // Explicitly targeting the customized sc_ prefix ledger table
        Schema::table('sc_companies', function (Blueprint $blueprint) {
            $blueprint->string('sms_phone_number', 30)->nullable()->after('email');
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
