<?php

namespace App\Services\Crawler\Parsers;

use App\Services\Kladr\Client;
use Illuminate\Validation\Concerns\ValidatesAttributes;
use Symfony\Component\DomCrawler\Crawler;

class CourtInformationParser extends Parser
{
    use ValidatesAttributes;

    const REGEX_CODE = '/(?<code>[0-9]{2}[A-Z]{2,5}[0-9]{4})/';
    const REGEX_PHONE = '/(?<phone>[0-9]?\([0-9- ]{2,9}\)[ -]*[0-9]{1,3}[ -]?[0-9]{1,3}([ -]?[0-9]{2})?)/';
    const REGEX_ADDRESS = '/[0-9]{6}\,[ ]*(?<address>.*)\,[ ]*(д.*)/';

    /**
     * @param string $html
     *
     * @return array
     */
    public function parse(string $html): array
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent(toUtf8($html));

        $data = [
            'region' => $crawler->filter('body > div.sud_ter_name')->first()->text(),
            'name' => $crawler->filter('body > div.sud_name')->first()->text(),
            'code' => $this->findCode($html),
            'phone' => $this->findPhone($html),
            'email' => [],
            'url' => ''
        ];

        $crawler->filter('a')->each(function ($node) use (&$data) {
            if ($this->validateEmail(null, $node->text())) {
                $data['email'][] = $node->text();
            }

            if ($this->validateUrl(null, $node->text())) {
                $data['url'] = $node->text();
            }
        });

        return $data;
    }

    /**
     * @param string $html
     *
     * @return string
     */
    public function findPhone(string $html)
    {
        $phone = [];
        preg_match(static::REGEX_PHONE, $html, $phone);

        return array_get($phone, 'phone');
    }

    /**
     * @param string $html
     *
     * @return string
     */
    public function findCode(string $html)
    {
        $code = [];
        preg_match(static::REGEX_CODE, $html, $code);

        return array_get($code, 'code');
    }
}