<?php

namespace Tests\Feature\Calculator\Strategies;

use App\Law\Calculator\Strategies\Weekly;
use Carbon\Carbon;
use Tests\TestCase;

class WeeklyTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_calculate()
    {
        $strategy = new Weekly(1000, Carbon::parse('2017-01-01'), Carbon::parse('2017-01-25'), 10);

        $this->assertEquals(342.86, $strategy->calculate());
    }
}
