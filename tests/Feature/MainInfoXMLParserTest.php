<?php

namespace Tests\Feature;

use App\Services\CBR\MainInfoXMLParser;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MainInfoXMLParserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_parse_get_rate_from_xml()
    {
        $xml = <<<XML
<RegData xmlns=""><stavka_ref Title="Ставка рефинансирования" Date="18.09.2017">8.50</stavka_ref><GoldBaks Title="Международные резервы">427</GoldBaks><BaksWeight Title="Денежная масса (M2)">36917.8</BaksWeight><BaksBase Title="Денежная база">11084.8</BaksBase><NOR Title="Нормативы обязательных резервов"><Ob_1 Title="банк-нерез">5.00</Ob_1><Ob_1_USD Title="банк-нерез">7.00</Ob_1_USD><Ob_2 Title="физ лиц в ин вал">5.00</Ob_2><Ob_2_USD Title="физ лиц в ин вал">6.00</Ob_2_USD><Ob_3 Title="по иным">5.00</Ob_3><Ob_3_USD Title="по иным">7.00</Ob_3_USD><PKoef Title="Поправочный коэффициент">0.2</PKoef><Ku_1 Title="для банков">0.8</Ku_1><Ku_2 Title="для РНКО и РЦ ОРЦБ">1</Ku_2></NOR></RegData>
XML;

        $parser = new MainInfoXMLParser();

        $this->assertEquals(8.5, $parser->parse($xml));
    }
}
