<?php

namespace Tests\Feature;

use App\Law\Calculator\ClaimPercentsCalculator;
use App\Law\Claim;
use Carbon\Carbon;
use Tests\TestCase;

class CalculateClaimPercentsTest extends TestCase
{
    /**
     * @dataProvider claimAmountsProvider
     *
     * @param $amount // Сумма займа
     * @param $borrow // Дата выдачи
     * @param $return // Дата возврата
     * @param $percent // Процент
     * @param $interval // Период начисления
     * @param $totalPercents // Сумма набежавшик процентов
     * @param $totalAmount // Общая сумма долга
     */
    function test_calculate_claim_percents($amount, $borrow, $return, $percent, $interval, $totalPercents, $totalAmount)
    {
        $claim = new Claim($amount, Carbon::parse($borrow), Carbon::parse($return), $percent, $interval);

        $calculator = new ClaimPercentsCalculator($claim);

        $this->assertEquals($totalPercents, $calculator->percentsAmount());
        $this->assertEquals($totalAmount, $calculator->totalAmount());
    }

    function test_calculate_claim_percents_with_returned_money()
    {
        $claim = new Claim(100000, Carbon::parse('2017-01-01'), Carbon::parse('2018-01-01'), 10);

        $claim->addReturnedMoney(Carbon::parse('2017-03-01'), 20000);
        $claim->addReturnedMoney(Carbon::parse('2017-09-01'), 20000);

        // Взяли 100 000 под 10% в месяц с 01.01.17
        // Январь - 10 000
        // Февраоь - 10 000
        // 01.03 вернули 20 000
        // Март - 8 000
        // Апрель - 8 000
        // Май - 8 000
        // Июнь - 8 000
        // Июль - 8 000
        // Август - 8 000
        // 01.09 вернули 20 000
        // Сентябрь - 6 000
        // Октябрь - 6 000
        // Ноябрь - 6 000
        // Декабрь - 6 000
        // 01.01.18 Должны вернуть 92 000 процентов и 152 000 займа

        $calculator = new ClaimPercentsCalculator($claim);

        $this->assertEquals(92000, $calculator->percentsAmount());
        $this->assertEquals(152000, $calculator->totalAmount());
    }

    function test_calculate_claim_percents_with_additional_claimed_money()
    {
        $claim = new Claim(100000, Carbon::parse('2017-01-01'), Carbon::parse('2018-01-01'), 10);

        $claim->addClaimedMoney(Carbon::parse('2017-03-01'), 20000);
        $claim->addClaimedMoney(Carbon::parse('2017-09-01'), 20000);

        // Взяли 100 000 под 10% в месяц с 01.01.17
        // Январь - 10 000
        // Февраоь - 10 000
        // 01.03 взяли 20 000
        // Март - 12 000
        // Апрель - 12 000
        // Май - 12 000
        // Июнь - 12 000
        // Июль - 12 000
        // Август - 12 000
        // 01.09 вернули 20 000
        // Сентябрь - 14 000
        // Октябрь - 14 000
        // Ноябрь - 14 000
        // Декабрь - 14 000
        // 01.01.18 Должны вернуть 148 000 процентов и 100 000 займа

        $calculator = new ClaimPercentsCalculator($claim);

        $this->assertEquals(148000, $calculator->percentsAmount());
        $this->assertEquals(288000, $calculator->totalAmount());
    }

    function test_calculate_claim_percents_with_additional_claimed_and_returned_money()
    {
        $claim = new Claim(100000, Carbon::parse('2016-10-26'), Carbon::parse('2017-10-26'), 2);

        $claim->addReturnedMoney(Carbon::parse('2017-01-26'), 50000);
        $claim->addClaimedMoney(Carbon::parse('2017-04-26'), 60000);

        // 11.26 2000
        // 12.26 2000
        // 01.26 2000
        // 02.26 1000
        // 03.26 1000
        // 04.26 1000
        // 05.26 2200
        // 06.26 2200
        // 06.26 2200
        // 06.26 2200
        // 06.26 2200
        // 06.26 2200

        $calculator = new ClaimPercentsCalculator($claim);

        $this->assertEquals(22200, $calculator->percentsAmount());
        $this->assertEquals(132200, $calculator->totalAmount());
    }

    function test_calculate_claim_percents_with_additional_claimed_money_2()
    {
        $claim = new Claim(50000, Carbon::parse('2016-10-26'), Carbon::parse('2017-10-26'), 2);

        $claim->addClaimedMoney(Carbon::parse('2017-04-26'), 50000);

        $calculator = new ClaimPercentsCalculator($claim);

        $this->assertEquals(18000, $calculator->percentsAmount());
        $this->assertEquals(118000, $calculator->totalAmount());
    }

    function claimAmountsProvider()
    {
        return [
            [
                100000, // Сумма займа
                '2017-01-01', // Дата выдачи
                '2017-12-01', // Дата возврата
                15, // Процент
                Claim::MONTHLY, // Период начисления
                165000, // Сумма набежавшик процентов
                265000, // Общая сумма долга
            ],
            [
                100000, // Сумма займа
                '2017-01-20', // Дата выдачи
                '2017-03-10', // Дата возврата
                15, // Процент
                Claim::MONTHLY, // Период начисления
                24124.42, // Сумма набежавшик процентов
                124124.42, // Общая сумма долга

                // 4285,714285714286
                // 15000
                // 4838,709677419355
            ],
            [
                100, // Сумма займа
                '2017-01-01', // Дата выдачи
                '2017-02-01', // Дата возврата
                1, // Процент
                Claim::DAILY, // Период начисления
                31, // Сумма набежавшик процентов
                131, // Общая сумма долга
            ],
            [
                100000, // Сумма займа
                '2015-04-15', // Дата выдачи
                '2017-06-26', // Дата возврата
                10, // Процент
                Claim::YEARLY, // Период начисления
                21968.04, // Сумма набежавшик процентов
                121968.04, // Общая сумма долга

                // 20000
                // 1666,666666666667
                // 3666,666666666667
            ],
            [
                100000, // Сумма займа
                '2015-01-01', // Дата выдачи
                '2017-01-01', // Дата возврата
                10, // Процент
                Claim::YEARLY, // Период начисления
                20000, // Сумма набежавшик процентов
                120000, // Общая сумма долга
            ],
            [
                100000, // Сумма займа
                '2016-10-26', // Дата выдачи
                '2016-11-26', // Дата возврата
                3, // Процент
                Claim::YEARLY, // Период начисления
                250, // Сумма набежавшик процентов
                100250, // Общая сумма долга
            ],
            [
                100000, // Сумма займа
                '2016-10-09', // Дата выдачи
                '2016-11-09', // Дата возврата
                12, // Процент
                Claim::MONTHLY, // Период начисления
                12000, // Сумма набежавшик процентов
                112000, // Общая сумма долга
            ],
            [
                100000, // Сумма займа
                '2017-04-10', // Дата выдачи
                '2017-10-25', // Дата возврата
                2, // Процент
                Claim::MONTHLY, // Период начисления
                12967.74, // Сумма набежавшик процентов
                112967.74, // Общая сумма долга
            ],
            [
                100000, // Сумма займа
                '2016-10-26', // Дата выдачи
                '2017-01-26', // Дата возврата
                3, // Процент
                Claim::MONTHLY, // Период начисления
                9000, // Сумма набежавшик процентов
                109000, // Общая сумма долга
            ],
        ];
    }
}
