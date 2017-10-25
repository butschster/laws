<?php

namespace App\Services\Crawler\Parsers;

use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;

class CourtJurisdictionsParser extends Parser
{

    /**
     * @param string $html
     *
     * @return array
     */
    public function parse(string $html): array
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent(toUtf8($html));
        $converter = new CssSelectorConverter();

        $crawler = $crawler->filterXPath($converter->toXPath('table#tblTerrList > tbody > tr'));

        return $crawler->each(function (Crawler $node, $i) {
            $city = $node->children()->first()->text();
            $address = $node->children()->last()->text();

            return [
                'city' => trim($city),
                'address' => trim($address, chr(0xC2).chr(0xA0)), // Удаление &nbsp;
            ];
        });
    }

    /**
     * Получение кол-ва страниц
     *
     * @param string $html
     *
     * @return int
     */
    public function parseTotalPages(string $html): int
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($html);
        $converter = new CssSelectorConverter();

        $pagination = $crawler->filterXPath($converter->toXPath('div.pages_button button'));

        if (count($pagination) > 0) {
            return (int) $pagination->last()->text();
        }

        return 1;
    }
}