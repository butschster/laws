<?php

namespace App\Law;

use PhpOffice\PhpWord\Element\AbstractContainer;

class ClaimTax extends Tax
{
    /**
     * @var Plaintiff
     */
    private $plaintiff;

    /**
     * @var Respondent
     */
    private $respondent;

    /**
     * @param int $amount
     * @param Plaintiff $plaintiff
     * @param Respondent $respondent
     */
    public function __construct($amount = 0, Plaintiff $plaintiff, Respondent $respondent)
    {
        $this->plaintiff = $plaintiff;
        $this->respondent = $respondent;

        parent::__construct(
            $this->calculate($amount)
        );
    }

    /**
     * @param float $amount
     *
     * @return float
     */
    protected function calculate(float $amount): float
    {
        if ( !$this->plaintiff->isIndividual() && !$this->respondent->isIndividual()) {
            if ($amount > 2000000) {
                return min(200000, 33000 + ($amount - 2000000) * 0.005);
            } else if ($amount > 1000000) {
                return 23000 + ($amount - 1000000) * 0.01;
            } else if ($amount > 200000) {
                return 7000 + ($amount - 200000) * 0.02;
            } else if ($amount > 100000) {
                return 4000 + ($amount - 100000) * 0.03;
            } else {
                return max(2000, $amount * 0.04);
            }
        } else {
            if ($amount > 1000000) {
                return min(60000, 13200 + ($amount - 1000000) * 0.005);
            } else if ($amount > 200000) {
                return 5200 + ($amount - 200000) * 0.01;
            } else if ($amount > 100000) {
                return 3200 + ($amount - 100000) * 0.02;
            } else if ($amount > 20000) {
                return 800 + ($amount - 20000) * 0.03;
            } else {
                return max(400, $amount * 0.04);
            }
        }
    }
}