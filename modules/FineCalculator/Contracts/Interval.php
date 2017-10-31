<?php

namespace Module\FineCalculator\Contracts;

use App\Law\Amount;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

interface Interval extends Arrayable
{

    /**
     * Дата начала интервала
     *
     * @return Carbon
     */
    public function from(): Carbon;

    /**
     * Дата конца интервала
     *
     * @return Carbon
     */
    public function to(): Carbon;

    /**
     * Ключевая ставка
     *
     * @return float
     */
    public function rate(): float;

    /**
     * Сумма
     *
     * @return float
     */
    public function amount(): float;

    /**
     * Учет дополнительных сумм в интервале
     *
     * @param Amount $amount
     *
     * @return void
     */
    public function consider(Amount $amount);

    /**
     * Проверка принадлежности даты периоду действия ставки
     *
     * @param Carbon $date
     *
     * @return bool
     */
    public function contains(Carbon $date): bool;

    /**
     * Расчет суммы в интервал
     *
     * @return float
     */
    public function calculate(): float;
}