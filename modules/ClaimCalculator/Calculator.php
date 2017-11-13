<?php

namespace Module\ClaimCalculator;

use App\Law\Claim\AdditionalAmounts;
use App\Law\Claim\AdditionalAmount;
use App\Law\InterestRate;
use App\Law\Claim\ReturnedAmount;
use Carbon\Carbon;
use Module\ClaimCalculator\Contracts\Calculator as CalculatorContract;
use Module\ClaimCalculator\Contracts\Result as ResultContract;
use Module\ClaimCalculator\Contracts\Strategy;
use Module\ClaimCalculator\Exceptions\ClaimPercentsCalculator as ClaimPercentsCalculatorException;

class Calculator implements CalculatorContract
{
    /**
     * @var array
     */
    private $summary = [];

    /**
     * @var float
     */
    private $amount;

    /**
     * @var InterestRate
     */
    private $rate;

    /**
     * @var Carbon
     */
    private $from;

    /**
     * @var Carbon
     */
    private $to;

    /**
     * @var AdditionalAmounts
     */
    private $amounts;

    /**
     * @param float $amount
     * @param InterestRate $rate
     * @param Carbon $from
     * @param Carbon $to
     * @param AdditionalAmounts|null $amounts
     */
    public function __construct(float $amount, InterestRate $rate, Carbon $from, Carbon $to, AdditionalAmounts $amounts = null)
    {
        $this->amount = $amount;
        $this->rate = $rate;
        $this->from = $from;
        $this->to = $to;
        $this->amounts = $amounts;
    }

    /**
     * Получение суммы возвращаемой по процентам
     *
     * @return float
     */
    protected function calculatePercents(): float
    {
        $percents = $this->rate->percents();

        if ($percents > 0) {
            $startDate = $this->from;
            $amount = $this->amount;

            $totalPercentsAmount = 0;

            if ($this->amounts) {
                $additionalAmounts = $this->amounts;

                // Если у нас есть доп займы или погашения, то сначала считаем проценты до их наступления
                if ($additionalAmounts->count() > 0 && ($firstClaim = $additionalAmounts->first()) instanceof AdditionalAmount) {
                    $totalPercentsAmount += $this->getStrategy($amount, $percents, $startDate, $firstClaim->date());
                    $startDate = $firstClaim->date();
                }

                $lastClaim = null;

                // Расчитываем проценты с учетом отданных и полученых денег в течение срока
                foreach ($additionalAmounts as $i => $item) {
                    // Если это дополнительный займ
                    if ($item instanceof AdditionalAmount) {
                        if ($lastClaim instanceof ReturnedAmount) {
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
            }

            // Расчет процентов по оставшимся на руках деньгах
            $totalPercentsAmount += $this->getStrategy($amount, $percents, $startDate, $this->to);

            return $totalPercentsAmount;
        }

        return 0.0;
    }

    /**
     * @return ResultContract
     */
    public function calculate(): ResultContract
    {
        $amount = $this->amount;

        if ($this->amounts) {
            foreach ($this->amounts as $i => $item) {
                if ($item instanceof AdditionalAmount) {
                    $amount += $item->amount();
                } else {
                    $amount -= $item->amount();
                }
            }
        }

        return new Result($amount, $this->calculatePercents(), $this->summary);
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
        $strategyClass = $this->makeStrategyClass();

        if ( !class_exists($strategyClass)) {
            throw new ClaimPercentsCalculatorException("Strategy class [{$strategyClass}] not found");
        }

        /** @var Strategy $strategy */
        $strategy = new $strategyClass($amount, $startDate, $endDate, $percents);

        $total = $strategy->calculate();

        $this->summary[] = new Summary($amount, $percents, $total, $startDate, $endDate);

        return $total;
    }

    /**
     * @return string
     */
    protected function makeStrategyClass(): string
    {
        $interval = $this->rate->interval();
        return 'Module\\ClaimCalculator\\Strategies\\'.ucfirst($interval);
    }
}