<?php

namespace Tests\Feature\Courts;

use App\Services\Crawler\Parsers\ArbitrationCourtInformationParser;
use App\Services\Crawler\Parsers\ArbitrationCourtListParser;
use Tests\TestCase;

class ArbitrationCourtParserTest extends TestCase
{

    public function test_parse_list_html()
    {
        $html = file_get_contents(base_path('tests/templates/arbitration_courts.html'));

        $parser = new ArbitrationCourtListParser();

        $this->assertEquals([
            [
                'name' => 'АС Волго-Вятског округа (Ф01)',
                'url' => 'http://arbitr.ru/as/okrug/?id_ac=86',
            ],
            [
                'name' => 'АС Поволжского округа (Ф06)',
                'url' => 'http://arbitr.ru/as/okrug/?id_ac=91',
            ],
        ], $parser->parse($html));
    }

    public function test_parse_court_information_html()
    {
        $html = file_get_contents(base_path('tests/templates/arbitration_court_information.html'));
        $parser = new ArbitrationCourtInformationParser();

        $this->assertEquals([
            'name' => 'Арбитражный суд Алтайского края',
            'address' => '656015 Барнаул, просп. Ленина, 76',
            'code' => 'А03',
            'phone' => '(385-2)61-92-78',
            'email' => ['info@altai-krai.arbitr.ru'],
            'url' => 'http://altai-krai.arbitr.ru/'
        ], $parser->parse($html));
    }
}
