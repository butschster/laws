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
            array_get($data, 'type', self::TYPE_INDIVIDUAL),
            array_only($data, 'address', 'phone', 'fact_address', 'email', 'fax', 'ogrn')
        );
    }

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type;

    /**
     * @var array
     */
    private $contacts;

    /**
     * @param string $name ФИО
     * @param string $type Тип (физ лицо, Юр лицо, ИП)
     * @param array $contacts
     */
    public function __construct(string $name, string $type = self::TYPE_INDIVIDUAL, array $contacts)
    {
        $this->name = $name;
        $this->type = $type;
        $this->contacts = $contacts;
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
        return array_get($this->contacts, 'address');
    }

    /**
     * @return string
     */
    public function factAddress(): string
    {
        $factAddress = array_get($this->contacts, 'factAddress');

        return $factAddress ?: $this->address();
    }

    /**
     * @return string
     */
    public function phone(): string
    {
        return array_get($this->contacts, 'phone');
    }

    /**
     * @return string
     */
    public function ogrn(): string
    {
        return array_get($this->contacts, 'ogrn');
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return array_get($this->contacts, 'email');
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
        if ($this->isIndividual()) {
            $text = '%s, место жительства: %s';
        } else {
            $text = '%s, место нахождения: %s';

            if ($this->isIndividualBusiness()) {
                $text .= sprintf(', ОГРНИП: %s', $this->ogrn());
            } else {
                $text .= sprintf(', ОГРН: %s', $this->ogrn());
            }
        }

        $phone = $this->phone();
        if ( !empty($phone)) {
            $text .= sprintf(', контактный телефон: %s', $phone);
        }

        $email = $this->email();
        if ( !empty($email)) {
            $text .= sprintf(', электронная почта: %s', $email);
        }

        $factAddress = $this->factAddress();
        if ( !empty($factAddress)) {
            $text .= sprintf(', адрес для корреспонденции: %s', $factAddress);
        }

        $text .= '.';

        return sprintf(
            $text,
            $this->fullName(),
            $this->address()
        );
    }
}