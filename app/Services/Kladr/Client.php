<?php

namespace App\Services\Kladr;

use App\Services\Kladr\Objects\Street;
use Illuminate\Support\Collection;

class Client
{
    const DOMAIN = 'http://kladr-api.ru/';

    /**
     * @var string
     */
    private $token = null;

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
     * @param string $token
     */
    public function token(string $token)
    {
        $this->token = $token;
    }

    /**
     * @param string $address
     * @param int $limit
     *
     * @return Collection
     */
    public function findByAddress(string $address, int $limit = 10): Collection
    {
        return $this->query(
            new OneStringQuery($address, $limit)
        );
    }

    /**
     * Возвращает результат запроса к сервису
     *
     * @param Query $query Объект запроса
     *
     * @return Collection
     * @throws ApiResponseException
     */
    private function query(Query $query): Collection
    {
        $result = $this->client->get($this->getURL($query), [
            'headers' => [
                'Connection' => 'close',
                'User-Agent' => \Campo\UserAgent::random()
            ],
        ]);

        $response = $result->getBody()->getContents();

        if (preg_match('/Error: (.*)/', $response, $matches)) {
            throw new ApiResponseException($matches[1]);
        }

        $array = json_decode($response, true);

        return collect($array['result'])->map(function ($street) {
            return new Street($street);
        });
    }

    /**
     * @param Query $query
     *
     * @return bool|string
     */
    private function getURL(Query $query)
    {
        $request = [];

        if ( !empty($this->token)) {
            $request['token'] = $this->token;
        }

        return static::DOMAIN.'api.php?'.http_build_query(array_merge($query->toArray(), $request));
    }
}