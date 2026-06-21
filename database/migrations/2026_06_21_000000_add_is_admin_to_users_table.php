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
        $userTable = (new \App\Models\User())->getTable();

        Schema::table($userTable, function (Blueprint $table) {
            // Adds the admin tracking bit cleanly defaulting to standard customer status
            $table->boolean('is_admin')->default(false)->after('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $userTable = (new \App\Models\User())->getTable();

        Schema::table($userTable, function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
};
