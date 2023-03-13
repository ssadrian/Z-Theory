<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('join_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('type')->unique();
        });

        DB::table('join_statuses')->insert([
            ['type' => 'Accepted'],
            ['type' => 'Pending'],
            ['type' => 'Ignored'],
            ['type' => 'Declined']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('join_statuses');
    }
};
