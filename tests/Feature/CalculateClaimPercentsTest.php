<?php

namespace Tests\Feature;

use App\Law\Calculator\ClaimPercentsCalculator;
use App\Law\Claim;
use Carbon\Carbon;
use Tests\TestCase;

class CalculateClaimPercentsTest extends TestCase
{
    /**
     * @dataProvider claimAmounts
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
        // 01.01.18 Должны вернуть 92 000 процентов и 100 000 займа

        $calculator = new ClaimPercentsCalculator($claim);

        $this->assertEquals(92000, $calculator->percentsAmount());
        $this->assertEquals(192000, $calculator->totalAmount());
    }

    function claimAmounts()
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
                24670, // Сумма набежавшик процентов
                124670, // Общая сумма долга

                // 1 месяц 11 / 31 * 15% * 100 000 / 100 = 5322,58064516129
                // 2 месяц 15000
                // 3 месяц 10 / 31 * 15% * 100 000 / 100 =  4838,709677419355
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
                21929.22, // Сумма набежавшик процентов
                121929.22, // Общая сумма долга

                // 1 год (8 месяцев / 12 * 10% * 100 000) / 100 = 6666,666666666667
                //  + (15 дней / 365 * 10% * 100 000_ / 100 = 410,958904109589

                // 2 год 1 * 10% * 100 000 = 10 000

                // 3 год 5 месяцев / 12 * 10% * 100 000 = 4166,666666666667
                // + 26 дней / 365 * 10% * 100 000 = 712,3287671232877
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
        ];
    }
}
