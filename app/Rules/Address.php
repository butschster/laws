<?php

namespace App\Rules;

use App\Services\Kladr\Client;
use Illuminate\Contracts\Validation\Rule;

class Address implements Rule
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $response = $this->client->findByAddress($value);

        return $response->count() > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Адрес не найден.';
    }
}
