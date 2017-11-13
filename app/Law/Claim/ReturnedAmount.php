<?php

namespace App\Law\Claim;

use App\Law\Amount;
use Carbon\Carbon;
use PhpOffice\PhpWord\Element\AbstractContainer;

class ReturnedAmount extends Amount
{
    /**
     * @var Carbon
     */
    protected $date;

    /**
     * @param float $amount
     * @param Carbon $date
     */
    public function __construct(float $amount = 0, Carbon $date)
    {
        parent::__construct($amount);

        $this->date = $date;
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
     * @return Carbon
     */
    public function date()
    {
        return $this->date;
    }
}