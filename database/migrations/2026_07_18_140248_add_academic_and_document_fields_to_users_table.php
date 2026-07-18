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
        Schema::table('users', function (Blueprint $table) {
            $table->string('previous_school')->nullable();
            $table->integer('graduation_year')->nullable();
            $table->string('document_identity')->nullable();
            $table->string('document_diploma')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'previous_school',
                'graduation_year',
                'document_identity',
                'document_diploma',
            ]);
        });
    }
};
