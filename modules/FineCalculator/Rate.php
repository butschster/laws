<?php

namespace Module\FineCalculator;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class Rate implements Arrayable
{

    /**
     * Начало действия ставки
     *
     * @var Carbon
     */
    private $from;

    /**
     * Окончание действия ставки
     *
     * @var Carbon
     */
    private $to;

    /**
     * Ставка
     *
     * @var float
     */
    private $rate;

    /**
     * @param float $rate
     * @param Carbon $from
     * @param Carbon $to
     */
    public function __construct(float $rate, Carbon $from, Carbon $to)
    {
        $this->from = $from;
        $this->to = $to;
        $this->rate = $rate;
    }

    /**
     * @return Carbon
     */
    public function to(): Carbon
    {
        return $this->to;
    }

    /**
     * @return Carbon
     */
    public function from(): Carbon
    {
        return $this->from;
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
    public function rate(): float
    {
        return $this->rate;
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
        ];
    }
}