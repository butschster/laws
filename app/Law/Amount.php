<?php

namespace App\Law;

use App\Contracts\Documents\ElementInterface;
use PhpOffice\PhpWord\Element\AbstractContainer;

class Amount implements ElementInterface
{

    /**
     * @var int
     */
    protected $rubles = 0;

    /**
     * @var int
     */
    protected $pennies = 0;

    /**
     * @param float $amount
     */
    public function __construct(float $amount)
    {
        $this->rubles = floor($amount);
        $this->pennies = ($amount - $this->rubles) * 100;

    }

    /**
     * @return int
     */
    public function rubles(): int
    {
        return $this->rubles;
    }

    /**
     * @return int
     */
    public function pennies(): int
    {
        return $this->pennies;
    }

    /**
     * @param AbstractContainer $container
     *
     * @return void
     */
    public function insertTo(AbstractContainer $container)
    {
        $container->addText($this->__toString());
    }

    /**
     * @param int $pennies
     */
    public function setPennies(int $pennies)
    {
        $this->pennies = $pennies;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            "%s руб. %s коп.",
            number_format($this->rubles(), 0, ',', ' '),
            $this->pennies()
        );
    }
}