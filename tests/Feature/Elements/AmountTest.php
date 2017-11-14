<?php

namespace Tests\Feature\Elements;

use App\Law\Amount;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AmountTest extends TestCase
{
    function test_sub()
    {
        $amount = new Amount(1000);
        $amount->sub(new Amount(100.50));

        $this->assertEquals(899.50, $amount->amount());

        $amount->sub(new Amount(501.50));
        $this->assertEquals(398, $amount->amount());
    }
    function test_add()
    {
        $amount = new Amount(1000);
        $amount->add(new Amount(100.50));

        $this->assertEquals(1100.50, $amount->amount());
    }

    /**
     * @dataProvider amountsDataProvider
     */
    function test_gets_values($amount, $rubles, $pennies)
    {
        $a = new Amount($amount);

        $this->assertEquals($amount, $a->amount());
        $this->assertEquals($rubles, $a->rubles());
        $this->assertEquals($pennies, $a->pennies());
    }

    function test_gets_text()
    {
        $amount = new Amount(100.50);

        $this->assertEquals('100 руб. 50 коп.', $amount->text());
    }

    function amountsDataProvider()
    {
        return [
            [
                100,
                100,
                0
            ],
            [
                100.50,
                100,
                50
            ],
            [
                0.50,
                0,
                50
            ]
        ];
    }
}
