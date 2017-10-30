<?php

namespace Module\FineCalculator\Tests;

use App\FederalDistrict;
use App\Law\Claim;
use Carbon\Carbon;
use Module\FineCalculator\Calculator;
use Tests\TestCase;

class CalculatorTest extends TestCase
{

    function test_make_intervals()
    {
        $district = new FederalDistrict();
        $district->id = 2;

        $claim = new Claim(10000, Carbon::parse('2014-01-01'), Carbon::parse('2016-09-01'));
        $calculator = new Calculator($claim, $district);

        $intervals = $calculator->makeIntervals();

        $this->assertEquals(2290.81, $intervals->sum());
    }

    function test_make_intervals_with_returned_money()
    {
        $district = new FederalDistrict();
        $district->id = 2;

        $claim = new Claim(50000, Carbon::parse('2015-01-01'), Carbon::parse('2015-09-01'));
        $claim->addReturnedMoney(Carbon::parse('2015-07-01'), 20000);
        $claim->addReturnedMoney(Carbon::parse('2015-08-20'), 20000);

        $calculator = new Calculator($claim, $district);

        $intervals = $calculator->makeIntervals();

        $this->assertEquals([
            [
                "from" => "2015-01-01",
                "to" => "2015-06-01",
                "rate" => 8.25,
                "amount" => 50000.0,
            ],
            [
                "from" => "2015-06-01",
                "to" => "2015-06-15",
                "rate" => 11.44,
                "amount" => 50000.0,
            ],
            [
                "from" => "2015-06-15",
                "to" => "2015-06-30",
                "rate" => 11.37,
                "amount" => 50000.0,
            ],
            [
                "from" => "2015-07-01",
                "to" => "2015-07-15",
                "rate" => 11.37,
                "amount" => 30000.0,
            ],
            [
                "from" => "2015-07-15",
                "to" => "2015-08-17",
                "rate" => 10.36,
                "amount" => 30000.0,
            ],
            [
                "from" => "2015-08-17",
                "to" => "2015-08-19",
                "rate" => 10.11,
                "amount" => 30000.0,
            ],
            [
                "from" => "2015-08-20",
                "to" => "2015-09-01",
                "rate" => 10.11,
                "amount" => 10000.0,
            ],
        ], $intervals->toArray());
    }

    function test_make_intervals_with_additional_money()
    {
        $district = new FederalDistrict();
        $district->id = 2;

        $claim = new Claim(50000, Carbon::parse('2015-01-01'), Carbon::parse('2015-09-01'));
        $claim->addClaimedMoney(Carbon::parse('2015-07-01'), 20000);
        $claim->addClaimedMoney(Carbon::parse('2015-08-20'), 20000);

        $calculator = new Calculator($claim, $district);

        $intervals = $calculator->makeIntervals();

        $this->assertEquals([
            [
                "from" => "2015-01-01",
                "to" => "2015-06-01",
                "rate" => 8.25,
                "amount" => 50000.0,
            ],
            [
                "from" => "2015-06-01",
                "to" => "2015-06-15",
                "rate" => 11.44,
                "amount" => 50000.0,
            ],
            [
                "from" => "2015-06-15",
                "to" => "2015-06-30",
                "rate" => 11.37,
                "amount" => 50000.0,
            ],
            [
                "from" => "2015-07-01",
                "to" => "2015-07-15",
                "rate" => 11.37,
                "amount" => 70000.0,
            ],
            [
                "from" => "2015-07-15",
                "to" => "2015-08-17",
                "rate" => 10.36,
                "amount" => 70000.0,
            ],
            [
                "from" => "2015-08-17",
                "to" => "2015-08-19",
                "rate" => 10.11,
                "amount" => 70000.0,
            ],
            [
                "from" => "2015-08-20",
                "to" => "2015-09-01",
                "rate" => 10.11,
                "amount" => 90000.0,
            ],
        ], $intervals->toArray());
    }
}