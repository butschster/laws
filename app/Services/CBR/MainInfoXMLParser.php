<?php

namespace App\Services\CBR;

use App\Contracts\ParserInterface;
use Symfony\Component\DomCrawler\Crawler;

class MainInfoXMLParser implements ParserInterface
{

    /**
     * @param string $xml
     *
     * @return float
     */
    public function parse(string $xml): float
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($xml);

        return (float) $crawler->filter('RegData > stavka_ref')->first()->text();
    }
}