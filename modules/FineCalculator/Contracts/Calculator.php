<?php

namespace Module\FineCalculator\Contracts;

interface Calculator
{
    /**
     * Расчет процентов по статье 395
     *
     * @return Result
     */
    public function calculate(): Result;
}