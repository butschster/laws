<?php

namespace App\Law\Claim;

use App\Law\Amount;
use PhpOffice\PhpWord\Element\AbstractContainer;

class ClaimAmount extends Amount
{
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