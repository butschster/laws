<?php

namespace App\Law\Claim;

use App\Documents\SimpleDocument;
use App\Law\Claim;

class ClaimDocument extends SimpleDocument
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
        parent::__construct();

        $this->claim = $claim;
    }

    public function generate()
    {
        $this->addElement(new \App\Law\Claim\Header($this->claim));

        $this->addTextBreak();

        $this->addElement($this->makeTitle());

        $this->addElement(
            new \App\Documents\Elements\SimplePlaintText(
                $this->claim
            )
        );

        $this->addTextBreak(2);

        $this->addElement($this->claim->plaintiff()->sign());
    }

    /**
     * @return \App\Documents\Elements\Title
     */
    protected function makeTitle(): \App\Documents\Elements\Title
    {
        $title = new \App\Documents\Elements\Title('ИСКОВОЕ ЗАЯВЛЕНИЕ', 'о взыскании денежных средств.');
        if ($this->claim->isMir()) {
            $title = new \App\Documents\Elements\Title('ЗАЯВЛЕНИЕ', 'о выдаче судебного приказа.');
        }

        return $title;
    }
}