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
        Schema::create('assignment_student', function (Blueprint $table) {
            $table->foreignId('student_id')->constrained();
            $table->foreignId('assignment_id')->constrained();

            $table->string('status')->nullable();
            $table->foreign('status')
                ->references('status')->on('assignment_statuses')
                ->nullOnDelete();

            $table->unsignedFloat('mark', 3)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_student');
    }
};
