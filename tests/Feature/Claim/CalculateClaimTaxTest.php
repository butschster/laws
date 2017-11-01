<?php

namespace Tests\Feature\Claim;

use App\Law\ClaimTax;
use App\Law\Person;
use App\Law\Plaintiff;
use App\Law\Respondent;
use Tests\TestCase;

class CalculateClaimTaxTest extends TestCase
{
    /**
     * @dataProvider amountsForTaxData
     */
    function test_calculate_tax($amount, $taxAmount, $plaintiffType, $respondentType)
    {
        $plaintiff = new Plaintiff('ФИО', 'Адрес', null, null, $plaintiffType);
        $respondent = new Respondent('ФИО', 'Адрес', null, null, $respondentType);

        $tax = new ClaimTax($amount, $plaintiff, $respondent);

        $this->assertEquals($taxAmount, $tax->amount());
    }

    function amountsForTaxData()
    {
        return [
            // Физ лица
            [
                100, 400, Person::TYPE_INDIVIDUAL, Person::TYPE_INDIVIDUAL
            ],
            [
                19000, 760, Person::TYPE_INDIVIDUAL, Person::TYPE_INDIVIDUAL
            ],
            [
                22000, 860, Person::TYPE_INDIVIDUAL, Person::TYPE_INDIVIDUAL
            ],
            [
                150000, 4200, Person::TYPE_INDIVIDUAL, Person::TYPE_INDIVIDUAL
            ],
            [
                250000, 5700, Person::TYPE_INDIVIDUAL, Person::TYPE_INDIVIDUAL
            ],
            [
                1250000, 14450, Person::TYPE_INDIVIDUAL, Person::TYPE_INDIVIDUAL
            ],
            [
                33250000, 60000, Person::TYPE_INDIVIDUAL, Person::TYPE_INDIVIDUAL
            ],

            // Юр лица
            [
                100, 2000, Person::TYPE_LEGAL_ENTITY, Person::TYPE_LEGAL_ENTITY
            ],
            [
                19000, 2000, Person::TYPE_LEGAL_ENTITY, Person::TYPE_LEGAL_ENTITY
            ],
            [
                22000, 2000, Person::TYPE_LEGAL_ENTITY, Person::TYPE_LEGAL_ENTITY
            ],
            [
                150000, 5500, Person::TYPE_LEGAL_ENTITY, Person::TYPE_LEGAL_ENTITY
            ],
            [
                250000, 8000, Person::TYPE_LEGAL_ENTITY, Person::TYPE_LEGAL_ENTITY
            ],
            [
                1250000, 25500, Person::TYPE_LEGAL_ENTITY, Person::TYPE_LEGAL_ENTITY
            ],
            [
                33250000, 189250, Person::TYPE_LEGAL_ENTITY, Person::TYPE_LEGAL_ENTITY
            ],
            [
                50250000, 200000, Person::TYPE_LEGAL_ENTITY, Person::TYPE_LEGAL_ENTITY
            ],
        ];
    }
}
