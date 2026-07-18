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
        Schema::create('admission_waves', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('period');
            $table->unsignedInteger('registration_cost');
            $table->unsignedInteger('total_quota');
            $table->unsignedInteger('remaining_quota');
            $table->enum('status', ['closed', 'active', 'upcoming'])->default('upcoming');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_waves');
    }
};
