<?php

namespace Tests\Feature\Calculator\Strategies;

use App\Law\Calculator\Strategies\Daily;
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
