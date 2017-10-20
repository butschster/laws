<?php

namespace App\Documents\Elements;

use App\Contracts\Documents\ElementInterface;
use App\Court;
use App\Law\Amount;
use App\Law\ClaimAmount;
use App\Law\Plaintiff;
use App\Law\Respondent;
use App\Law\Tax;
use PhpOffice\PhpWord\Element\AbstractContainer;

class Header implements ElementInterface
{

    /**
     * @var \App\Law\Court
     */
    private $court;

    /**
     * @var Plaintiff
     */
    private $plaintiff;

    /**
     * @var Respondent
     */
    private $respondent;

    /**
     * @var Amount
     */
    private $cost;

    /**
     * @var Tax
     */
    private $tax;

    /**
     * @param Court $court
     * @param Plaintiff $plaintiff
     * @param Respondent $respondent
     * @param ClaimAmount $cost
     * @param Tax $tax
     */
    public function __construct(Court $court, Plaintiff $plaintiff, Respondent $respondent, ClaimAmount $cost, Tax $tax)
    {
        $this->court = new \App\Law\Court($court);
        $this->plaintiff = $plaintiff;
        $this->respondent = $respondent;
        $this->cost = $cost;
        $this->tax = $tax;
    }

    /**
     * @param AbstractContainer $container
     *
     * @return mixed
     */
    public function insertTo(AbstractContainer $container)
    {
        $table = $container->addTable([
            'unit' => \PhpOffice\PhpWord\Style\Table::WIDTH_PERCENT,
            'width' => 100 * 50,
        ]);

        $row = $table->addRow();
        $row->addCell(1200);
        $cell = $row->addCell();

        $this->court->insertTo($cell);
        $this->plaintiff->insertTo($cell);
        $this->respondent->insertTo($cell);
        $this->cost->insertTo($cell);
        $this->tax->insertTo($cell);
    }
}