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
        Schema::create('cbt_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10);  // 'MA', 'SC', 'EN'
            $table->string('name');
            $table->string('topic');
            $table->unsignedInteger('items_count')->default(0);
            $table->string('difficulty'); // 'Hard', 'Medium', 'Easy'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_subjects');
    }
};
