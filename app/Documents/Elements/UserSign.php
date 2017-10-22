<?php

namespace App\Documents\Elements;

use App\Contracts\Documents\ElementInterface;
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
     * @var User
     */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
            $this->user->title(),
            $this->user->shortName()
        ), ['bold' => true]);
    }
}