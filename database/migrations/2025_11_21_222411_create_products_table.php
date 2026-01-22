<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
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
            $table->json('highlights')->nullable();
            $table->json('features')->nullable();
            $table->json('requirements')->nullable();
            $table->json('specifications')->nullable();
            $table->json('includes')->nullable();
            $table->string('version')->nullable();
            $table->text('changelog')->nullable();
            $table->string('license_type')->nullable();
            $table->string('sku')->nullable();
            $table->integer('stock_quantity')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->json('dimensions')->nullable();
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->boolean('requires_shipping')->default(false);
            $table->boolean('track_inventory')->default(false);
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->uuid('admin_id')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('badge')->nullable(); // NEW, HOT, BESTSELLER, etc.
            $table->integer('downloads')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');
            $table->index(['type', 'subcategory']);
            $table->index('status');
            $table->index('category_id');
            $table->index('user_id');
            $table->index('is_featured');
            $table->index('is_active');
            $table->index('sort_order');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};