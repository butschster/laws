<?php

namespace Module\FineCalculator;

use Carbon\Carbon;
use Module\FineCalculator\Contracts\Summary as SummaryContract;

class Summary implements SummaryContract
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
     * @var float
     */
    private $rate;

    /**
     * @var Carbon
     */
    private $startDate;

    /**
     * @var Carbon
     */
    private $endDate;

    /**
     * @param float $amount
     * @param float $rate
     * @param float $percents
     * @param Carbon $startDate
     * @param Carbon $endDate
     */
    public function __construct(float $amount, float $rate, float $percents, Carbon $startDate, Carbon $endDate)
    {
        $this->amount = $amount;
        $this->rate = $rate;
        $this->percents = $percents;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'amount' => round($this->amount, 2),
            'rate' => $this->rate,
            'percents' => round($this->percents, 2),
            'from' => $this->startDate->toDateString(),
            'to' => $this->endDate->toDateString(),
            'days' => $this->endDate->diffInDays($this->startDate) + 1
        ];
    }
}