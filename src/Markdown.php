<?php

namespace Kisphp;

class Markdown implements MarkdownInterface
{
    /**
     * @var DataObjectInterface
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
        $this->rowTypeGuesser = $this->factory->createRowTypeGuesser($this->dataObject);

        $this->convertLines();
        $this->validateLinesType();

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
    }

    /**
     * @param int $lineNumber
     *
     * @return BlockInterface
     */
    protected function createLineObject($lineNumber)
    {
        return $this->rowTypeGuesser->getRowObjectByLineContent($lineNumber);
    }
}
