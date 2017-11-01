<?php

namespace App\Law;

class InterestRate
{
    const MONTHLY = 'monthly';
    const DAILY   = 'daily';
    const YEARLY  = 'yearly';
    const WEEKLY  = 'weekly';

    /**
     * Получение списка доступных периодов
     *
     * @return array
     */
    public static function intervals(): array
    {
        return [self::DAILY, self::WEEKLY, self::MONTHLY, self::YEARLY];
    }

    /**
     * Процентная ставка
     *
     * @var int
     */
    private $percents;

    /**
     * Период начисления
     *
     * @var string
     */
    private $interval;

    /**
     * @param float $percents Процентная ставка
     * @param string $interval Период начисления
     */
    public function __construct(float $percents = 0, string $interval = self::MONTHLY)
    {
        $this->percents = $percents;
        $this->interval = $interval;
    }

    /**
     * Проверка, является ли займ процентным
     *
     * @return bool
     */
    public function hasPercents(): bool
    {
        return $this->percents > 0;
    }

    /**
     * Получение процентной ставки
     *
     * @return float
     */
    public function percents(): float
    {
        return $this->percents;
    }

    /**
     * Получение периода начисления
     *
     * @return string
     */
    public function interval(): string
    {
        return $this->interval;
    }
}