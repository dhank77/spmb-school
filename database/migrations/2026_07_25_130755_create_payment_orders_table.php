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
        Schema::create('payment_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('merchant_order_id')->unique();
            $table->string('reference')->nullable(); // Duitku payment reference / URL
            $table->string('payment_method')->nullable();
            $table->unsignedInteger('amount');
            $table->string('status')->default('pending'); // pending, success, failed
            $table->string('result_code')->nullable();    // 00 = success, 01 = failed
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('merchant_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_orders');
    }
};
