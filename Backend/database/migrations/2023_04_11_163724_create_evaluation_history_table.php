<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluation_history', function (Blueprint $table) {
            $table->id();

            $table->foreignId('skill_id')->constrained();

            $table->unsignedBigInteger('evaluator');
            $table->foreign('evaluator')
                ->on('students')
                ->references('id')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('subject');
            $table->foreign('subject')
                ->on('students')
                ->references('id')
                ->cascadeOnDelete();

            $table->unsignedInteger('kudos');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_history');
    }
};
