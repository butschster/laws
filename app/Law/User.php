<?php

namespace App\Law;

use App\Contracts\Documents\ElementInterface;
use App\Contracts\Law\UserInterface;
use PhpOffice\PhpWord\Element\AbstractContainer;

class User implements UserInterface, ElementInterface
{
    /**
     * @param array $data
     *
     * @return static
     */
    public static function fromArray(array $data)
    {
        return new static(
            array_get($data, 'name'),
            array_get($data, 'address'),
            array_get($data, 'phone'),
            array_get($data, 'fact_address')
        );
    }

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
    private $factAddress;

    /**
     * @param string $name
     * @param string $address
     * @param string $phone
     * @param string|null $factAddress
     */
    public function __construct(string $name, string $address, string $phone = null, string $factAddress = null)
    {
        $this->name = $name;
        $this->address = $address;
        $this->phone = $phone;
        $this->factAddress = $factAddress;
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

        $factAddress = $this->factAddress;
        if ( !empty($factAddress)) {
            $text .= ', адрес для корреспонденции: %s';
        }

        $text .= '.';

        return sprintf(
            $text,
            $this->fullName(),
            $this->address(),
            $phone,
            $factAddress
        );
    }
}