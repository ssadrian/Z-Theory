<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students_rankings', function (Blueprint $table) {
            $table->foreignId('students_id')->constrained();
            $table->uuid('rankings_code');
            $table->foreign('rankings_code')->references('code')->on('rankings');
            $table->unsignedInteger('rank');
            $table->integer('points');
            $table->timestamps();

            $table->primary(['students_id', 'rankings_code']);
            $table->unique(['rankings_code', 'rank']);
            $table->unique(['students_id', 'rankings_code', 'rank']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students_rankings');
    }
};
