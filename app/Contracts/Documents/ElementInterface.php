<?php

namespace App\Contracts\Documents;

use PhpOffice\PhpWord\Element\AbstractContainer;

interface ElementInterface
{

    /**
     * @param AbstractContainer $container
     *
     * @return void
     */
    public function insertTo(AbstractContainer $container);
}