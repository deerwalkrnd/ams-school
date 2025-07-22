<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Initialize daily attendance status at 06:00 am
        $schedule->command('attendance:initialize-daily')
                 ->dailyAt('13:51')
                 ->withoutOverlapping();

        $schedule->command('attendance:check-reminders')
                 ->dailyAt('13:53')
                 ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
