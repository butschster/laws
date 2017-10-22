<?php

namespace App\Law;

use App\Contracts\Documents\ElementInterface;
use PhpOffice\PhpWord\Element\AbstractContainer;

class Amount implements ElementInterface
{
    /**
     * Сумма заёма
     *
     * @var float
     */
    protected $amount = 0;

    /**
     * Всего рублей
     *
     * @var int
     */
    protected $rubles = 0;

    /**
     * Всего копеек
     *
     * @var int
     */
    protected $pennies = 0;

    /**
     * Сумма заема
     *
     * @param float $amount
     */
    public function __construct(float $amount = 0)
    {
        $this->setAmount($amount);
    }

    /**
     * Получение суммы заема
     *
     * @return float
     */
    public function amount(): float
    {
        return $this->amount;
    }

    /**
     * Получение кол-ва рублей
     *
     * @return int
     */
    public function rubles(): int
    {
        return $this->rubles;
    }

    /**
     * Получение кол-ва копеек
     *
     * @return int
     */
    public function pennies(): int
    {
        return $this->pennies;
    }

    /**
     * Вычинатине денег
     *
     * @param Amount $amount
     *
     * @return Amount
     */
    public function sub(Amount $amount)
    {
        $amount = $this->amount() - $amount->amount();

        return $this->setAmount($amount);
    }

    /**
     * Добавление денег
     *
     * @param Amount $amount
     *
     * @return Amount
     */
    public function add(Amount $amount)
    {
        $amount = $this->amount() + $amount->amount();

        return $this->setAmount($amount);
    }

    /**
     * @param float $amount
     *
     * @return $this
     */
    protected function setAmount(float $amount)
    {
        $this->amount = $amount;
        $this->rubles = floor($amount);
        $this->pennies = ($amount - $this->rubles) * 100;

        return $this;
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