<?php

namespace Module\ClaimCalculator\Contracts;

interface Calculator
{
    /**
     * @return Result
     */
    public function calculate(): Result;
}