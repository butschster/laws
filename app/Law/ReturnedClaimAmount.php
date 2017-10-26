<?php

namespace App\Law;

use Carbon\Carbon;
use PhpOffice\PhpWord\Element\AbstractContainer;

class ReturnedClaimAmount extends Amount
{
    /**
     * @var Carbon
     */
    protected $returnDate;

    /**
     * @param float $amount
     * @param Carbon $returnDate
     */
    public function __construct(float $amount = 0, Carbon $returnDate)
    {
        parent::__construct($amount);

        $this->returnDate = $returnDate;
    }

    /**
     * @param AbstractContainer $container
     */
    public function insertTo(AbstractContainer $container)
    {
        $textRun = $container->addTextRun();
        $textRun->addText('Возвращено: ', ['bold' => true]);

        parent::insertTo($textRun);
    }

    /**
     * @return bool
     */
    public function hasReturnDate(): bool
    {
        return !is_null($this->returnDate);
    }

    /**
     * @return Carbon
     */
    public function returnDate()
    {
        return $this->returnDate;
    }
}