<?php

namespace App\Law;

use App\Law\Calculator\ClaimPercentsCalculator;
use Carbon\Carbon;

/**
 * Займ
 *
 * @package App\Law
 */
class Claim
{
    const MONTHLY = 'monthly';
    const DAILY = 'daily';
    const YEARLY = 'yearly';
    const WEEKLY = 'weekly';

    /**
     * @var ClaimAmount
     */
    private $amount;

    /**
     * @var Carbon
     */
    private $borrowingDate;

    /**
     * @var Carbon
     */
    private $returnDate;

    /**
     * @var int
     */
    private $percents;

    /**
     * @var string
     */
    private $interval;

    /**
     * @var array|ReturnedClaimAmount[]
     */
    private $returnedAmount = [];

    /**
     * @param float $amount Сумма займа
     * @param Carbon $borrowingDate Дата выдачи
     * @param Carbon $returnDate Дата возврата
     * @param int $percents Процент
     * @param string $interval Период начисления
     */
    public function __construct(float $amount = 0, Carbon $borrowingDate, Carbon $returnDate, int $percents = 0, string $interval = self::MONTHLY)
    {
        $this->amount = new ClaimAmount($amount);

        $this->borrowingDate = $borrowingDate;
        $this->returnDate = $returnDate;
        $this->percents = $percents;
        $this->interval = $interval;
    }

    /**
     * Получение суммы займа
     *
     * @return ClaimAmount
     */
    public function amount(): ClaimAmount
    {
        return $this->amount;
    }

    /**
     * Добавление факта возврата денег
     *
     * @param Carbon $date Дата возврата
     * @param float $amount Сумма
     *
     * @return $this
     */
    public function addReturnedMoney(Carbon $date, float $amount)
    {
        $this->returnedAmount[] = new ReturnedClaimAmount($amount, $date);

        return $this;
    }

    /**
     * Получение списка фактов возвращения денег
     *
     * @return array|ReturnedClaimAmount[]
     */
    public function returnedAmounts(): array
    {
        return $this->returnedAmount;
    }

    /**
     * Проверка, есть ли возвращенные деньгие
     *
     * @return bool
     */
    public function hasReturnedAmounts(): bool
    {
        return count($this->returnedAmount) > 0;
    }

    /**
     * Проверка, является ли займ процентным
     *
     * @return bool
     */
    public function hasPercents(): bool
    {
        return $this->percents() > 0;
    }

    /**
     * Получение процентной ставки
     *
     * @return int
     */
    public function percents(): int
    {
        return $this->percents;
    }

    /**
     * Получение даты выдачи займа
     *
     * @return Carbon
     */
    public function borrowingDate(): Carbon
    {
        return $this->borrowingDate;
    }

    /**
     * Получение даты возврата
     *
     * @return Carbon
     */
    public function returnDate(): Carbon
    {
        return $this->returnDate;
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

    /**
     * Получение суммы для возврата с учетом процентов
     *
     * @return float
     */
    public function calculateReturnAmount(): float
    {
        return $this->getCalculator()->totalAmount();
    }

    /**
     * Получение суммы возвращаемой по процентам
     *
     * @return float
     */
    public function calculateReturnPercentsAmount(): float
    {
        return $this->getCalculator()->percentsAmount();
    }

    protected function getCalculator(): ClaimPercentsCalculator
    {
        return new ClaimPercentsCalculator($this);
    }
}