<?php

namespace App\Services\Kladr;

class OneStringQuery extends Query
{
    /**
     * @param string $address
     * @param int $limit
     */
    public function __construct(string $address, int $limit = 10)
    {
        $this->query = $address;
        $this->withParent = true;
        $this->limit = $limit;
        $this->oneString = true;
    }
}