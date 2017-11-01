<?php

namespace Tests\Feature\Claim;

use App\Law\InterestRate;
use Tests\TestCase;

class InterestRateTest extends TestCase
{
    function test_check_if_rate_has_percents()
    {
        $rate = new InterestRate(0);

        $this->assertFalse($rate->hasPercents());

        $rate = new InterestRate(1);

        $this->assertTrue($rate->hasPercents());
    }

    function test_gets_percents_amount()
    {
        $rate = new InterestRate(1.5);

        $this->assertEquals(1.5, $rate->percents());
    }

    function test_gets_and_sets_interval()
    {
        $rate = new InterestRate(1.5);
        $this->assertEquals(InterestRate::MONTHLY, $rate->interval());

        $rate = new InterestRate(1.5, InterestRate::WEEKLY);
        $this->assertEquals(InterestRate::WEEKLY, $rate->interval());
    }
}
