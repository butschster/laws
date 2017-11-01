<?php

namespace Module\ClaimCalculator\Tests\Feature;

use App\Law\InterestRate;
use Tests\TestCase;

class CalculatorTest extends TestCase
{

    function test_calculate_with_failed_validation()
    {
        $response = $this->json('post', route('claim.calculate.fine'));

        $response->assertStatus(422);
    }

    function test_calculate()
    {
        $response = $this->json('post', route('claim.calculate.fine'), [
            'amount' => 1000,
            'date_of_borrowing' => '26.10.2016',
            'date_of_return' => '26.10.2017',
            'is_interest_bearing_loan' => true,
            'interest_bearing_loan' => [
                'interval' => InterestRate::MONTHLY,
                'percent' => 10,
            ],
            'has_returned_money' => false,
            'has_claimed_money' => false,
        ]);

        $response->assertStatus(200)->assertJson([
                'data' => [
                    'amount' => 1000,
                    'percents' => 1200,
                    'amount_with_percents' => 2200,
                    'summary' => [
                        [
                            'amount' => 1000,
                            'rate' => 10,
                            'percents' => 1200,
                            'from' => '2016-10-26',
                            'to' => '2017-10-26',
                            'days' => 366
                        ],
                    ],
                ],
            ]);
    }
}