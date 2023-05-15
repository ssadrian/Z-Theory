<?php

namespace App\Console;

use App\Models\Student;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $weeklyKudos = env('WEEKLY_KUDOS');
            $now = Carbon::now();

            DB::update("UPDATE ranking_student SET kudos = $weeklyKudos, updated_at = '$now'");
        })->weekly();

        // Prune expired tokens every 24hrs
        $schedule
            ->command('sanctum:prune-expired --hours=24')
            ->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
