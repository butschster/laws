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
        $crawler->addHtmlContent($this->convertToUtf8($html));
        $converter = new CssSelectorConverter();

        $crawler = $crawler->filterXPath($converter->toXPath('table#tblTerrList > tbody > tr'));

        return $crawler->each(function (Crawler $node, $i) {
            return [
                'city' => trim($node->children()->first()->text()),
                'address' => trim($node->children()->last()->text())
            ];
        });
    }
}