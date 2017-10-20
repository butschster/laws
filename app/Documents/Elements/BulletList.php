<?php

namespace App\Documents\Elements;

use App\Contracts\Documents\ElementInterface;
use PhpOffice\PhpWord\Element\AbstractContainer;

class BulletList implements ElementInterface
{

    /**
     * @var array
     */
    protected $elements;

    /**
     * @var int
     */
    protected $depth = 0;

    /**
     * @var int
     */
    protected $style;

    /**
     * @param array $elements
     * @param int $depth
     */
    public function __construct(array $elements, int $depth = 0, int $style = \PhpOffice\PhpWord\Style\ListItem::TYPE_BULLET_FILLED)
    {
        $this->elements = $elements;
        $this->depth = $depth;
        $this->style = $style;
    }

    /**
     * @param AbstractContainer $container
     *
     * @return void
     */
    public function insertTo(AbstractContainer $container)
    {
        foreach ($this->elements as $element) {
            if (is_array($element)) {
                (new self($element, $this->depth++))->insertTo($container);
            } else {
                $container->addListItem($element, $this->depth, null, null, 'P-Style')
                    ->getStyle()
                    ->setListType($this->style);
            }
        }
    }
}