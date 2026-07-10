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
        // Dynamic detection of tables ensuring compatibility with custom prefixes like 'sc_'
        Schema::table('companies', function (Blueprint $table) {
            $table->string('profile_theme')->default('light')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('profile_theme');
        });
    }
};
