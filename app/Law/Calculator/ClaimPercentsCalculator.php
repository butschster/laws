<?php

namespace App\Law\Calculator;

use App\Contracts\Law\Calculator\Strategy;
use App\Exceptions\ClaimPercentsCalculator as ClaimPercentsCalculatorException;
use App\Law\AdditionalClaimAmount;
use App\Law\Calculator\Strategies\Daily;
use App\Law\Calculator\Strategies\Monthly;
use App\Law\Calculator\Strategies\Weekly;
use App\Law\Calculator\Strategies\Yearly;
use App\Law\Claim;
use App\Law\ReturnedClaimAmount;
use Carbon\Carbon;

class ClaimPercentsCalculator
{

    /**
     * @var Claim
     */
    private $claim;

    /**
     * @param Claim $claim
     */
    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    /**
     * Получение суммы возвращаемой по процентам
     *
     * @return float
     */
    public function percentsAmount(): float
    {
        $percents = $this->claim->percents();

        if ($percents > 0) {
            $startDate = $this->claim->borrowingDate();
            $amount = $this->claim->amount()->amount();

            $totalPercentsAmount = 0;

            $additionalAmounts = $this->claim->additionalAmounts();

            // Если у нас есть доп займы или погашения, то сначала считаем проценты до их наступления
            if ($additionalAmounts->count() > 0 && ($firstClaim = $additionalAmounts->first()) instanceof AdditionalClaimAmount) {
                $totalPercentsAmount += $this->getStrategy($amount, $percents, $startDate, $firstClaim->date())->calculate();
                $startDate = $firstClaim->date();
            }

            $lastClaim = null;
            // Расчитываем проценты с учетом отданных и полученых денег в течение срока
            foreach ($additionalAmounts as $i => $item) {
                // Если это дополнительный займ
                if ($item instanceof AdditionalClaimAmount) {
                    if ($lastClaim instanceof ReturnedClaimAmount) {
                        $totalPercentsAmount += $this->getStrategy($amount, $percents, $startDate, $item->date())->calculate();
                    }

                    $amount += $item->amount();
                    $nextClaim = $additionalAmounts->slice($i+1)->first();

                    if ($nextClaim) {
                        $totalPercentsAmount += $this->getStrategy($amount, $percents, $item->date(), $nextClaim->date())->calculate();
                        $startDate = $nextClaim->date();
                    } else {
                        $startDate = $item->date();
                    }

                } else { // Если это частичный возврат
                    $totalPercentsAmount += $this->getStrategy($amount, $percents, $startDate, $item->date())->calculate();

                    $amount -= $item->amount();
                    $startDate = $item->date();
                }


                $lastClaim = $item;
            }

            // Расчет процентов по оставшимся на руках деньгах
            $totalPercentsAmount += $this->getStrategy($amount, $percents, $startDate, $this->claim->returnDate())->calculate();

            return $totalPercentsAmount;
        }

        return 0.0;
    }

    /**
     * Получение суммы для возврата с учетом процентов
     *
     * @return float
     */
    public function totalAmount(): float
    {
        $amount = $this->claim->amount()->amount();

        foreach ($this->claim->additionalAmounts() as $i => $item) {
            if ($item instanceof AdditionalClaimAmount) {
                $amount += $item->amount();
            } else {
                $amount -= $item->amount();
            }
        }

        return $this->percentsAmount() + $amount;
    }

    /**
     * Выбор стратегии для расчета процентов
     *
     * @param float $amount
     * @param float $percents
     * @param Carbon $startDate
     * @param Carbon $endDate
     *
     * @return Strategy
     * @throws ClaimPercentsCalculatorException
     */
    protected function getStrategy(float $amount, float $percents, Carbon $startDate, Carbon $endDate): Strategy
    {
        $interval = $this->claim->interval();

        switch ($interval) {
            case Claim::DAILY:
                return new Daily($amount, $startDate, $endDate, $percents);
            case Claim::WEEKLY:
                return new Weekly($amount, $startDate, $endDate, $percents);
            case Claim::MONTHLY:
                return new Monthly($amount, $startDate, $endDate, $percents);
            case Claim::YEARLY:
                return new Yearly($amount, $startDate, $endDate, $percents);
            default:

                throw new ClaimPercentsCalculatorException('Strategy not found');

        }
    }
}