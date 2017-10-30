<?php

namespace Module\ClaimCalculator\Contracts;

interface Strategy
{

    /**
     * @return float
     */
    public function calculate(): float;
}