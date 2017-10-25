<?php

namespace App\Services\Crawler\Parsers;

use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;

class ArbitrationCourtListParser extends Parser
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

        $crawler = $crawler->filterXPath($converter->toXPath('a.zag21'));

        return $crawler->each(function (Crawler $node, $i) {
            return [
                'name' => trim($node->text()),
                'url' => 'http://arbitr.ru/'.ltrim($node->attr('href'), '/')
            ];
        });
    }
}