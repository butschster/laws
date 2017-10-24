<?php

namespace App\Jobs;

use App\Court;
use App\Exceptions\CourtInformationNotFound;
use App\Services\Dadata\ClientInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Psr\Log\LoggerInterface;

class GetInformationAboutCourtAddress implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @var Court
     */
    public $court;

    /**
     * GetInformationAboutCourtAddress constructor.
     *
     * @param Court $court
     */
    public function __construct(Court $court)
    {
        $this->court = $court;
    }

    /**
     * @param ClientInterface $client
     * @param LoggerInterface $logger
     */
    public function handle(ClientInterface $client, LoggerInterface $logger)
    {
        try {
            $data = $client->suggest($this->court->address);
        } catch (CourtInformationNotFound $e) {
            $logger->error($e->getMessage());

            $this->fail($e);

            return;
        }

        $data = $data->first();

        if (empty($data)) {
            $logger->error("Информация по адресу суда [{$this->court->code}] не найдена.");

            return;
        }

        $data = array_only(array_get($data, 'data', []), [
            'region_fias_id', 'region_kladr_id',
            'area_fias_id', 'area_kladr_id',
            'city_fias_id', 'city_kladr_id',
            'settlement_fias_id', 'settlement_kladr_id',
            'street_fias_id', 'street_kladr_id',
            'house_fias_id', 'house_kladr_id',
            'fias_id', 'kladr_id',
        ]);

        $data['court_id'] = $this->court->id;

        if (\DB::table('kladr_court')->where('court_id', $this->court->id)->first()) {
            \DB::table('kladr_court')->where('court_id', $this->court->id)->update($data);
        } else {
            \DB::table('kladr_court')->insert($data);
        }
    }
}
