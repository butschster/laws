<?php

namespace App\Rules;

use App\Services\Dadata\ClientInterface;
use Illuminate\Contracts\Validation\Rule;

class Address implements Rule
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * Address constructor.
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
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
        $response = $this->client->suggest($value);

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
