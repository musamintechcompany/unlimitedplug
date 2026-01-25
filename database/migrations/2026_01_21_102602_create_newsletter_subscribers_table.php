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
        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->nullableUuidMorphs('subscriber'); // user_id or null for guests
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->string('confirmation_token')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamp('subscribed_at')->useCurrent();
            $table->timestamps();
            
            $table->index('email');
            $table->index('is_active');
            $table->index('confirmation_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');
    }
};
