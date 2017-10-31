<?php

namespace Module\ClaimCalculator\Contracts;

interface Strategy
{

    /**
     * Расчет процентов
     *
     * @return float
     */
    public function calculate(): float;
}