<?php

namespace App\Law;

use PhpOffice\PhpWord\Element\AbstractContainer;

class Plaintiff extends User
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