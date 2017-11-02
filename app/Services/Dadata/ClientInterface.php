<?php

namespace App\Services\Dadata;

use App\Exceptions\AddressNotFound;
use Illuminate\Support\Collection;

interface ClientInterface
{
    /**
     * @param string $address
     *
     * @return Collection
     * @throws AddressNotFound
     */
    public function suggest(string $address): Collection;
}