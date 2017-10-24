<?php

namespace App\Services\CBR;

use App\Exceptions\RefinancingRateResponse;
use Symfony\Component\DomCrawler\Crawler;

class RefinancingRate
{

    /**
     * @var \Zend\Soap\Client
     */
    private $client;

    /**
     * @param \Zend\Soap\Client $client
     */
    public function __construct(\Zend\Soap\Client $client)
    {
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

            $crawler = new Crawler();
            $crawler->addHtmlContent($xml);

            return (float) $crawler->filter('RegData > stavka_ref')->first()->text();
        } catch (\Exception $e) {
            throw new RefinancingRateResponse('Ошибка получениея ставки рефинансирования');
        }
    }
}