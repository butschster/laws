<?php

namespace App\Documents\Elements;

use App\Contracts\Documents\ElementInterface;
use App\Law\User;
use PhpOffice\PhpWord\Element\AbstractContainer;

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
        $container->addText(now()->format('d G Y г.'), null, [
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END,
        ]);
        $container->addText(sprintf(
            '%s _________________ %s',
            $this->user->title(),
            $this->user->shortName()
        ), null, [
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END,
        ]);
    }
}