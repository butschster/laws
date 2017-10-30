<?php

namespace Module\FineCalculator;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class Interval implements Arrayable
{

    /**
     * @var float
     */
    private $rate;

    /**
     * @var Carbon
     */
    private $from;

    /**
     * @var Carbon
     */
    private $to;

    /**
     * @var float
     */
    private $amount;

    /**
     * Interval constructor.
     *
     * @param Carbon $from
     * @param Carbon $to
     * @param float $rate
     * @param float $amount
     */
    public function __construct(Carbon $from, Carbon $to, float $rate, float $amount)
    {
        $this->rate = $rate;
        $this->from = $from;
        $this->to = $to;
        $this->amount = $amount;
    }

    /**
     * @return Carbon
     */
    public function from(): Carbon
    {
        return $this->from;
    }

    /**
     * @return Carbon
     */
    public function to(): Carbon
    {
        return $this->to;
    }

    /**
     * @return float
     */
    public function rate(): float
    {
        return $this->rate;
    }

    /**
     * @return float
     */
    public function amount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return void
     */
    public function sub(float $amount)
    {
        $this->amount -= $amount;
    }

    /**
     * @param float $amount
     *
     * @return void
     */
    public function add(float $amount)
    {
        $this->amount += $amount;
    }

    /**
     * Проверка принадлежности даты периоду действия ставки
     *
     * @param Carbon $date
     *
     * @return bool
     */
    public function contains(Carbon $date): bool
    {
        return $date->between($this->from, $this->to);
    }

    /**
     * @return float
     */
    public function calculate(): float
    {
        return ($this->rate / 365 * $this->to->diffInDays($this->from) * $this->amount) / 100;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'from' => $this->from->toDateString(),
            'to' => $this->to->toDateString(),
            'rate' => $this->rate,
            'amount' => $this->amount,
        ];
    }
}