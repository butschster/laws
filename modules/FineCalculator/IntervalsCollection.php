<?php

namespace Module\FineCalculator;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class IntervalsCollection extends Collection
{

    /**
     * @param null $callback
     *
     * @return float
     */
    public function sum($callback = null)
    {
        $amount = parent::sum($callback ?: function (Interval $interval) {
            return $interval->calculate();
        });

        return round($amount, 2);
    }

    /**
     * @param Carbon $date
     *
     * @return static
     */
    public function containsDate(Carbon $date)
    {
        return $this->filter(function (Interval $interval) use ($date) {
            return $interval->contains($date);
        });
    }

    /**
     * @param Carbon $date
     * @param float $amount
     */
    public function subAmountFromDate(Carbon $date, float $amount)
    {
        $this
            ->filter(function (Interval $interval) use ($date) {
                return $interval->contains($date);
            })->each(function (Interval $interval) use ($amount) {
                $interval->sub($amount);
            });
    }
}