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
        $this->prefix = !empty($configuredPrefix) ? $configuredPrefix : 'sc_';
    }

    public function up(): void
    {
        Schema::create($this->prefix . 'users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();

            // Passwordless Sign-In Token Engine
            $table->string('login_token', 64)->nullable()->unique();
            $table->timestamp('token_expires_at')->nullable();

            // Contractor Referral Engine
            $table->string('referral_code')->unique()->nullable();
            $table->unsignedBigInteger('referred_by_user_id')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')
                  ->references('id')
                  ->on($this->prefix . 'companies')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists($this->prefix . 'users');
    }
};
