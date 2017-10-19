<?php

namespace App\Services\Crawler;

use App\Court;
use App\Exceptions\CourtInformationNotFound;
use App\Exceptions\CourtJurisdictionsNotFound;
use App\Services\Crawler\Parsers\CourtInformationParser;
use App\Services\Crawler\Parsers\CourtJurisdictionsParser;
use Illuminate\Cache\CacheManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;

class CourtsApi
{
    // Паттерн разбора ответа списка судов
    const PATTERN = "/balloons_user\[\'(?<code>[0-9A-Z]+)\'\]\.length\]\=\{type\:\'(?<type>([a-z]+))\'\,name\:\'(?<name>(.*))\'\,adress\:\'(?<address>(.*))\'\,coord\:\[(?<lat>[0-9]{2,4}\.[0-9]+)\,(?<lon>[0-9]{2,4}\.[0-9]+)\]/";

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Store
     */
    private $cache;

    /**
     * @param \GuzzleHttp\Client $client
     * @param LoggerInterface $logger
     * @param CacheManager $cache
     */
    public function __construct(\GuzzleHttp\Client $client, LoggerInterface $logger, CacheManager $cache)
    {
        $this->client = $client;
        $this->logger = $logger;
        $this->cache = $cache;
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
        return $this->cache->remember('courts:'.$type, now()->addDay(), function () use($type) {
            $html = $this->getUrlConentent('https://sudrf.ru/index.php?id=300&act=ya_coords&type_suds='.$type, ['timeout' => 3]);

            $matches = [];

            preg_match_all(static::PATTERN, $html, $matches);

            $data = [];

            foreach ($matches as $group => $groupRows) {
                if (is_numeric($group)) {
                    continue;
                }

                foreach ($groupRows as $i => $value) {
                    $data[$i][$group] = $value;
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
     * @throws CourtInformationNotFound
     */
    public function getCourt(string $code): array
    {
        try {
            $html = $this->getUrlConentent('https://sudrf.ru/index.php?id=300&act=ya_info&vnkod='.$code, ['timeout' => 3]);
        } catch (\GuzzleHttp\Exception\RequestException $exception) {
            throw new CourtInformationNotFound($code, 0, $exception);
        }

        try {
            $parser = new CourtInformationParser();
            return $parser->parse($html);
        } catch (\Exception $exception) {
            throw new CourtInformationNotFound($code, 0, $exception);
        }
    }

    /**
     * @param Court $court
     *
     * @return array
     * @throws CourtJurisdictionsNotFound
     */
    public function getCourtJurisdictionsFromSite(Court $court): array
    {
        try {
            $html = $this->loadCourtJurisdictionsHTMLFromSite($court->url);
        } catch (\GuzzleHttp\Exception\RequestException $exception) {
            throw new CourtJurisdictionsNotFound($court->code, 0, $exception);
        }

        if (str_contains($html, 'Модуль с таким именем не существует!')) {
            throw new CourtJurisdictionsNotFound($court->code);
        }

        if (str_contains($html, 'В базе данных нет информации о территориальной подсудности.')) {
            throw new CourtJurisdictionsNotFound($court->code);
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
        return $this->getUrlConentent("http://promyshleny.stv.sudrf.ru/modules.php?name=terr&pagenum={$page}");
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

    /**
     * @param string $url
     *
     * @return string
     */
    protected function getUrlConentent(string $url): string
    {
        $this->logger->debug('CourtsApi выполнение запроса', [
            'url' => $url
        ]);

        $res = $this->client->get($url, ['timeout' => 5]);

        return $this->convertToUtf8($res->getBody()->getContents());
    }
}