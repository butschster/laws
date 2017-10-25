<?php

namespace App\Services\Kladr;

use App\Services\Kladr\Objects\Street;
use Illuminate\Support\Collection;

class Client
{

    const URL = 'http://kladr-api.ru/api.php';

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
        return $this->query(new OneStringQuery($address, $limit));
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
        $request = [];

        if (! empty($this->token)) {
            $request['token'] = $this->token;
        }

        $result = $this->client->get(static::URL, [
            'query' => array_merge($query->toArray(), $request),
            'headers' => [
                'Connection' => 'close',
                'User-Agent' => \Campo\UserAgent::random(),
            ],
        ]);

        $response = $result->getBody()->getContents();

        if (preg_match('/Error: (.*)/', $response, $matches)) {
            throw new ApiResponseException($matches[1]);
        }

        $array = \GuzzleHttp\json_decode($response, true);

        return collect($array['result'])
            ->where('contentType', 'street')
            ->map(function ($street) {
                return new Street($street);
            });
    }
}