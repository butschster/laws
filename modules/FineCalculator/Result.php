<?php

namespace Module\FineCalculator;

use Illuminate\Support\Collection;
use Module\FineCalculator\Contracts\Result as ResultContract;

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
     * @var array|\Illuminate\Support\Collection
     */
    private $summary = [];

    /**
     * @var \Module\FineCalculator\IntervalsCollection
     */
    private $intervals;

    /**
     * @param float $amount
     * @param \Module\FineCalculator\IntervalsCollection $intervals
     */
    public function __construct(float $amount, IntervalsCollection $intervals)
    {
        $percents = $intervals->sum();
        $this->amount = $amount + $percents;
        $this->percents = $percents;
        $this->intervals = $intervals;
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
     * @return Collection
     */
    public function summary(): Collection
    {
        return $this->intervals()->summary();
    }

    /**
     * @return \Module\FineCalculator\IntervalsCollection
     */
    public function intervals(): \Module\FineCalculator\IntervalsCollection
    {
        return $this->intervals;
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