<?php

namespace App\Law;

use App\Contracts\Documents\ElementInterface;
use App\Contracts\Law\UserInterface;
use PhpOffice\PhpWord\Element\AbstractContainer;

class User implements UserInterface, ElementInterface
{

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $corespondAddress;

    /**
     * @param string $name
     * @param string $address
     * @param string $phone
     * @param string|null $corespondAddress
     */
    public function __construct(string $name, string $address, string $phone = null, string $corespondAddress = null)
    {
        $this->name = $name;
        $this->address = $address;
        $this->phone = $phone;
        $this->corespondAddress = $corespondAddress;
    }

    /**
     * @return string
     */
    public function fullName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function shortName(): string
    {
        return $this->fullName();
    }

    /**
     * @return string
     */
    public function address(): string
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function phone(): string
    {
        return $this->phone;
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
    public function title(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $text = '%s, место жительства: %s';
        $phone = $this->phone();
        if ( !empty($phone)) {
            $text .= ', контактный телефон: %s';
        }

        $corespondAddress = $this->corespondAddress;
        if ( !empty($corespondAddress)) {
            $text .= ', адрес для корреспонденции: %s';
        }

        $text .= '.';

        return sprintf(
            $text,
            $this->fullName(),
            $this->address(),
            $phone,
            $corespondAddress
        );
    }
}