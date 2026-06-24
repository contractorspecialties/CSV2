<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to inject onboarding tracking attributes natively.
     */
    public function up(): void
    {
        $tableName = (new \App\Models\User())->getTable();

        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            if (!Schema::hasColumn($tableName, 'onboarding_completed_at')) {
                $table->timestamp('onboarding_completed_at')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = (new \App\Models\User())->getTable();

        Schema::table($tableName, function (Blueprint $table) {
            $table->dropColumn('onboarding_completed_at');
        });
    }
};
