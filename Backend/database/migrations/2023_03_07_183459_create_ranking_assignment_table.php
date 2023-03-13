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
        Schema::create('ranking_assignment', function (Blueprint $table) {
            $table->foreignId('ranking_id')->constrained();
            $table->foreignId('assignment_id')->constrained();

            $table->primary(['ranking_id', 'assignment_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranking_assignment');
    }
};
