<?php

namespace App\Law;

use Carbon\Carbon;
use PhpOffice\PhpWord\Element\AbstractContainer;

class AdditionalClaimAmount extends Amount
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
        $textRun->addText('Дополнительно взято: ', ['bold' => true]);

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