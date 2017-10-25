<?php

namespace App\Contracts;

interface ParserInterface
{

    /**
     * @param string $html
     *
     * @return array
     */
    public function parse(string $html);
}