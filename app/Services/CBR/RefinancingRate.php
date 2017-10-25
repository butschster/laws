<?php

namespace App\Services\CBR;

use App\Exceptions\RefinancingRateResponse;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\DomCrawler\Crawler;

class RefinancingRate
{

    /**
     * @var \Zend\Soap\Client
     */
    private $client;

    /**
     * @var Application
     */
    private $app;

    /**
     * @param Application $app
     * @param \Zend\Soap\Client $client
     */
    public function __construct(Application $app, \Zend\Soap\Client $client)
    {
        $this->app = $app;
        $this->client = $client;
        $client->setWSDL('http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL');
    }

    /**
     * @return float
     * @throws RefinancingRateResponse
     */
    public function get(): float
    {
        try {
            $xml = $this->client->MainInfoXML()->MainInfoXMLResult->any;

            return $this->app->make(MainInfoXMLParser::class)->parse($xml);
        } catch (\Exception $e) {
            throw new RefinancingRateResponse('Ошибка получениея ставки рефинансирования');
        }
    }
}