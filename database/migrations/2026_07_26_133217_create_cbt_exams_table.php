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
        Schema::create('cbt_exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->string('session'); // 'Morning (08:00)', 'Noon (11:00)', 'Afternoon (14:00)'
            $table->string('room');    // 'Lab A-01', 'Lab A-02', 'Hall C', 'Library W'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbt_exams');
    }
};
