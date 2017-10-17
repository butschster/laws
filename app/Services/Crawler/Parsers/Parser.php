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

    /**
     * @param string $text
     *
     * @return string
     */
    protected function convertToUtf8(string $text): string
    {
        return iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $text);
    }
}