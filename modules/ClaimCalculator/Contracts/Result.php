<?php

namespace Module\ClaimCalculator\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Module\ClaimCalculator\SummaryCollection;

interface Result extends Arrayable
{
    /**
     * Сумма займа
     *
     * @return float
     */
    public function amount(): float;

    /**
     * Сумма займа с учетом процентов
     *
     * @return float
     */
    public function amountWithPercents(): float;

    /**
     * Сумма процентов по займу
     *
     * @return float
     */
    public function percents(): float;

    /**
     * Сводка по займу
     *
     * @return SummaryCollection
     */
    public function summary(): SummaryCollection;
}