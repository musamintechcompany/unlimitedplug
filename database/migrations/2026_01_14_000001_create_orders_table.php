<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuidMorphs('orderable'); // User, Guest, Organization, etc.
            $table->string('order_number')->unique();
            $table->uuid('payment_id')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->string('payment_method'); // paystack, nowpayments
            $table->string('payment_status')->default('pending'); // pending, completed, failed, refunded
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('set null');
            $table->index('order_number');
            $table->index('transaction_reference');
            $table->index(['orderable_type', 'orderable_id', 'status']);
            $table->index('payment_status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
