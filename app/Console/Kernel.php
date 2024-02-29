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
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // phpcs:disable PSR12.Operators.OperatorSpacing.NoSpaceAfter, PSR12.Operators.OperatorSpacing.NoSpaceBefore
        $this->load(__DIR__.'/Commands');
        // phpcs:enable

        include base_path('routes/console.php');
    }
}
