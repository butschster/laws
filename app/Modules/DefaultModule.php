<?php

namespace App\Modules;

use App\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

class DefaultModule extends Module
{

    /**
     * @param Schedule $schedule
     */
    public function schedule(Schedule $schedule)
    {

    }

    /**
     * @param ConsoleKernel $console
     */
    public function console(ConsoleKernel $console)
    {

    }
}
