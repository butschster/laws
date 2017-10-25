<?php

namespace App\Services\Crawler\Parsers;

use Symfony\Component\CssSelector\CssSelectorConverter;
use Symfony\Component\DomCrawler\Crawler;

class ArbitrationCourtInformationParser extends Parser
{

    /**
     * @param string $html
     *
     * @return array
     */
    public function parse(string $html): array
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent(toUtf8($html));
        $converter = new CssSelectorConverter();

        $header = $crawler->filterXPath($converter->toXPath('h1.as_header'))->first();

        $table = $header->nextAll()->first();

        $data = [
            'name' => $header->text()
        ];

        $table->filterXPath($converter->toXPath('tr table > tr'))
              ->each(function (\Symfony\Component\DomCrawler\Crawler $node) use (&$data) {
                  $td = $node->filter('td');
                  $title = trim($td->first()->text());
                  $content = trim($td->last()->text());

                  if ($title == 'Адрес:') {
                      $data['address'] = $content;
                  } else if ($title == 'Индекс (код) суда:') {
                      $data['code'] = $content;
                  } else if ($title == 'Телефон:') {
                      $data['phone'] = $content;
                  } else if ($title == 'E-mail:') {
                      $data['email'] = [$content];
                  } else if ($title == 'Сайт:') {
                      $data['url'] = $content;
                  }
              });

        return $data;
    }
}