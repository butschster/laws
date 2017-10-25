<?php

namespace App\Law\Calculator\Strategies;

use Carbon\Carbon;

class Yearly extends Strategy
{
    /**
     * @return float
     */
    public function calculate(): float
    {
        $amount = $this->calculateAmount($this->getFullYears(), $this->percents);
        $amount += $this->calculateAmount(1, $this->getFirstYearMonthsPercents());
        $amount += $this->calculateAmount(1, $this->getFirstYearDaysPercents());
        $amount += $this->calculateAmount(1, $this->getLastYearMonthsPercents());
        $amount += $this->calculateAmount(1, $this->getLastYearDaysPercents());

        return round($amount, 2);
    }

    /**
     * @return int
     */
    public function getFullYears(): int
    {
        $from = clone $this->from;
        $to = clone $this->to;

        if ($from->startOfYear()->eq($this->from)) {
            return $from->diffInYears($to->startOfYear());
        }

        return $from->endOfYear()->diffInYears($to->startOfYear());
    }

    /**
     * Получение кол-ва дней в году
     *
     * @param Carbon $date
     *
     * @return int
     */
    protected function getDaysInYear(Carbon $date)
    {
        return 365 + $date->format('L');
    }

    /**
     * Подсчет процентов в днях неполного месяца последнего года
     *
     * @return float
     */
    protected function getLastYearDaysPercents(): float
    {
        $to = clone $this->to;

        if ($to->endOfMonth()->eq($this->to)) {
            return 0;
        }

        $totalDays = $this->to->diffInDays($to->startOfMonth());

        return ($totalDays / $this->getDaysInYear($this->to)) * $this->percents;
    }

    /**
     * Подсчет процентов в днях неполного месяца первого года
     *
     * @return float
     */
    protected function getFirstYearDaysPercents(): float
    {
        $from = clone $this->from;

        if ($from->startOfMonth()->eq($this->from)) {
            return 0;
        }

        $totalDays = $from->endOfMonth()->diffInDays($this->from);

        return ($totalDays / $this->getDaysInYear($this->from)) * $this->percents;
    }

    /**
     * Подсчет процентов в полных месяцах первого года
     *
     * @return float
     */
    protected function getFirstYearMonthsPercents(): float
    {
        $from = clone $this->from;

        if ($from->startOfMonth()->eq($this->from)) {
            return 0;
        }

        $totalMonths = $from->endOfYear()->diffInMonths($this->from);

        return ($totalMonths / 12) * $this->percents;
    }

    /**
     * Подсчет процентов в полных месяцах последнего года
     *
     * @return float
     */
    protected function getLastYearMonthsPercents(): float
    {
        $to = clone $this->to;

        if ($to->endOfMonth()->eq($this->to)) {
            return 0;
        }

        $totalMonths = $this->to->diffInMonths($to->startOfYear());

        return ($totalMonths / 12) * $this->percents;
    }
}