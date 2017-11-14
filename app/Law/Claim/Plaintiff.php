<?php

namespace App\Law\Claim;

use App\Court;
use App\Law\Person;
use PhpOffice\PhpWord\Element\AbstractContainer;

class Plaintiff extends Person
{
    /**
     * @var string
     */
    private $courtType;

    /**
     * @param string $type
     */
    public function setCourtType(string $type)
    {
        $this->courtType = $type;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        if ($this->courtType == Court::TYPE_MIR) {
            return 'Взыскатель»';
        }

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