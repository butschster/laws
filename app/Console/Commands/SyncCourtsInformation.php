<?php

namespace App\Console\Commands;

use App\Court;
use App\CourtRegion;
use App\Exceptions\CourtInformationNotFound;
use App\Jobs\GetInformationAboutCourt;
use App\Services\Crawler\CourtsApi;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

class SyncCourtsInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:courts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновление информации о судах РФ';

    /**
     * @var CourtsApi
     */
    private $api;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * SyncCourtsInformation constructor.
     *
     * @param CourtsApi $api
     * @param LoggerInterface $logger
     */
    public function __construct(CourtsApi $api, LoggerInterface $logger)
    {
        parent::__construct();

        $this->api = $api;
        $this->logger = $logger;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $types = array_keys(Court::types());

        foreach ($types as $type) {
            $this->syncCourts($type);
        }
    }

    /**
     * @param string $type
     *
     * @return int
     */
    protected function syncCourts(string $type)
    {
        $courts = $this->api->getCourts($type);
        $totalCourts = count($courts);

        $this->info("Всего судов [{$totalCourts}] с типом [{$type}]");

        $bar = $this->output->createProgressBar($totalCourts);

        foreach ($courts as $court) {
            $court['type'] = $type;

            dispatch(new GetInformationAboutCourt($court));

            $bar->advance();
        }

        $bar->finish();
        $this->output->writeln('');
    }
}
