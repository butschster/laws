<?php

namespace App\Documents\Elements;

use App\Contracts\Documents\ElementInterface;
use PhpOffice\PhpWord\Element\AbstractContainer;

class Title implements ElementInterface
{

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $subtitle;

    /**
     * @param string $title
     * @param string $subtitle
     */
    public function __construct(string $title, string $subtitle = null)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
    }

    /**
     * @param AbstractContainer $container
     *
     * @return void
     */
    public function insertTo(AbstractContainer $container)
    {
        $container->addText($this->title, [
            'bold' => true,
            'allCaps' => true,
            'size' => 12,
        ], [
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            'spaceAfter' => 10
        ]);

        if (! empty($this->subtitle)) {
            $container->addText($this->subtitle, null, [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            ]);
        }

        $container->addTextBreak();
    }
}