<?php

namespace App\Law\Calculator\Strategies;

class Weekly extends Strategy
{
    /**
     * @return float
     */
    public function calculate(): float
    {
        $totalFullWeeks = $this->to->diffInWeeks($this->from);

        return
            $this->calculateAmount($totalFullWeeks, $this->percents)
            + $this->calculateAmount(1, $this->getFirstWeekPercents())
            + $this->calculateAmount(1, $this->getLastWeekPercents());
    }

    /**
     * @return float
     */
    public function getFirstWeekPercents(): float
    {
        $lastDay = clone $this->from;

        if ($lastDay->startOfWeek()->eq($this->from)) {
            return 0;
        }

        $totalDays = $lastDay->endOfWeek()->diffInDays($this->from);

        return round(($totalDays / 7) * $this->percents, 2);
    }

    /**
     * @return float
     */
    public function getLastWeekPercents(): float
    {
        $firstDay = clone $this->to;

        if ($firstDay->endOfWeek()->eq($this->to)) {
            return 0;
        }

        $totalDays = $this->to->diffInDays($firstDay->startOfWeek());

        return round(($totalDays / 7) * $this->percents, 2);
    }
}