<?php

namespace App\Law\Calculator\Strategies;

class Daily extends Strategy
{
    /**
     * @return float
     */
    public function calculate(): float
    {
        return $this->calculateAmount($this->to->diffInDays($this->from), $this->percents);
    }
}