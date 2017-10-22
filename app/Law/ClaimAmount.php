<?php

namespace App\Law;

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
     * @param float $amount Сумма заёма
     * @param int $percents Процентная ставка
     */
    public function __construct(float $amount = 0, int $percents = 0)
    {
        parent::__construct($amount);
        $this->percents = $percents;
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