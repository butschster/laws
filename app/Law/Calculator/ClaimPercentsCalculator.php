<?php

namespace App\Law\Calculator;

use App\Contracts\Law\Calculator\Strategy;
use App\Exceptions\ClaimPercentsCalculator as ClaimPercentsCalculatorException;
use App\Law\Calculator\Strategies\Daily;
use App\Law\Calculator\Strategies\Monthly;
use App\Law\Calculator\Strategies\Weekly;
use App\Law\Calculator\Strategies\Yearly;
use App\Law\Claim;
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
            $endDate = $this->claim->returnDate();
            $amount = $this->claim->amount()->amount();

            $totalPercentsAmount = 0;

            // Расчитываем проценты с учетом отданных в течение срока денег
            foreach ($this->claim->returnedAmounts() as $return) {
                $totalPercentsAmount += $this->getStrategy($amount, $percents, $startDate, $return->returnDate())->calculate();

                $amount -= $return->amount();
                $startDate = $return->returnDate();
            }

            // Расчет процентов по оставшимся на руках деньгах
            $totalPercentsAmount += $this->getStrategy($amount, $percents, $startDate, $endDate)->calculate();

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
        return $this->percentsAmount() + $this->claim->amount()->amount();
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