<?php

namespace App\Console\Commands;

use App\Court;
use App\CourtJurisdiction;
use App\Services\Crawler\CourtsApi;
use DB;
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
     * @var CourtsApi
     */
    private $api;

    /**
     * SyncCourtsInformation constructor.
     *
     * @param CourtsApi $api
     */
    public function __construct(CourtsApi $api)
    {
        parent::__construct();

        $this->api = $api;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Court $court */
        $courts = Court::expired()->take(1000)->get()->each(function(Court $court) {
            $this->syncJurisdictions($court);
        });

        $this->info("Total courts to sync jurisdictions [".count($courts)."]");
    }

    /**
     * @param Court $court
     */
    protected function syncJurisdictions(Court $court)
    {
        try {
            $jurisdictions = $this->api->getCourtJurisdictionsFromSite($court->url);

            $this->info("Total $jurisdictions [".count($jurisdictions)."] for court [{$court->name}]");
            if (count($jurisdictions) > 0) {
                $court->jurisdictions()->createMany($jurisdictions);
            }
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
