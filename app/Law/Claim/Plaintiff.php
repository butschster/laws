<?php

namespace App\Law\Claim;

use App\Law\Person;
use PhpOffice\PhpWord\Element\AbstractContainer;

class Plaintiff extends Person
{
    /**
     * @return string
     */
    public function title(): string
    {
        return 'Истец';
    }

    /**
     * @param AbstractContainer $container
     *
     * @return void
     */
    public function insertTo(AbstractContainer $container)
    {
        $textRun = $container->addTextRun();

        $textRun->addText($this->title().': ', ['bold' => true]);

        parent::insertTo($textRun);
    }
}