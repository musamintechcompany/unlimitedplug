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
        Schema::create('admins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('login_verification_code', 6)->nullable();
            $table->timestamp('login_verification_code_expires_at')->nullable();
            $table->string('password');
            $table->string('password_reset_code', 6)->nullable();
            $table->timestamp('password_reset_code_expires_at')->nullable();
            $table->enum('status', ['pending', 'active', 'suspended', 'blocked'])->default('active');
            $table->string('profile_photo_path', 2048)->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->enum('theme', ['light', 'dark'])->default('light');
            $table->enum('two_factor_method', ['email', 'authenticator'])->default('email');
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->timestamp('welcome_email_sent_at')->nullable();
            $table->json('created_by')->nullable();
            $table->json('deleted_by')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
