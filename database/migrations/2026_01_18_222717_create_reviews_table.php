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
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuidMorphs('reviewer'); // User or Admin who wrote the review
            $table->uuidMorphs('reviewable'); // Product, Service, Seller, etc.
            $table->json('review_data'); // Stores rating, comment, images, and any future fields
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            $table->unique(['reviewer_type', 'reviewer_id', 'reviewable_type', 'reviewable_id'], 'unique_review');
            $table->index('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
