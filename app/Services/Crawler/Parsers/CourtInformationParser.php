<?php

namespace App\Services\Crawler\Parsers;

use Illuminate\Validation\Concerns\ValidatesAttributes;
use Symfony\Component\DomCrawler\Crawler;

class CourtInformationParser extends Parser
{
    use ValidatesAttributes;

    /**
     * @param string $html
     *
     * @return array
     */
    public function parse(string $html): array
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($this->convertToUtf8($html));

        $code = [];
        preg_match('/(?<code>[0-9]{2}[A-Z]{2,5}[0-9]{4})/', $html, $code);
        $phone = [];
        preg_match('/(?<phone>[0-9]?\([0-9- ]{2,9}\)[ -]*[0-9]{1,3}[ -]?[0-9]{1,3}([ -]?[0-9]{2})?)/', $html, $phone);


        $data = [
            'region' => $crawler->filter('body > div.sud_ter_name')->first()->text(),
            'name' => $crawler->filter('body > div.sud_name')->first()->text(),
            'okrug' => $crawler->filter('body > div.sud_okrug_name')->first()->text(),
            'code' => array_get($code, 'code'),
            'phone' => array_get($phone, 'phone'),
            'email' => [],
            'url' => ''
        ];

        $crawler->filter('a')->each(function($node) use(&$data) {
            if ($this->validateEmail(null, $node->text())) {
                $data['email'][] = $node->text();
            }

            if ($this->validateUrl(null, $node->text())) {
                $data['url'] = $node->text();
            }
        });

        return $data;
    }
}