<?php

namespace App\Console\Commands;

use App\Court;
use App\Jobs\GetInformationAboutCourtJurisdictions;
use Illuminate\Console\Command;

class SyncCourtJurisdictions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:court:jurisdictions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление информации о подсудности судов';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $totalCourts = Court::count();
        $batchSize = 500;
        $skip = 0;

        $this->info("Всего судов для синхронизации [".$totalCourts."]");

        while ($totalCourts > 0) {
            /** @var Court $court */
            Court::expired()->take($batchSize)->skip($batchSize)->get()->each(function(Court $court) {
                dispatch(new GetInformationAboutCourtJurisdictions($court));
            });

            $skip += $batchSize;
            $totalCourts -= $batchSize;

            $this->info("Судов осталось синхронизировать [".$totalCourts."]");
        }
    }
}
