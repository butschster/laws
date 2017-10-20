<?php

namespace App\Documents;

use App\Contracts\Documents\DocumentInterface;
use App\Contracts\Documents\ElementInterface;
use PhpOffice\PhpWord\PhpWord;

class SimpleDocument implements DocumentInterface
{

    /**
     * @var PhpWord
     */
    private $word;

    /**
     * @var \PhpOffice\PhpWord\Element\Section
     */
    private $section;

    /**
     * @param PhpWord $phpWord
     */
    public function __construct(PhpWord $phpWord)
    {
        $this->word = $phpWord;

        $phpWord->setDefaultFontName('Times New Roman');

        $this->section = $phpWord->addSection([
            'marginTop' => 800,
            'marginRight' => 800,
        ]);

        $phpWord->addParagraphStyle('P-Style', ['spaceAfter' => 50]);
    }

    /**
     * @param ElementInterface $element
     */
    public function addElement(ElementInterface $element)
    {
        $element->insertTo($this->section);
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function save(string $filename): string
    {
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($this->word);

        $path = ['app', 'public', 'documents', $filename];

        $objWriter->save($path = storage_path(implode(DIRECTORY_SEPARATOR, $path)));

        return $path;
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        return call_user_func_array([$this->section, $method], $arguments);
    }
}