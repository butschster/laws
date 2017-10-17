<?php

namespace App\Console\Commands;

use App\Court;
use App\Services\Crawler\CourtsApi;
use Illuminate\Console\Command;

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
        foreach ([CourtsApi::TYPE_COMMON, CourtsApi::TYPE_MIR] as $type) {

            $courts = $this->api->getCourts($type);
            $this->info("Total courts ".count($courts)." with type [{$type}]");
            foreach ($courts as $court) {
                $data = $this->getCourt($court['code']);
                $data = array_merge($data, $court);
                $data['type'] = $type;

                /** @var Court $court */
                $court = Court::updateOrCreate(['code' => $data['code']], array_except($data, 'okrug'));

                try {
                    $jurisdictions = $this->api->getCourtJurisdictionsFromSite($data['url']);

                    if (count($jurisdictions) > 0) {
                        $court->jurisdictions()->createMany($jurisdictions);
                    }
                } catch (\Exception $exception) {
                    $this->error($exception->getMessage());
                }
            }
        }
    }

    /**
     * @param string $code
     *
     * @return array
     */
    protected function getCourt(string $code)
    {
        try {
            return $this->api->getCourt($code);
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            $this->info("Retry request to [{$code}]");
            return $this->getCourt($code);
        }
    }
}
