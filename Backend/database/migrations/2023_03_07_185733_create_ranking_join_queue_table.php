<?php

use App\Enums\JoinStatus;
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
            $table->foreignId('student_id')->onDelete('cascade')->constrained();
            $table->foreignId('ranking_id')->onDelete('cascade')->constrained();
            $table->foreignId('join_status_id')
                ->default(JoinStatus::Pending->value)
                ->onDelete('cascade')
                ->constrained();

            $table->primary(['student_id', 'ranking_id']);
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
