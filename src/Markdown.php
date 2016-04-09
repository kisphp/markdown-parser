<?php

namespace Kisphp;

use Kisphp\Interfaces\BlockFactoryInterface;
use Kisphp\Interfaces\BlockInterface;

class Markdown
{
    /**
     * @var DataObject
     */
    protected $dataObject;

    /**
     * @var BlockFactoryInterface
     */
    protected $factory;

    /**
     * @var RowTypeGuesser
     */
    protected $rowTypeGuesser;

    /**
     * @param BlockFactoryInterface $factory
     */
    public function __construct(BlockFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function parse($text)
    {
        $this->dataObject = $this->factory->createDataObject($text);
        $this->rowTypeGuesser = new RowTypeGuesser($this->dataObject);

        $this->convertLines();
//        dump($this->dataObject->getLines());
        $this->validateLinesType();
//die;
        return $this->dataObject->parseEachLine();
    }

    protected function validateLinesType()
    {
        $max = $this->dataObject->count();

        for ($i = 0; $i < $max; $i++) {
            $lineObject = $this->dataObject->getLine($i);
            if (method_exists($lineObject, 'changeLineType')) {
                $lineObject->changeLineType($this->dataObject);
            }
        }
    }

    protected function convertLines()
    {
        $max = $this->dataObject->count();

        for ($i = 0; $i < $max; $i++) {
            $this->dataObject->updateLine($i, $this->createLineObject($i));
        }

        dump($this->dataObject->getLines());
//        die;
//        foreach ($this->dataObject->getLines() as $key => $value) {
//            $this->dataObject->updateLine(
//                $key,
//                $this->createBlockByContent($key, $value)
//            );
//        }
    }

    protected function createLineObject($lineNumber)
    {
        return $this->rowTypeGuesser->getRowObjectByLineContent($lineNumber);
    }

    /*
     * @param int $lineIndex
     * @param string $lineContent
     *
     * @return BlockInterface
     */
//    public function createBlockByContent($lineIndex, $lineContent)
//    {
//        $type = $this->getLineTypeByContent($lineContent, $lineIndex);
//
//        /** @var BlockInterface $block */
//        $block = $this->factory
//            ->create($type)
//            ->setContent($lineContent)
//            ->setLineNumber($lineIndex)
//        ;
//
//        return $block;
//    }
}
