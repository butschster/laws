<?php

namespace App\Law;

use App\Contracts\Documents\ElementInterface;
use App\Contracts\Law\Person as PersonContract;
use App\Documents\Elements\UserSign;
use PhpOffice\PhpWord\Element\AbstractContainer;

class Person implements PersonContract, ElementInterface
{
    const TYPE_INDIVIDUAL = 1;
    const TYPE_INDIVIDUAL_BUSINESS = 2;
    const TYPE_LEGAL_ENTITY = 3;

    /**
     * Список типов
     *
     * @return array
     */
    public static function types(): array
    {
        return [
            self::TYPE_INDIVIDUAL, self::TYPE_INDIVIDUAL_BUSINESS, self::TYPE_LEGAL_ENTITY
        ];
    }

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
            array_get($data, 'fact_address'),
            array_get($data, 'type', self::TYPE_INDIVIDUAL)
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
     * @var string
     */
    private $type;

    /**
     * @param string $name ФИО
     * @param string $address Юридический адрес / Адрес прописки
     * @param string $phone Контактный телефон
     * @param string|null $factAddress Фактический адрес / Адрес прожимания
     * @param string $type Тип (физ лицо, Юр лицо, ИП)
     */
    public function __construct(string $name, string $address, string $phone = null, string $factAddress = null, string $type = self::TYPE_INDIVIDUAL)
    {
        $this->name = $name;
        $this->address = $address;
        $this->phone = $phone;
        $this->factAddress = $factAddress;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Статус персоны: Физ лицо
     *
     * @return bool
     */
    public function isIndividual(): bool
    {
        return $this->type == self::TYPE_INDIVIDUAL;
    }

    /**
     * Статус персоны: ИП
     *
     * @return bool
     */
    public function isIndividualBusiness(): bool
    {
        return $this->type == self::TYPE_INDIVIDUAL_BUSINESS;
    }

    /**
     * Статус персоны: Юр лицо
     *
     * @return bool
     */
    public function isLegalEntity(): bool
    {
        return $this->type == self::TYPE_LEGAL_ENTITY;
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
    public function factAddress(): string
    {
        return $this->factAddress ?: $this->address;
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
     * @return UserSign
     */
    public function sign(): UserSign
    {
        return new UserSign($this);
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