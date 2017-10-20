<?php

namespace App\Law;

use App\Contracts\Documents\ElementInterface;
use App\Court as CourtModel;
use PhpOffice\PhpWord\Element\AbstractContainer;

class Court implements ElementInterface
{

    /**
     * @var CourtModel
     */
    private $court;

    /**
     * @param CourtModel $court
     */
    public function __construct(CourtModel $court)
    {
        $this->court = $court;
    }

    /**
     * @param AbstractContainer $container
     *
     * @return void
     */
    public function insertTo(AbstractContainer $container)
    {
        $textRun = $container->addTextRun();
        $textRun->addText($this->court->address);
        $textRun->addText(sprintf(' в %s.', $this->court->name), ['bold' => true]);
    }
}