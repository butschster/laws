<?php

namespace Tests\Feature\Courts;

use App\Court as CourtModel;
use App\Law\Court;
use App\Law\Person;
use App\Law\Plaintiff;
use App\Law\Respondent;
use Tests\TestCase;

class CourtTest extends TestCase
{
    function test_detect_arbitrage_court_by_persons()
    {
        $plaintiff = new Plaintiff('ФИО', 'г. Москва, ул. Индустриальная 16', null, null, Person::TYPE_LEGAL_ENTITY);
        $respondent = new Respondent('ФИО', 'г. Тамбов, ул. Жемчужникова 14', null, null, Person::TYPE_INDIVIDUAL_BUSINESS);

        $this->assertTrue(
            CourtModel::find(9386)->is(
                Court::detect($plaintiff, $respondent)
            )
        );
    }

    /**
     * @dataProvider personsForCourtTypesData
     */
    function test_detects_court_type($plaintiffType, $respondentType, $amount, $expects)
    {
        $plaintiff = new Plaintiff('ФИО', 'Адрес', null, null, $plaintiffType);
        $respondent = new Respondent('ФИО', 'Адрес', null, null, $respondentType);

        $this->assertEquals($expects, Court::detectType($plaintiff, $respondent, $amount));
    }

    function personsForCourtTypesData()
    {
        return [
            [
                Person::TYPE_INDIVIDUAL, Person::TYPE_LEGAL_ENTITY, 0, CourtModel::TYPE_MIR,
                Person::TYPE_INDIVIDUAL, Person::TYPE_INDIVIDUAL, 0, CourtModel::TYPE_MIR,
                Person::TYPE_INDIVIDUAL, Person::TYPE_INDIVIDUAL, 501000, CourtModel::TYPE_COMMON,

                Person::TYPE_LEGAL_ENTITY, Person::TYPE_INDIVIDUAL, 0, CourtModel::TYPE_MIR,
                Person::TYPE_LEGAL_ENTITY, Person::TYPE_INDIVIDUAL, 501000, CourtModel::TYPE_COMMON,
                Person::TYPE_LEGAL_ENTITY, Person::TYPE_LEGAL_ENTITY, 0, CourtModel::TYPE_ARBITR_SUBJ,
                Person::TYPE_LEGAL_ENTITY, Person::TYPE_INDIVIDUAL_BUSINESS, 0, CourtModel::TYPE_ARBITR_SUBJ,

                Person::TYPE_INDIVIDUAL_BUSINESS, Person::TYPE_LEGAL_ENTITY, 0, CourtModel::TYPE_ARBITR_SUBJ,
                Person::TYPE_INDIVIDUAL_BUSINESS, Person::TYPE_INDIVIDUAL_BUSINESS, 0, CourtModel::TYPE_ARBITR_SUBJ,
                Person::TYPE_INDIVIDUAL_BUSINESS, Person::TYPE_INDIVIDUAL, 0, CourtModel::TYPE_MIR,
                Person::TYPE_INDIVIDUAL_BUSINESS, Person::TYPE_INDIVIDUAL, 501000, CourtModel::TYPE_COMMON,
            ],
        ];
    }
}