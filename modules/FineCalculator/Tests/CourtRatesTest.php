<?php

namespace Module\FineCalculator\Tests;

use App\FederalDistrict;
use Module\FineCalculator\Rates;
use Tests\TestCase;

class CourtRatesTest extends TestCase
{
    function test_build_rates_from_config()
    {
        $district = new FederalDistrict();
        $district->id = 1;

        $rates = new Rates($district);

        $this->assertTrue($rates->rates()->count() > 0);
    }

    /**
     * @expectedException \Module\FineCalculator\Exceptions\DistrictRatesNotFound
     */
    function test_rates_not_found()
    {
        $district = new FederalDistrict();

        new Rates($district);
    }
}