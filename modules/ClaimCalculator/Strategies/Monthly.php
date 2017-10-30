<?php

namespace Module\ClaimCalculator\Strategies;

use Carbon\Carbon;

class Monthly extends Strategy
{

    /**
     * @return float
     */
    public function calculate(): float
    {
        $totalFullMonths = $this->to->diffInMonths($this->from);

        $amount = $this->calculateAmount($totalFullMonths, $this->percents);
        $amount += $this->getLastMonthAmount($totalFullMonths);

        return round($amount, 2);
    }

    /**
     * @param int $totalFullMonths
     *
     * @return float
     */
    public function getLastMonthAmount(int $totalFullMonths): float
    {
        $firstDay = clone $this->to;
        $from = clone $this->from;

        if ($firstDay->endOfMonth()->eq($this->to)) {
            return 0;
        }

        $lastMonth = $from->addMonths($totalFullMonths);

        if (($days = $lastMonth->diffInDays($this->to)) == 0) {
            return 0;
        }

        $endOfLastMonth = clone $lastMonth;

        $amount = 0;


        if ($endOfLastMonth->endOfMonth()->lt($this->to)) {
            $amount += $this->calculateDaysAmount($endOfLastMonth->endOfMonth(), $lastMonth);
            $amount += $this->calculateDaysAmount($endOfLastMonth->subDay(1), $this->to);
        } else {
            $amount += $this->calculateDaysAmount($lastMonth, $this->to);
        }

        return $amount;
    }

    /**
     * @param Carbon $from
     * @param Carbon $to
     *
     * @return float
     */
    protected function calculateDaysAmount(Carbon $from, Carbon $to): float
    {
        $totalDays = $from->diffInDays($to);
        if ($totalDays > 0) {
            return $this->calculateAmount(1, $totalDays / $to->daysInMonth * $this->percents);
        }

        return 0;
    }
}