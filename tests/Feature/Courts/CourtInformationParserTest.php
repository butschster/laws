<?php

namespace Tests\Feature\Courts;

use App\Services\Crawler\Parsers\CourtInformationParser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourtInformationParserTest extends TestCase
{
    /**
     * @dataProvider courtHtmlProvider
     */
    public function test_parse_court_information_from_html($html, $name, $region, $code, $phone, $email, $url)
    {
        $parser = new CourtInformationParser();

        $data = $parser->parse($html);

        $this->assertEquals($name, $data['name']);
        $this->assertEquals($region, $data['region']);
        $this->assertEquals($code, $data['code']);
        $this->assertEquals($phone, $data['phone']);
        $this->assertEquals($email, $data['email']);
        $this->assertEquals($url, $data['url']);
    }

    public function courtHtmlProvider()
    {
        return [
            [
                '<div class="sud_name">Гиагинский районный суд</div><div class="sud_okrug_name"></div><div class="sud_ter_name">Республика Адыгея</div><B>Классификационный код:</B> 01RS0001 <BR><B>Адрес:</B> 385600, ст. Гиагинская, ул. Советская, д. 35 <BR><B>Телефон:</B> (887779) 3-02-67 <BR><B>E-mail:</B><br>&nbsp;&nbsp;&nbsp;<a href=\'mailto:giaginsky.adg@sudrf.ru\'>giaginsky.adg@sudrf.ru</a><br/>&nbsp;&nbsp;&nbsp;<a href=\'mailto:giagsud@mail.ru\'>giagsud@mail.ru</a><br/><div style=\'padding-top:5px;\'><B>Официальный сайт:</B><br/>&nbsp;&nbsp;&nbsp;<a href=\'http://giaginsky.adg.sudrf.ru\' TARGET=\'_blank\'>http://giaginsky.adg.sudrf.ru</A><br/></div>',
                'Гиагинский районный суд',
                'Республика Адыгея',
                '01RS0001',
                '(887779) 3-02-67',
                ['giaginsky.adg@sudrf.ru', 'giagsud@mail.ru'],
                'http://giaginsky.adg.sudrf.ru'
            ],
            [
                '<div class="sud_name">Перовский районный суд</div><div class="sud_okrug_name"></div><div class="sud_ter_name">Город Москва</div><B>Классификационный код:</B> 77RS0020 <BR><B>Адрес:</B> 111398, г. Москва, ул. Кусковская, д. 8, стр. 1 <BR><B>Телефон:</B> (495) 309-03-02, (499) 748-66-36 <BR><B>E-mail:</B><br>&nbsp;&nbsp;&nbsp;<a href=\'mailto:perovsky.msk@sudrf.ru\'>perovsky.msk@sudrf.ru</a><br/><div style=\'padding-top:5px;\'><B>Официальный сайт:</B><br/>&nbsp;&nbsp;&nbsp;<a href=\'http://perovsky.msk.sudrf.ru\' TARGET=\'_blank\'>http://perovsky.msk.sudrf.ru</A><br/></div>',
                'Перовский районный суд',
                'Город Москва',
                '77RS0020',
                '(495) 309-03-02',
                ['perovsky.msk@sudrf.ru'],
                'http://perovsky.msk.sudrf.ru'
            ],

            [
                '<div class="sud_name">Алейский городской суд</div><div class="sud_okrug_name"></div><div class="sud_ter_name">Алтайский край</div><B>Классификационный код:</B> 22RS0001 <BR><B>Адрес:</B> 658130, Алтайский край, г. Алейск, ул. Советская, д. 98 <BR><B>Телефон:</B> (38553) 25-0-30 <BR><B>E-mail:</B><br>&nbsp;&nbsp;&nbsp;<a href=\'mailto:aleysky.alt@sudrf.ru\'>aleysky.alt@sudrf.ru</a><br/><div style=\'padding-top:5px;\'><B>Официальный сайт:</B><br/>&nbsp;&nbsp;&nbsp;<a href=\'http://aleysky.alt.sudrf.ru\' TARGET=\'_blank\'>http://aleysky.alt.sudrf.ru</A><br/></div>',
                'Алейский городской суд',
                'Алтайский край',
                '22RS0001',
                '(38553) 25-0-30',
                ['aleysky.alt@sudrf.ru'],
                'http://aleysky.alt.sudrf.ru'
            ],
            [
                '<div class="sud_name">Фокинский городской суд</div><div class="sud_okrug_name"></div><div class="sud_ter_name">Приморский край</div><B>Классификационный код:</B> 25RS0038 <BR><B>Адрес:</B> 692880, г. Фокино, ул. Центральная, д. 4 <BR><B>Телефон:</B> (42339) 29-212 (т/ф.)  <BR><B>E-mail:</B><br>&nbsp;&nbsp;&nbsp;<a href=\'mailto:fokinsky.prm@sudrf.ru\'>fokinsky.prm@sudrf.ru</a><br/><div style=\'padding-top:5px;\'><B>Официальный сайт:</B><br/>&nbsp;&nbsp;&nbsp;<a href=\'http://fokinsky.prm.sudrf.ru\' TARGET=\'_blank\'>http://fokinsky.prm.sudrf.ru</A><br/></div>',
                'Фокинский городской суд',
                'Приморский край',
                '25RS0038',
                '(42339) 29-212',
                ['fokinsky.prm@sudrf.ru'],
                'http://fokinsky.prm.sudrf.ru'
            ],
            [
                '<div class="sud_name">Шарыповский районный суд</div><div class="sud_okrug_name"></div><div class="sud_ter_name">Красноярский край</div><B>Классификационный код:</B> 24RS0058 <BR><B>Адрес:</B> 662311, г. Шарыпово, мкр-н Пионерный, д. 11 <BR><B>Телефон:</B> (391-53) 2-21-42 <BR><B>E-mail:</B><br>&nbsp;&nbsp;&nbsp;<a href=\'mailto:sharray.krk@sudrf.ru\'>sharray.krk@sudrf.ru</a><br/><div style=\'padding-top:5px;\'><B>Официальный сайт:</B><br/>&nbsp;&nbsp;&nbsp;<a href=\'http://sharray.krk.sudrf.ru\' TARGET=\'_blank\'>http://sharray.krk.sudrf.ru</A><br/></div>',
                'Шарыповский районный суд',
                'Красноярский край',
                '24RS0058',
                '(391-53) 2-21-42',
                ['sharray.krk@sudrf.ru'],
                'http://sharray.krk.sudrf.ru'
            ]
        ];
    }
}
