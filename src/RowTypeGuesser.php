<?php

namespace Kisphp;

class RowTypeGuesser implements RowTypeGuesserInterface
{
    /**
     * @var MarkdownFactoryInterface
     */
    protected $factory;

    /**
     * @var DataObject
     */
    protected $dataObject;

    /**
     * @param DataObjectInterface $dataObject
     * @param MarkdownFactoryInterface $factoryInterface
     */
    public function __construct(DataObjectInterface $dataObject, MarkdownFactoryInterface $factoryInterface)
    {
        $this->dataObject = $dataObject;
        $this->factory = $factoryInterface;
    }

    /**
     * @param int $lineNumber
     *
     * @throws Exceptions\BlockNotFoundException
     *
     * @return BlockInterface
     */
    public function getRowObjectByLineContent($lineNumber)
    {
        $objectType = $this->getObjectTypeByLine($lineNumber);

        return $this->factory->create($objectType)
            ->setContent($this->dataObject->getLine($lineNumber))
            ->setLineNumber($lineNumber)
        ;
    }

    /**
     * @param int $lineNumber
     *
     * @return string
     */
    protected function getObjectTypeByLine($lineNumber)
    {
        $lineContent = $this->dataObject->getLine($lineNumber);

        if (empty($lineContent)) {
            return BlockTypes::BLOCK_EMPTY;
        }

        if ($this->getAvailableTypesByContent($lineContent) === false) {
            return BlockTypes::BLOCK_PARAGRAPH;
        }

        $blockPlugins = $this->factory->getBlockPlugins();
        $possibleTypes = $blockPlugins[$lineContent[0]];

        foreach ($possibleTypes as $type) {
            $blockObject = $this->factory->create($type);
            if ($blockObject->validateLineType($lineNumber) === true) {
                return $type;
            }
        }

        return BlockTypes::BLOCK_PARAGRAPH;
    }

    /**
     * @param string $lineContent
     *
     * @return int|string|bool
     */
    protected function getAvailableTypesByContent($lineContent)
    {
        if (empty($lineContent)) {
            return false;
        }

        return array_search($lineContent[0], array_keys($this->factory->getBlockPlugins()));
    }
}
