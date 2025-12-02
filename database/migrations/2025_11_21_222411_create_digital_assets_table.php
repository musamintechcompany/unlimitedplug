<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('digital_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->string('name');
            $table->text('description');
            $table->string('type'); // website, template, plugin, service, digital
            $table->uuid('category_id')->nullable();
            $table->string('subcategory')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('list_price', 10, 2)->nullable();
            $table->string('banner')->nullable();
            $table->json('media')->nullable();
            $table->json('file')->nullable();
            $table->string('demo_url')->nullable();
            $table->json('tags')->nullable();
            $table->json('features')->nullable();
            $table->text('requirements')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->uuid('admin_id')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->string('badge')->nullable(); // NEW, HOT, BESTSELLER, etc.
            $table->integer('downloads')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->index(['type', 'subcategory']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('digital_assets');
    }
};