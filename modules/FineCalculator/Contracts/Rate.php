<?php

namespace Module\FineCalculator\Contracts;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

interface Rate extends Arrayable
{
    /**
     * Дата окончания действия ставки
     *
     * @return Carbon
     */
    public function to(): Carbon;

    /**
     * Дата начала действия ставки
     *
     * @return Carbon
     */
    public function from(): Carbon;

    /**
     * Проверка принадлежности даты периоду действия ставки
     *
     * @param Carbon $date
     *
     * @return bool
     */
    public function contains(Carbon $date): bool;

    /**
     * Размер ключевой ставки
     *
     * @return float
     */
    public function rate(): float;
}