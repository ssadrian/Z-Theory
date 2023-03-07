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
        Schema::create('ranking_join_queue', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();

            $table->foreignUuid('ranking_code');
            $table->foreign('ranking_code')->references('code')->on('rankings');

            $table->foreignId('join_status_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranking_join_queue');
    }
};
