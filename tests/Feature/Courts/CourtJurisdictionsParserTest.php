<?php

namespace Tests\Feature\Courts;

use App\Services\Crawler\Parsers\CourtJurisdictionsParser;
use Tests\TestCase;

class CourtJurisdictionsParserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_parse_html()
    {
        $html = file_get_contents(base_path('tests/templates/court_jurisdictions.html'));
        $parser = new CourtJurisdictionsParser();

        $this->assertEquals([
            [
                'city' => 'г. Спасск-Рязанский',
                'address' => ''
            ],
            [
                'city' => 'д. Агломазово',
                'address' => ''
            ],
            [
                'city' => 'д. Аграфеновка',
                'address' => 'Simple Text'
            ]
        ], $parser->parse($html));
    }

    public function test_parse_paginator()
    {
        $html = file_get_contents(base_path('tests/templates/court_jurisdictions.html'));
        $parser = new CourtJurisdictionsParser();

        $this->assertEquals(7, $parser->parseTotalPages($html));
    }
}
