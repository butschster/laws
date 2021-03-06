<?php

namespace App\Services\Dadata;

use Illuminate\Support\Collection;

class Client implements ClientInterface
{
    const URL = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address';

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var string
     */
    private $token;

    /**
     * @param \GuzzleHttp\Client $client
     * @param string $token
     */
    public function __construct(\GuzzleHttp\Client $client, string $token)
    {
        $this->client = $client;
        $this->token = $token;
    }

    /**
     * @param string $address
     *
     * @return Collection
     */
    public function suggest(string $address): Collection
    {
        $response = $this->client->get(static::URL, [
            'query' => [
                'query' => $address,
            ],
            'headers' => [
                'Content-type' => 'application/json',
                'User-Agent' => \Campo\UserAgent::random(),
                'Authorization' => 'Token '.$this->token,
            ],
        ]);

        $jsonString = $response->getBody()->getContents();

        $data = \GuzzleHttp\json_decode($jsonString, true);

        return collect(array_get($data, 'suggestions'));
    }
}