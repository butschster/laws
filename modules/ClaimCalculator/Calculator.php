<?php

namespace Module\ClaimCalculator;

use Module\ClaimCalculator\Contracts\Calculator as CalculatorContract;
use Module\ClaimCalculator\Contracts\Result as ResultContract;
use Module\ClaimCalculator\Contracts\Strategy;
use Module\ClaimCalculator\Exceptions\ClaimPercentsCalculator as ClaimPercentsCalculatorException;
use App\Law\AdditionalClaimAmount;
use App\Law\Claim;
use App\Law\ReturnedClaimAmount;
use Carbon\Carbon;

class Calculator implements CalculatorContract
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
     * @return ResultContract
     */
    public function calculate(): ResultContract
    {
        $amount = $this->claim->amount()->amount();

        foreach ($this->claim->additionalAmounts() as $i => $item) {
            if ($item instanceof AdditionalClaimAmount) {
                $amount += $item->amount();
            } else {
                $amount -= $item->amount();
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
        return 'Module\\ClaimCalculator\\Strategies\\'.ucfirst($this->claim->interval());
    }
}