<?php

namespace App\Law\Claim;

use App\Contracts\Documents\ElementInterface;
use App\Law\Claim;
use PhpOffice\PhpWord\Element\AbstractContainer;

/**
 * Шапка документа
 *
 * @package App\Documents\Elements
 */
class Header implements ElementInterface
{
    /**
     * @var Claim
     */
    private $claim;

    /**
     * @param Claim $claim
     */
    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
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

        $this->claim->court()->insertTo($cell);
        $this->claim->plaintiff()->insertTo($cell);
        $this->claim->respondent()->insertTo($cell);
        $this->claim->amount()->insertTo($cell);
        $this->claim->calculateTax()->insertTo($cell);
    }
}