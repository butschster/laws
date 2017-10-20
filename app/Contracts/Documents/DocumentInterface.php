<?php

namespace App\Contracts\Documents;

interface DocumentInterface
{
    /**
     * @param ElementInterface $element
     */
    public function addElement(ElementInterface $element);
}