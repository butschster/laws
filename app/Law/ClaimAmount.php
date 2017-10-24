<?php

namespace App\Law;

use App\Law\ReturnedClaimAmount;
use Carbon\Carbon;
use PhpOffice\PhpWord\Element\AbstractContainer;

class ClaimAmount extends Amount
{
    /**
     * Процентная ставка
     *
     * @var int
     */
    private $percents = 0;

    /**
     * @var Carbon
     */
    private $borrowingDate;

    /**
     * @var Carbon
     */
    private $returnDate;

    /**
     * @var array|ReturnedClaimAmount[]
     */
    private $returnedAmount = [];

    /**
     * @param float $amount Сумма заёма
     * @param Carbon $borrowingDate Дата взятия
     * @param Carbon $returnDate Дата возврата
     * @param int $percents Процентная ставка
     * @param string $interval
     */
    public function __construct(float $amount = 0, Carbon $borrowingDate, Carbon $returnDate, int $percents = 0, string $interval = 'monthly')
    {
        parent::__construct($amount);

        $this->percents = $percents;
        $this->borrowingDate = $borrowingDate;
        $this->returnDate = $returnDate;
        $this->amount = $amount;
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
        $current = clone $this;

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
     * @param AbstractContainer $container
     */
    public function insertTo(AbstractContainer $container)
    {
        $textRun = $container->addTextRun();
        $textRun->addText('Цена иска: ', ['bold' => true]);

        parent::insertTo($textRun);
    }
}