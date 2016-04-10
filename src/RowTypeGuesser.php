<?php

namespace Kisphp;

use Kisphp\Exceptions\MethodNotFoundException;

class RowTypeGuesser
{
    const BACKTICK_CODE = '96';
    /**
     * @var array
     */
    protected $blockTypes = [
        '=' => [BlockTypes::BLOCK_HEADER_ONE],
        '-' => [BlockTypes::BLOCK_HEADER_TWO, BlockTypes::BLOCK_HORIZONTAL_RULE],
        '#' => [BlockTypes::BLOCK_HEADER],
        '*' => [BlockTypes::BLOCK_HORIZONTAL_RULE],
        '_' => [BlockTypes::BLOCK_HORIZONTAL_RULE],
        '>' => [BlockTypes::BLOCK_QUOTE],
        '`' => [BlockTypes::BLOCK_CODE],

//        '|' => [self::TYPE_TABLE],
//        '*' => [self::TYPE_HORIZONTAL_RULE, self::TYPE_LIST],
//        '+' => [self::TYPE_LIST],
//        '_' => [self::TYPE_HORIZONTAL_RULE],
//        '`' => [self::TYPE_CODE, AbstractBlock::TYPE_INLINE_CODE],
//        '>' => [AbstractBlock::TYPE_BLOCKQUOTE],
//        '1' => [self::TYPE_ORDERED_LIST],
//        '2' => [self::TYPE_ORDERED_LIST],
//        '3' => [self::TYPE_ORDERED_LIST],
//        '4' => [self::TYPE_ORDERED_LIST],
//        '5' => [self::TYPE_ORDERED_LIST],
//        '6' => [self::TYPE_ORDERED_LIST],
//        '7' => [self::TYPE_ORDERED_LIST],
//        '8' => [self::TYPE_ORDERED_LIST],
//        '9' => [self::TYPE_ORDERED_LIST],
    ];

    /**
     * @var DataObject
     */
    protected $dataObject;

    /**
     * @param DataObject $dataObject
     */
    public function __construct(DataObject $dataObject)
    {
        $this->dataObject = $dataObject;
    }

    /**
     * @param $lineNumber
     *
     * @throws Exceptions\BlockNotFoundException
     * @throws MethodNotFoundException
     *
     * @return Interfaces\BlockInterface
     */
    public function getRowObjectByLineContent($lineNumber)
    {
        $objectType = $this->getObjectTypeByLine($lineNumber);

        return BlockFactory::create($objectType)
            ->setContent($this->dataObject->getLine($lineNumber))
            ->setLineNumber($lineNumber)
        ;
    }

    /**
     * @param $lineNumber
     *
     * @throws MethodNotFoundException
     *
     * @return string
     */
    protected function getObjectTypeByLine($lineNumber)
    {
        $lineContent = $this->dataObject->getLine($lineNumber);

        if (empty($lineContent)) {
            return BlockTypes::BLOCK_EMPTY;
        }

        if (array_search($lineContent[0], array_keys($this->blockTypes)) === false) {
            return BlockTypes::BLOCK_PARAGRAPH;
        }

        $possibleTypes = $this->blockTypes[$lineContent[0]];

        foreach ($possibleTypes as $type) {
            $methodName = 'is' . $type;

            if (!method_exists($this, $methodName)) {
                throw new MethodNotFoundException($methodName);
            }

            if (call_user_func([$this, $methodName], $lineNumber) === true) {
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
     */
    protected function isLineTypeOf($lineNumber, $blockName)
    {
        $previousLine = $this->dataObject->getLine($lineNumber);
        $instance = BlockFactory::getClassNamespace($blockName);

        if ($previousLine instanceof $instance) {
            return true;
        }

        return false;
    }

    /**
     * @param string $lineContent
     *
     * @return int|string|bool
     *
     * @deprecated
     */
    protected function getAvailableTypesByContent($lineContent)
    {
        if (empty($lineContent)) {
            return false;
        }

        return array_search($lineContent[0], array_keys($this->blockTypes));
    }

    /**
     * @param string $lineContent
     *
     * @return bool
     */
//    public function isInlineCode($lineContent)
//    {
//        return (bool) preg_match('/\`(.*)\`/', $lineContent);
//    }

    /**
     * @param string $lineContent
     *
     * @return bool
     */
//    public function isList($lineContent)
//    {
//        $lineContent = trim($lineContent);
//
//        return (bool) preg_match('/(^\*\s|^\-\s|^\+\s)/', $lineContent) || $this->isOrderedList($lineContent);
//    }

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockHeader($lineNumber)
    {
        return (bool) preg_match('/^([#]{1,6})\s/', $this->dataObject->getLine($lineNumber));
    }

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockHeaderOne($lineNumber)
    {
        return (bool) preg_match('/([\=]{3,})/', $this->dataObject->getLine($lineNumber));
    }

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockHeaderTwo($lineNumber)
    {
        if ($lineNumber < 1) {
            return false;
        }

        // check if previous line is paragraph
        if (!$this->isLineTypeOf($lineNumber - 1, BlockTypes::BLOCK_PARAGRAPH)) {
            return false;
        }

        return (bool) preg_match('/([\-]{3,})/', $this->dataObject->getLine($lineNumber));
    }

    /**
     * @param string $lineContent
     *
     * @return bool
     */
//    public function isOrderedList($lineContent)
//    {
//        $lineContent = trim($lineContent);
//
//        return (bool) preg_match('/(^[0-9]\.\s)/', $lineContent);
//    }

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockHorizontalRule($lineNumber)
    {
        return (bool) preg_match('/^([\*|\*\s|\-|\-\s|\_|\_\s]{3,})/', $this->dataObject->getLine($lineNumber));
    }

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockCode($lineNumber)
    {
        $lineContent = $this->dataObject->getLine($lineNumber);
        $counter = count_chars($lineContent, 1);
        if ($counter[self::BACKTICK_CODE] !== 3) {
            return false;
        }

        return (bool) preg_match('/^([\`]{3})/', $lineContent);
    }

    /*
     * @param string $lineContent
     *
     * @return bool
     */
    public function isBlockQuote($lineNumber)
    {
        return (bool) preg_match('/^\>\s/', $this->dataObject->getLine($lineNumber));
    }
}
