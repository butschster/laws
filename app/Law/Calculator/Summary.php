<?php

namespace App\Law\Calculator;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class Summary implements Arrayable
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
    private $calculatedPercents;

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
     * @param float $percents
     * @param float $calculatedPercents
     * @param Carbon $startDate
     * @param Carbon $endDate
     */
    public function __construct(float $amount, float $percents, float $calculatedPercents, Carbon $startDate, Carbon $endDate)
    {
        $this->amount = $amount;
        $this->percents = $percents;
        $this->calculatedPercents = $calculatedPercents;
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
            'amount' => $this->amount,
            'percents' => $this->percents,
            'calculated_percents' => $this->calculatedPercents,
            'start_date' => $this->startDate->toDateString(),
            'end_date' => $this->endDate->toDateString(),
        ];
    }
}