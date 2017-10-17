<?php

namespace App\Services\Crawler;

use App\Services\Crawler\Parsers\CourtInformationParser;
use App\Services\Crawler\Parsers\CourtJurisdictionsParser;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;

class CourtsApi
{
    const TYPE_COMMON = 'fs'; // суды РФ общей юрисдикции
    const TYPE_MIR = 'mir'; // мировые суда РФ

    // Паттерн разбора ответа списка судов
    const PATTERN = "/balloons_user\[\'(?<code>[0-9A-Z]+)\'\]\.length\]\=\{type\:\'(?<type>([a-z]+))\'\,name\:\'(?<name>(.*))\'\,adress\:\'(?<address>(.*))\'\,coord\:\[(?<lat>[0-9]{2,4}\.[0-9]+)\,(?<lon>[0-9]{2,4}\.[0-9]+)\]/";

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
    }

    /**
     * Получение списка судов
     *
     * @param string $type
     *
     * @return array
     */
    public function getCourts(string $type): array
    {
        return cache()->rememberForever('courts', function () use($type) {
            $res = $this->client->request('GET', 'https://sudrf.ru/index.php?id=300&act=ya_coords&type_suds='.$type);

            $response = $res->getBody()->getContents();

            $matches = [];

            preg_match_all(static::PATTERN, $response, $matches);

            $data = [];

            foreach ($matches as $group => $groupRows) {
                if (is_numeric($group)) {
                    continue;
                }

                foreach ($groupRows as $i => $value) {
                    $data[$i][$group] = $this->convertToUtf8($value);
                }
            }

            return $data;
        });
    }

    /**
     * Получение информации о суде по классификационному коду
     *
     * @param string $code
     *
     * @return array
     */
    public function getCourt(string $code): array
    {
        $res = $this->client->request('GET', 'https://sudrf.ru/index.php?id=300&act=ya_info&vnkod='.$code);

        $parser = new CourtInformationParser();

        return $parser->parse($res->getBody()->getContents());
    }

    /**
     * @param string $url
     *
     * @return array
     */
    public function getCourtJurisdictionsFromSite(string $url): array
    {
        $html = $this->loadCourtJurisdictionsHTMLFromSite($url);

        if (str_contains($html, 'Модуль с таким именем не существует!')) {
            return [];
        }

        if (str_contains($html, 'В базе данных нет информации о территориальной подсудности.')) {
            return [];
        }

        $totalPages = $this->getTotalOfPages($html);

        $parser = new CourtJurisdictionsParser();

        $jurisdictions = $parser->parse($html);

        if ($totalPages > 1) {
            for ($page = 2; $totalPages > $page; $page++) {
                $html = $this->loadCourtJurisdictionsHTMLFromSite($url, $page);
                $jurisdictions = array_merge($jurisdictions, $parser->parse($html));
            }
        }

        return $jurisdictions;
    }

    /**
     * Конвертация строки в UTF-8
     *
     * @param string $text
     *
     * @return string
     */
    protected function convertToUtf8(string $text): string
    {
        return iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $text);
    }

    /**
     * @param string $url
     * @param int $page
     *
     * @return string
     */
    protected function loadCourtJurisdictionsHTMLFromSite(string $url, int $page = 1)
    {
        $res = $this->client->request('GET', "{$url}/modules.php?name=terr&pagenum={$page}");

        return $this->convertToUtf8($res->getBody()->getContents());
    }

    /**
     * @param string $html
     *
     * @return int
     */
    protected function getTotalOfPages(string $html): int
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