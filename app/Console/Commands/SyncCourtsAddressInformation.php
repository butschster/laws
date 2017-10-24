<?php

namespace App\Console\Commands;

use App\Court;
use App\Jobs\GetInformationAboutCourt;
use App\Jobs\GetInformationAboutCourtAddress;
use App\Services\Crawler\CourtsApi;
use App\Services\Dadata\ClientInterface;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

class SyncCourtsAddressInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:courts:address';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление подробной информации по адресам судов';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $courts = Court::select('courts.*')->leftJoin('kladr_court', function ($join) {
            $join->on('kladr_court.court_id', '=', 'courts.id');
        })->whereNull('kladr_court.court_id')->get();

        $bar = $this->output->createProgressBar($courts->count());
        foreach ($courts as $court) {
            dispatch(new GetInformationAboutCourtAddress($court));

            $bar->advance();
        }

        $bar->finish();
    }
}
