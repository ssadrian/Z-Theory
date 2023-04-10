<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assignment_statuses', function (Blueprint $table) {
            $table->string('status')->primary();
            $table->timestamps();
        });

        DB::table('assignment_statuses')->insert([
            ['status' => 'Sent'],
            ['status' => 'Pending'],
            ['status' => 'Delayed'],
            ['status' => 'Missing']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_statuses');
    }
};
