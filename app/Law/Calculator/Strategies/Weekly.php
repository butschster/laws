<?php

namespace App\Law\Calculator\Strategies;

use Carbon\Carbon;

class Weekly extends Strategy
{
    /**
     * @return float
     */
    public function calculate(): float
    {
        $totalFullWeeks = $this->to->diffInWeeks($this->from);

        $amount = $this->calculateAmount($totalFullWeeks, $this->percents);
        $amount += $this->getLastWeekAmount($totalFullWeeks);

        return round($amount, 2);
    }

    /**
     * @param int $totalFullWeeks
     *
     * @return float
     */
    public function getLastWeekAmount(int $totalFullWeeks): float
    {
        $firstDay = clone $this->to;
        $from = clone $this->from;

        if ($firstDay->endOfWeek()->eq($this->to)) {
            return 0;
        }

        $lastWeek = $from->addWeeks($totalFullWeeks);

        if (($days = $lastWeek->diffInDays($this->to)) == 0) {
            return 0;
        }

        $endOfLastWeek = clone $lastWeek;

        $amount = 0;

        if ($endOfLastWeek->endOfWeek()->lt($this->to)) {
            $amount += $this->calculateDaysAmount($endOfLastWeek->endOfWeek(), $lastWeek);
            $amount += $this->calculateDaysAmount($endOfLastWeek->subDay(1), $this->to);
        } else {
            $amount += $this->calculateDaysAmount($lastWeek, $this->to);
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
            return $this->calculateAmount(1, $totalDays / 7 * $this->percents);
        }

        return 0;
    }
}