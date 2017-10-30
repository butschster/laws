<?php

namespace Module\ClaimCalculator\Tests\Strategies;

use Module\ClaimCalculator\Strategies\Monthly;
use Carbon\Carbon;
use Tests\TestCase;

class MonthlyTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_calculate()
    {
        $strategy = new Monthly(1000, Carbon::parse('2017-01-01'), Carbon::parse('2017-04-20'), 10);

        $this->assertEquals(363.33, $strategy->calculate());
    }
}
