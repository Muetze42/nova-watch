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
        $schedule->command('app:nova:update-releases')
            ->everyFiveMinutes();

        $schedule->command('queue:work', [
            '--timeout' => 0,
            '--stop-when-empty',
        ])->everyMinute()->withoutOverlapping()->when(function () {
            return $this->app['config']->get('queue.default') == 'database';
        });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
