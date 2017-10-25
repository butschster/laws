<?php

namespace App\Law\Calculator\Strategies;

class Monthly extends Strategy
{

    /**
     * @return float
     */
    public function calculate(): float
    {
        $totalFullMonths = $this->to->diffInMonths($this->from);

        return
            $this->calculateAmount($totalFullMonths, $this->percents)
            + $this->calculateAmount(1, $this->getFirstMonthPercents())
            + $this->calculateAmount(1, $this->getLastMonthPercents());
    }

    /**
     * @return float
     */
    public function getFirstMonthPercents(): float
    {
        $lastDay = clone $this->from;

        if ($lastDay->startOfMonth()->eq($this->from)) {
            return 0;
        }

        $totalDays = $lastDay->endOfMonth()->diffInDays($this->from);

        return round(($totalDays / $this->from->daysInMonth) * $this->percents, 2);
    }

    /**
     * @return float
     */
    public function getLastMonthPercents(): float
    {
        $firstDay = clone $this->to;

        if ($firstDay->endOfMonth()->eq($this->to)) {
            return 0;
        }

        $totalDays = $this->to->diffInDays($firstDay->startOfMonth());

        return round(($totalDays / $this->to->daysInMonth) * $this->percents, 2);
    }
}