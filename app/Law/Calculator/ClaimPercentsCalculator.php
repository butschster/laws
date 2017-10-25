<?php

namespace App\Law\Calculator;

use App\Contracts\Law\Calculator\Strategy;
use App\Exceptions\ClaimPercentsCalculator as ClaimPercentsCalculatorException;
use App\Law\Calculator\Strategies\Daily;
use App\Law\Calculator\Strategies\Monthly;
use App\Law\Calculator\Strategies\Weekly;
use App\Law\Calculator\Strategies\Yearly;
use App\Law\Claim;

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
     * @return float
     */
    public function percentsAmount(): float
    {
        $percents = $this->claim->percents();

        if ($percents > 0) {
            return $this->getStrategy($percents)->calculate();
        }

        return 0.0;
    }

    /**
     * @return float
     */
    public function totalAmount(): float
    {
        return $this->percentsAmount() + $this->claim->amount()->amount();
    }

    /**
     * @param float $percents
     *
     * @return Strategy
     * @throws ClaimPercentsCalculatorException
     */
    protected function getStrategy(float $percents): Strategy
    {
        $startDate = $this->claim->borrowingDate();
        $endDate = $this->claim->returnDate();
        $interval = $this->claim->interval();
        $amount = $this->claim->amount()->amount();

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