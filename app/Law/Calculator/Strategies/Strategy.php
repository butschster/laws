<?php

namespace App\Law\Calculator\Strategies;

use App\Contracts\Law\Calculator\Strategy as StrategyContract;
use Carbon\Carbon;

abstract class Strategy implements StrategyContract
{
    /**
     * @var float
     */
    protected $amount;

    /**
     * @var Carbon
     */
    protected $from;

    /**
     * @var Carbon
     */
    protected $to;

    /**
     * @var float
     */
    protected $percents;

    /**
     * Monthly constructor.
     *
     * @param float $amount
     * @param Carbon $from
     * @param Carbon $to
     * @param float $percents
     */
    public function __construct(float $amount, Carbon $from, Carbon $to, float $percents)
    {
        $this->amount = $amount;
        $this->from = $from;
        $this->to = $to;
        $this->percents = $percents;
    }

    /**
     * @param int $interval
     * @param float $percents
     *
     * @return float|int
     */
    protected function calculateAmount(int $interval, float $percents): float
    {
        return ($interval * $this->amount * $percents) / 100;
    }
}