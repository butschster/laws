<?php

namespace App\Jobs;

use App\Court;
use App\Exceptions\CourtJurisdictionsNotFound;
use App\Services\Crawler\CourtsApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Psr\Log\LoggerInterface;

class GetInformationAboutCourtJurisdictions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Court
     */
    public $court;

    /**
     * Create a new job instance.
     *
     * @param Court $court
     */
    public function __construct(Court $court)
    {
        $this->court = $court;
    }

    /**
     * Execute the job.
     *
     * @param CourtsApi $api
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function handle(CourtsApi $api, LoggerInterface $logger)
    {
        $logger->debug('Получение списка подсудностей.', [
            'code' => $this->court->code,
            'url' => $this->court->url,
        ]);

        try {
            $jurisdictions = $api->getCourtJurisdictionsFromSite($this->court);
        } catch (CourtJurisdictionsNotFound $exception) {
            $logger->error($exception->getMessage());

            $this->fail($exception);
            return;
        }

        $logger->debug('Всего найдено подсудностей', [
            'code' => $this->court->code,
            'count' => count($jurisdictions)
        ]);

        if (count($jurisdictions) > 0) {
            $this->court->jurisdictions()->createMany($jurisdictions);
        }

        $this->court->synced();
    }
}
