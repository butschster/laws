<?php

namespace App\Law;

use PhpOffice\PhpWord\Element\AbstractContainer;

class Tax extends Amount
{

    /**
     * @param AbstractContainer $container
     */
    public function insertTo(AbstractContainer $container)
    {
        $textRun = $container->addTextRun();
        $textRun->addText('Государственная пошлина: ', ['bold' => true]);
        $textRun->addText("в соответствие с пп.1 п.1 ст. 333.19 Налогового кодекса РФ, государственная пошлина составляет ");

        parent::insertTo($textRun);
    }
}