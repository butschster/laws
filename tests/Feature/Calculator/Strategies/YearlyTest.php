<?php

namespace Tests\Feature\Calculator\Strategies;

use App\Law\Calculator\Strategies\Yearly;
use Carbon\Carbon;
use Tests\TestCase;

class YearlyTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_calculate()
    {
        $strategy = new Yearly(1000, Carbon::parse('2017-01-01'), Carbon::parse('2020-06-10'), 10);

        $this->assertEquals(344.13, $strategy->calculate());
    }
}
