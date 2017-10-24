<?php

namespace App\Services\Dadata;

use Illuminate\Support\Collection;

interface ClientInterface
{
    /**
     * @param string $address
     *
     * @return Collection
     */
    public function suggest(string $address): Collection;
}