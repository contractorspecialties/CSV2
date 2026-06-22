<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Dynamically discover the active database prefix by checking existing infrastructure.
     */
    private function getPrefixedTable(): string
    {
        $prefix = '';
        foreach (['sc_', 'cs_', 'app_', 'wp_'] as $possiblePrefix) {
            if (Schema::hasTable($possiblePrefix . 'companies')) {
                $prefix = $possiblePrefix;
                break;
            }
        }
        return $prefix . 'companies';
    }

    /**
     * Run the migrations to append image tracking columns.
     */
    public function up(): void
    {
        $tableName = $this->getPrefixedTable();

        Schema::table($tableName, function (Blueprint $table) use ($tableName) {
            if (!Schema::hasColumn($tableName, 'logo_path')) {
                $table->string('logo_path')->nullable()->after('trade');
            }
            if (!Schema::hasColumn($tableName, 'portfolio_image_path')) {
                $table->string('portfolio_image_path')->nullable()->after('logo_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableName = $this->getPrefixedTable();

        Schema::table($tableName, function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'portfolio_image_path']);
        });
    }
};
