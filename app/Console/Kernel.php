<?php

namespace App\Console;

use App\Console\Commands\ImportFiasDatabase;
use App\Console\Commands\IndexFiasDatabase;
use App\Console\Commands\SyncCourtJurisdictions;
use App\Console\Commands\SyncCourtsInformation;
use App\Console\Commands\UpdateRefinancingRate;
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
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('update:refinancing-rate')->daily();
        $schedule->command('sync:courts')->monthly();
        $schedule->command('sync:court:jurisdictions')->daily();
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
