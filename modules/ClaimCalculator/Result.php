<?php

namespace Module\ClaimCalculator;

use Module\ClaimCalculator\Contracts\Result as ResultContract;

class Result implements ResultContract
{

    /**
     * @var float
     */
    private $amount;

    /**
     * @var float
     */
    private $percents;

    /**
     * @var SummaryCollection|Summary[]
     */
    private $summary = [];

    /**
     * @param float $amount
     * @param float $percents
     * @param array $summary
     */
    public function __construct(float $amount, float $percents, array $summary)
    {
        $this->amount = $amount;
        $this->percents = $percents;
        $this->summary = new SummaryCollection($summary);
    }

    /**
     * @return float
     */
    public function amount(): float
    {
        return round($this->amount, 2);
    }

    /**
     * @return float
     */
    public function amountWithPercents(): float
    {
        return round($this->amount + $this->percents, 2);
    }

    /**
     * @return float
     */
    public function percents(): float
    {
        return round($this->percents, 2);
    }

    /**
     * @return SummaryCollection
     */
    public function summary(): SummaryCollection
    {
        return $this->summary;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'amount' => $this->amount(),
            'percents' => $this->percents(),
            'amount_with_percents' => $this->amountWithPercents(),
            'summary' => $this->summary->toArray(),
        ];
    }
}