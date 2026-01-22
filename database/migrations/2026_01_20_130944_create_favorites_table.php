<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->uuidMorphs('favoritable'); // Product, Service, Seller, etc.
            $table->string('session_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes for performance
            $table->index('user_id');
            $table->index('session_id');
            
            $table->unique(['user_id', 'favoritable_type', 'favoritable_id'], 'unique_user_favorite');
            $table->unique(['session_id', 'favoritable_type', 'favoritable_id'], 'unique_session_favorite');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
