<?php

namespace App\Law\Calculator;

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
     * @var array
     */
    private $summary = [];

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
    protected function calculatePercents(): float
    {
        $percents = $this->claim->percents();

        if ($percents > 0) {
            $startDate = $this->claim->borrowingDate();
            $amount = $this->claim->amount()->amount();

            $totalPercentsAmount = 0;

            $additionalAmounts = $this->claim->additionalAmounts();

            // Если у нас есть доп займы или погашения, то сначала считаем проценты до их наступления
            if ($additionalAmounts->count() > 0 && ($firstClaim = $additionalAmounts->first()) instanceof AdditionalClaimAmount) {
                $totalPercentsAmount += $this->getStrategy($amount, $percents, $startDate, $firstClaim->date());
                $startDate = $firstClaim->date();
            }

            $lastClaim = null;

            // Расчитываем проценты с учетом отданных и полученых денег в течение срока
            foreach ($additionalAmounts as $i => $item) {
                // Если это дополнительный займ
                if ($item instanceof AdditionalClaimAmount) {
                    if ($lastClaim instanceof ReturnedClaimAmount) {
                        $totalPercentsAmount += $this->getStrategy($amount, $percents, $startDate, $item->date());
                    }

                    $amount += $item->amount();
                    $nextClaim = $additionalAmounts->slice($i+1)->first();

                    if ($nextClaim) {
                        $totalPercentsAmount += $this->getStrategy($amount, $percents, $item->date(), $nextClaim->date());
                        $startDate = $nextClaim->date();
                    } else {
                        $startDate = $item->date();
                    }

                } else { // Если это частичный возврат
                    $totalPercentsAmount += $this->getStrategy($amount, $percents, $startDate, $item->date());

                    $amount -= $item->amount();
                    $startDate = $item->date();
                }


                $lastClaim = $item;
            }

            // Расчет процентов по оставшимся на руках деньгах
            $totalPercentsAmount += $this->getStrategy($amount, $percents, $startDate, $this->claim->returnDate());

            return $totalPercentsAmount;
        }

        return 0.0;
    }

    /**
     * @return Result
     */
    public function calculate(): Result
    {
        $percents = $this->calculatePercents();

        $amount = $this->claim->amount()->amount();

        foreach ($this->claim->additionalAmounts() as $i => $item) {
            if ($item instanceof AdditionalClaimAmount) {
                $amount += $item->amount();
            } else {
                $amount -= $item->amount();
            }
        }

        return new Result($amount, $percents, $this->summary);
    }

    /**
     * Выбор стратегии для расчета процентов
     *
     * @param float $amount
     * @param float $percents
     * @param Carbon $startDate
     * @param Carbon $endDate
     *
     * @return float
     * @throws ClaimPercentsCalculatorException
     */
    protected function getStrategy(float $amount, float $percents, Carbon $startDate, Carbon $endDate): float
    {
        $interval = $this->claim->interval();

        switch ($interval) {
            case Claim::DAILY:
                $strategy = new Daily($amount, $startDate, $endDate, $percents);
                break;
            case Claim::WEEKLY:
                $strategy = new Weekly($amount, $startDate, $endDate, $percents);
                break;
            case Claim::MONTHLY:
                $strategy = new Monthly($amount, $startDate, $endDate, $percents);
                break;
            case Claim::YEARLY:
                $strategy = new Yearly($amount, $startDate, $endDate, $percents);
                break;
            default:

                throw new ClaimPercentsCalculatorException('Strategy not found');

        }

        $total = $strategy->calculate();

        $this->summary[] = new Summary($amount, $percents, $total, $startDate, $endDate);

        return $total;
    }
}