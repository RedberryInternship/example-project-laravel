<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule( Schedule $schedule )
    {
        $schedule -> command( 'command:check-orders-on-penalty'      ) -> everyMinute();
        $schedule -> command( 'command:synchronize-real-chargers'    ) -> everyMinute();
        $schedule -> command( 'command:pre-charged-orders-checker'   ) -> everyMinute();
        $schedule -> command( 'command:not-confirmed-orders-checker' ) -> everyMinute();
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
