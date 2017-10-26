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
        $amount += $this->getLastYearPercents();

        return round($amount, 2);
    }

    /**
     * @return int
     */
    public function getFullYears(): int
    {
        return $this->from->diffInYears($this->to);
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
     * Подсчет процентов в полных месяцах последнего года
     *
     * @return float
     */
    protected function getLastYearPercents(): float
    {
        $from = clone $this->from;

        $from->addYears($this->getFullYears());

        $totalFullMonths = $from->diffInMonths($this->to);

        $amount = 0;

        $amount += $this->calculateAmount(1, $totalFullMonths / 12 * $this->percents);

        $from->addMonths($totalFullMonths);
        $totalFullDays = $from->diffInDays($this->to);

        $amount += $this->calculateAmount(1, ($totalFullDays / $this->getDaysInYear($this->to) * $this->percents));

        return $amount;
    }
}