<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('rankings', function (Blueprint $table) {
            $table->id();
            $table->uuid('code');
            $table->foreignId('student_id')->constrained();
            $table->unsignedInteger('rank');
            $table->timestamps();

            $table->unique(['code', 'rank']);
            $table->unique(['code', 'student_id', 'rank']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('rankings');
    }
};
