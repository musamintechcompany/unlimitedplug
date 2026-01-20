<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();
            $table->enum('type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('value', 10, 2);
            $table->decimal('min_purchase', 10, 2)->nullable();
            $table->integer('max_uses')->nullable();
            $table->integer('max_uses_per_user')->default(1);
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->enum('applicable_to', ['all', 'products', 'categories'])->default('all');
            $table->json('applicable_ids')->nullable();
            $table->uuid('created_by')->nullable();
            $table->string('created_by_type')->nullable(); // 'admin' or 'user'
            $table->boolean('is_active')->default(true);
            $table->integer('used_count')->default(0);
            $table->timestamps();
        });

        Schema::create('coupon_usage', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('coupon_id');
            $table->uuid('user_id');
            $table->uuid('order_id');
            $table->decimal('discount_amount', 10, 2);
            $table->decimal('order_total', 10, 2);
            $table->timestamps();

            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usage');
        Schema::dropIfExists('coupons');
    }
};
