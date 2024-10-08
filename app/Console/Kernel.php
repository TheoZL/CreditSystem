<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Set Timezone to Costa Rica
     *
     * @return \DateTimeZone|string|null
     */
    protected function scheduleTimezone(): \DateTimeZone|string|null
    {
        return 'America/Costa_Rica';
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if(app()->environment('production')){

            $schedule->command('credits:verification')->dailyAt('1:00');

        }else{

            $schedule->command('credits:verification')->everyMinute();

        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
