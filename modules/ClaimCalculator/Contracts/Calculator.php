<?php

namespace Module\ClaimCalculator\Contracts;

interface Calculator
{
    /**
     * Расчет процентов
     * 
     * @return Result
     */
    public function calculate(): Result;
}