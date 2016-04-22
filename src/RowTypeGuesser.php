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
     * @param int $lineNumber
     * @param string $blockName
     *
     * @return bool
     *
     * @deprecated use method from AbstractBlock
     */
//    protected function isLineTypeOf($lineNumber, $blockName)
//    {
//        $previousLine = $this->dataObject->getLine($lineNumber);
//        $instance = $this->factory->getClassNamespace($blockName);
//
//        if ($previousLine instanceof $instance) {
//            return true;
//        }
//
//        return false;
//    }

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

    /*
     * @param int $lineNumber
     *
     * @return bool
     */
//    public function isBlockTable($lineNumber)
//    {
//        $lineContent = $this->dataObject->getLine($lineNumber);
//        $lineContent = trim($lineContent);
//
//        if (strpos($lineContent, '|') !== false && strpos($lineContent, '---') !== false) {
//            return true;
//        }
//
//        return false;
//    }

    /*
     * @param int $lineNumber
     *
     * @return bool
     */
//    public function isBlockList($lineNumber)
//    {
//        $lineContent = $this->dataObject->getLine($lineNumber);
//        $lineContent = trim($lineContent);
//
//        return (
//            static::isBlockOrderedListByContent($lineContent)
//            || static::isBlockUnorderedListByContent($lineContent)
//        );
//    }

    /*
     * @param string $lineContent
     *
     * @return bool
     */
//    public static function isBlockUnorderedListByContent($lineContent)
//    {
//        return (bool) preg_match('/(^\*\s|^\-\s|^\+\s)/', $lineContent);
//    }

    /*
     * @param string $lineContent
     *
     * @return bool
     */
//    public static function isBlockOrderedListByContent($lineContent)
//    {
//        return (bool) preg_match('/(^[0-9]\.\s)/', $lineContent);
//    }

    /*
     * @param int $lineNumber
     *
     * @return bool
     */
//    public function isBlockHeader($lineNumber)
//    {
//        return (bool) preg_match('/^([#]{1,6})\s/', $this->dataObject->getLine($lineNumber));
//    }

    /*
     * @param int $lineNumber
     *
     * @return bool
     */
//    public function isBlockContinue($lineNumber)
//    {
//        if ($lineNumber < 1) {
//            return false;
//        }
//
//        return (bool) preg_match('/^([\s]{1,}|[\t]+)\s/', $this->dataObject->getLine($lineNumber));
//    }

    /*
     * @param int $lineNumber
     *
     * @return bool
     */
//    public function isBlockHeaderOne($lineNumber)
//    {
//        if ($lineNumber < 1) {
//            return false;
//        }
//
//        if (!$this->isLineTypeOf($lineNumber - 1, BlockTypes::BLOCK_PARAGRAPH)) {
//            return false;
//        }
//
//        return (bool) preg_match('/([\=]{3,})/', $this->dataObject->getLine($lineNumber));
//    }

    /*
     * @param int $lineNumber
     *
     * @return bool
     */
//    public function isBlockHeaderTwo($lineNumber)
//    {
//        if ($lineNumber < 1) {
//            return false;
//        }
//
//        if (!$this->isLineTypeOf($lineNumber - 1, BlockTypes::BLOCK_PARAGRAPH)) {
//            return false;
//        }
//
//        return (bool) preg_match('/([\-]{3,})/', $this->dataObject->getLine($lineNumber));
//    }

    /*
     * @param int $lineNumber
     *
     * @return bool
     */
//    public function isBlockHorizontalRule($lineNumber)
//    {
//        return (bool) preg_match('/^([\*|\*\s|\-|\-\s|\_|\_\s]{3,})/', $this->dataObject->getLine($lineNumber));
//    }

    /*
     * @param int $lineNumber
     *
     * @return bool
     */
//    public function isBlockCode($lineNumber)
//    {
//        $lineContent = $this->dataObject->getLine($lineNumber);
//        $counter = count_chars($lineContent, 1);
//        if (!isset($counter[self::BACKTICK_CODE]) || $counter[self::BACKTICK_CODE] !== 3) {
//            return false;
//        }
//
//        return (bool) preg_match('/^([\`]{3})/', $lineContent);
//    }

    /*
     * @param int $lineNumber
     *
     * @return bool
     */
//    public function isBlockInlineCode($lineNumber)
//    {
//        $lineContent = $this->dataObject->getLine($lineNumber);
//        if (preg_match('/([\s]{4,}|[\t]{1,})/', $lineContent)) {
//            return true;
//        }
//
//        return false;
//    }

    /*
     * @param int $lineNumber
     *
     * @return bool
     */
//    public function isBlockQuote($lineNumber)
//    {
//        return (bool) preg_match('/^\>\s/', $this->dataObject->getLine($lineNumber));
//    }
}
