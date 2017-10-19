<?php

namespace App\Jobs;

use App\Court;
use App\CourtRegion;
use App\Exceptions\CourtInformationNotFound;
use App\Services\Crawler\CourtsApi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Psr\Log\LoggerInterface;

class GetInformationAboutCourt implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @var array
     */
    public $court;

    /**
     * Create a new job instance.
     *
     * @param array $court
     */
    public function __construct(array $court)
    {
        $this->court = $court;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CourtsApi $api, LoggerInterface $logger)
    {
        try {
            $data = $api->getCourt($this->court['code']);
        } catch (CourtInformationNotFound $e) {
            $logger->error($e->getMessage());

            return;
        }

        $data = array_merge($this->court, $data);

        $region = ['name' => $data['region']];
        $region = CourtRegion::updateOrCreate($region, $region);
        $data['region_id'] = $region->id;

        Court::updateOrCreate(['code' => $data['code'], 'type' => $data['type']],
            array_except($data, ['okrug', 'region'])
        );
    }
}
