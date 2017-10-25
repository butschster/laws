<?php

namespace App\Services\Crawler\Parsers;

use App\Contracts\ParserInterface;

abstract class Parser implements ParserInterface
{
    /**
     * @param string $html
     *
     * @return array
     */
    abstract public function parse(string $html): array;
}