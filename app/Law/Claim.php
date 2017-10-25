<?php

namespace App\Law;

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
     * @param float $amount
     * @param Carbon $borrowingDate
     * @param Carbon $returnDate
     * @param int $percents
     * @param string $interval
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
     * @return ClaimAmount
     */
    public function amount(): ClaimAmount
    {
        return $this->amount;
    }

    /**
     * @param Carbon $date
     * @param float $amount
     *
     * @return $this
     */
    public function addReturnedMoney(Carbon $date, float $amount)
    {
        $this->returnedAmount[] = new ReturnedClaimAmount($amount, $date);

        return $this;
    }

    /**
     * @return ClaimAmount
     */
    public function residualAmount()
    {
        $current = clone $this->amount;

        foreach ($this->returnedAmounts() as $amount) {
            $current->sub($amount);
        }

        return $current;
    }

    /**
     * @return array|ReturnedClaimAmount[]
     */
    public function returnedAmounts(): array
    {
        return $this->returnedAmount;
    }

    /**
     * @return bool
     */
    public function hasReturnedAmounts(): bool
    {
        return count($this->returnedAmount) > 0;
    }


    /**
     * @return bool
     */
    public function hasPercents(): bool
    {
        return $this->percents() > 0;
    }

    /**
     * @return int
     */
    public function percents(): int
    {
        return $this->percents;
    }

    /**
     * @return Carbon
     */
    public function borrowingDate(): Carbon
    {
        return $this->borrowingDate;
    }

    /**
     * @return Carbon
     */
    public function returnDate(): Carbon
    {
        return $this->returnDate;
    }

    /**
     * @return string
     */
    public function interval(): string
    {
        return $this->interval;
    }
}