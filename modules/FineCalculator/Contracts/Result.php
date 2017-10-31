<?php

namespace Module\FineCalculator\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
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
     * @return Collection
     */
    public function summary(): Collection;

    /**
     * @return \Module\FineCalculator\IntervalsCollection
     */
    public function intervals(): \Module\FineCalculator\IntervalsCollection;
}