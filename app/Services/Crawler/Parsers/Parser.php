<?php

namespace App\Services\Crawler\Parsers;

abstract class Parser
{
    /**
     * @param string $html
     *
     * @return array
     */
    abstract public function parse(string $html): array;
}