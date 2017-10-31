<?php

namespace App\Documents\Elements;

use App\Contracts\Documents\ElementInterface;
use App\Law\Person;
use App\Law\User;
use PhpOffice\PhpWord\Element\AbstractContainer;

/**
 * Подпись
 *
 * @package App\Documents\Elements
 */
class UserSign implements ElementInterface
{

    /**
     * @var Person
     */
    private $person;

    /**
     * UserSign constructor.
     *
     * @param Person $person
     */
    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    /**
     * @param AbstractContainer $container
     *
     * @return void
     */
    public function insertTo(AbstractContainer $container)
    {
        $container->addText(now()->format('«d» F Y г.'));

        $container->addText(sprintf(
            '%s _______________________________ %s',
            $this->person->title(),
            $this->person->shortName()
        ), ['bold' => true]);
    }
}