<?php

namespace Module\ClaimCalculator\Tests\Strategies;

use Module\ClaimCalculator\Strategies\Daily;
use Tests\TestCase;

class DailyTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_calculate()
    {
        $strategy = new Daily(1000, now(), now()->addWeek(), 10);

        $this->assertEquals(700, $strategy->calculate());
    }
}
