<?php

namespace App\Documents\Elements;

class NumberedList extends BulletList
{
    /**
     * @param array $elements
     * @param int $depth
     */
    public function __construct(array $elements, int $depth = 0, int $style = \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER)
    {
        parent::__construct($elements, $depth, $style);
    }
}