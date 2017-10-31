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
     * @return static
     */
    public function sortByDate()
    {
        return $this->sortBy(function (Interval $interval) {
            return $interval->from();
        })->values();
    }

    /**
     * @return Collection
     */
    public function summary(): Collection
    {
        $summary = [];

        foreach ($this as $interval) {
            $summary[] = new Summary(
                $interval->amount(),
                $interval->rate(),
                $interval->calculate(),
                $interval->from(),
                $interval->to()
            );
        }

        return collect($summary);
    }
}