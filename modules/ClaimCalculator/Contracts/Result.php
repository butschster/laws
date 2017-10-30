<?php

namespace Module\ClaimCalculator\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use Module\ClaimCalculator\SummaryCollection;

interface Result extends Arrayable
{
    /**
     * @return float
     */
    public function amount(): float;

    /**
     * @return float
     */
    public function amountWithPercents(): float;

    /**
     * @return float
     */
    public function percents(): float;

    /**
     * @return SummaryCollection
     */
    public function summary(): SummaryCollection;
}