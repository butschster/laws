<?php

namespace App\Documents\Elements;
/**
 * Нумерованный список
 *
 * @package App\Documents\Elements
 */
class NumberedList extends BulletList
{
    /**
     * @param array $elements Массив эллементов
     * @param int $depth Начальная глубина
     * @param int $style Стиль
     */
    public function __construct(array $elements, int $depth = 0, int $style = \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER)
    {
        parent::__construct($elements, $depth, $style);
    }
}