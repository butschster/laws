<?php

namespace App\Console\Commands;

use App\Court;
use App\CourtRegion;
use App\Exceptions\CourtInformationNotFound;
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

            $courts = $this->api->getCourts($type);
            $totalCourts = count($courts);

            $this->info("Total courts [{$totalCourts}] with type [{$type}]");

            $bar = $this->output->createProgressBar($totalCourts);

            foreach ($courts as $court) {
                try {
                    $data = $this->api->getCourt($court['code']);
                } catch (CourtInformationNotFound $e) {
                    $this->logger->error($e->getMessage());
                    continue;
                }

                $data = array_merge($data, $court);
                $data['type'] = $type;

                $region = ['name' => $data['region']];
                $region = CourtRegion::updateOrCreate($region, $region);
                $data['region_id'] = $region->id;

                Court::updateOrCreate(['code' => $data['code']],
                    array_except($data, ['okrug', 'region'])
                );

                $bar->advance();
            }

            $bar->finish();
            $this->output->writeln('');
        }
    }

    /**
     * @param string $code
     *
     * @return array
     */
    protected function getCourt(string $code)
    {
        return ;
    }
}
