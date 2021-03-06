<?php

namespace App\Services\Crawler;

use App\Court;
use App\Exceptions\CourtInformationNotFound;
use App\Exceptions\CourtJurisdictionsNotFound;
use App\Services\Crawler\Parsers\ArbitrationCourtListParser;
use App\Services\Crawler\Parsers\CourtBalloonParser;
use App\Services\Crawler\Parsers\CourtInformationParser;
use App\Services\Crawler\Parsers\CourtJurisdictionsParser;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Cache\CacheManager as Cache;
use Illuminate\Contracts\Foundation\Application;
use Psr\Log\LoggerInterface as Logger;

class CourtsApi
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var Application
     */
    private $app;

    /**
     * @param Application $app
     * @param HttpClient $client
     * @param Logger $logger
     * @param Cache $cache
     */
    public function __construct(Application $app, HttpClient $client, Logger $logger, Cache $cache)
    {
        $this->app = $app;
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
            $js = $this->query("https://sudrf.ru/index.php?id=300&act=ya_coords&type_suds={$type}");

            return $this->app->make(CourtBalloonParser::class)->parse($js);
        });
    }

    /**
     * @param string $type
     *
     * @return array
     */
    public function getArbitrationCourts(string $type): array
    {
        return $this->cache->remember('arbitration_courts:'.$type, now()->addDay(), function () use($type) {
            $html = $this->query("http://arbitr.ru/as/{$type}/");

            return $this->app->make(ArbitrationCourtListParser::class)->parse($html);
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
            $html = $this->query('https://sudrf.ru/index.php?id=300&act=ya_info&vnkod='.$code);
        } catch (\GuzzleHttp\Exception\RequestException $exception) {
            throw new CourtInformationNotFound($code, 0, $exception);
        }

        try {
            return $this->app->make(CourtInformationParser::class)->parse($html);
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

        $parser = $this->app->make(CourtJurisdictionsParser::class);

        $totalPages = $parser->parseTotalPages($html);

        $jurisdictions = $parser->parse($html);

        if ($totalPages > 1) {
            for ($page = 2; $totalPages > $page; $page++) {
                $html = $this->loadCourtJurisdictionsHTMLFromSite($court->url, $page);
                $jurisdictions = array_merge($jurisdictions, $parser->parse($html));
            }
        }

        return $jurisdictions;
    }

    /**
     * @param string $url
     * @param int $page
     *
     * @return string
     */
    protected function loadCourtJurisdictionsHTMLFromSite(string $url, int $page = 1)
    {
        $url = rtrim($url, '/');

        return $this->query("{$url}/modules.php?name=terr&pagenum={$page}");
    }

    /**
     * @param string $url
     *
     * @return string
     */
    protected function query(string $url): string
    {
        $res = $this->client->get($url, [
            'timeout' => 5,
            'headers' => [
                'User-Agent' => \Campo\UserAgent::random()
            ]
        ]);

        return toUtf8($res->getBody()->getContents());
    }
}