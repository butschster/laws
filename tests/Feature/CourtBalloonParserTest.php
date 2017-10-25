<?php

namespace Tests\Feature;

use App\Services\Crawler\Parsers\CourtBalloonParser;
use Tests\TestCase;

class CourtBalloonParserTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_parser()
    {
        $js = <<<EOF
			if (typeof balloons_user['01MS0001'] == 'undefined'){
				balloons_user['01MS0001']= new Array();
			}
			balloons_user['01MS0001'][balloons_user['01MS0001'].length]={type:'mir',name:'Судебный участок № 1 г. Майкопа',adress:'385009, Республика Адыгея, г. Майкоп, п. Западный, ул. Юбилейная, д. 23 А',coord:[44.6289,40.0778]};
			if (typeof balloons_user['01MS0002'] == 'undefined'){
				balloons_user['01MS0002']= new Array();
			}
			balloons_user['01MS0002'][balloons_user['01MS0002'].length]={type:'mir',name:'Судебный участок № 2 г. Майкопа',adress:'385000, Республика Адыгея, г. Майкоп, ул. Крестьянская, д. 236',coord:[44.6112,40.1049]};
			if (typeof balloons_user['01MS0003'] == 'undefined'){
				balloons_user['01MS0003']= new Array();
			}
EOF;

        $parser = new CourtBalloonParser();

        $this->assertEquals([
            [
                'code' => '01MS0001',
                'type' => 'mir',
                'name' => 'Судебный участок № 1 г. Майкопа',
                'address' => '385009, Республика Адыгея, г. Майкоп, п. Западный, ул. Юбилейная, д. 23 А',
                'lat' => '44.6289',
                'lon' => '40.0778',
            ],
            [
                'code' => '01MS0002',
                'type' => 'mir',
                'name' => 'Судебный участок № 2 г. Майкопа',
                'address' => '385000, Республика Адыгея, г. Майкоп, ул. Крестьянская, д. 236',
                'lat' => '44.6112',
                'lon' => '40.1049',
            ],
        ], $parser->parse($js));
    }
}
