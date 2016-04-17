<?php

namespace Kisphp;

use Kisphp\Exceptions\MethodNotFoundException;

class RowTypeGuesser implements RowTypeGuesserInterface
{
    const BACKTICK_CODE = '96';

    /**
     * @var BlockFactoryInterface
     */
    protected $factory;

    /**
     * @var array
     */
    protected $blockTypes = [
        '=' => [BlockTypes::BLOCK_HEADER_ONE],
        '-' => [BlockTypes::BLOCK_HEADER_TWO, BlockTypes::BLOCK_HORIZONTAL_RULE, BlockTypes::BLOCK_LIST],
        '#' => [BlockTypes::BLOCK_HEADER],
        '*' => [BlockTypes::BLOCK_HORIZONTAL_RULE, BlockTypes::BLOCK_LIST],
        '_' => [BlockTypes::BLOCK_HORIZONTAL_RULE],
        '>' => [BlockTypes::BLOCK_QUOTE],
        '`' => [BlockTypes::BLOCK_CODE],
        ' ' => [BlockTypes::BLOCK_CONTINUE, BlockTypes::BLOCK_INLINE_CODE],
//        '|' => [BlockTypes::TYPE_TABLE],
        '+' => [BlockTypes::BLOCK_LIST],
        '1' => [BlockTypes::BLOCK_LIST],
        '2' => [BlockTypes::BLOCK_LIST],
        '3' => [BlockTypes::BLOCK_LIST],
        '4' => [BlockTypes::BLOCK_LIST],
        '5' => [BlockTypes::BLOCK_LIST],
        '6' => [BlockTypes::BLOCK_LIST],
        '7' => [BlockTypes::BLOCK_LIST],
        '8' => [BlockTypes::BLOCK_LIST],
        '9' => [BlockTypes::BLOCK_LIST],
    ];

    /**
     * @var DataObject
     */
    protected $dataObject;

    /**
     * @param DataObjectInterface $dataObject
     * @param BlockFactoryInterface $factoryInterface
     */
    public function __construct(DataObjectInterface $dataObject, BlockFactoryInterface $factoryInterface)
    {
        $this->dataObject = $dataObject;
        $this->factory = $factoryInterface;
    }

    /**
     * @param $lineNumber
     *
     * @throws Exceptions\BlockNotFoundException
     * @throws MethodNotFoundException
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

        if ($this->getAvailableTypesByContent($lineContent) === false) {
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
        $instance = $this->factory->getClassNamespace($blockName);

        if ($previousLine instanceof $instance) {
            return true;
        }

        return false;
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

        return array_search($lineContent[0], array_keys($this->blockTypes));
    }

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockList($lineNumber)
    {
        $lineContent = $this->dataObject->getLine($lineNumber);
        $lineContent = trim($lineContent);

        return (
            static::isBlockOrderedListByContent($lineContent)
            || static::isBlockUnorderedListByContent($lineContent)
        );
    }

    /**
     * @param string $lineContent
     *
     * @return bool
     */
    public static function isBlockUnorderedListByContent($lineContent)
    {
        return (bool) preg_match('/(^\*\s|^\-\s|^\+\s)/', $lineContent);
    }

    /**
     * @param string $lineContent
     *
     * @return bool
     */
    public static function isBlockOrderedListByContent($lineContent)
    {
        return (bool) preg_match('/(^[0-9]\.\s)/', $lineContent);
    }

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
    public function isBlockContinue($lineNumber)
    {
        if ($lineNumber < 1) {
            return false;
        }

        return (bool) preg_match('/^([\s]{1,}|[\t]+)\s/', $this->dataObject->getLine($lineNumber));
    }

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockHeaderOne($lineNumber)
    {
        if ($lineNumber < 1) {
            return false;
        }

        if (!$this->isLineTypeOf($lineNumber - 1, BlockTypes::BLOCK_PARAGRAPH)) {
            return false;
        }

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

        if (!$this->isLineTypeOf($lineNumber - 1, BlockTypes::BLOCK_PARAGRAPH)) {
            return false;
        }

        return (bool) preg_match('/([\-]{3,})/', $this->dataObject->getLine($lineNumber));
    }

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
        if (!isset($counter[self::BACKTICK_CODE]) || $counter[self::BACKTICK_CODE] !== 3) {
            return false;
        }

        return (bool) preg_match('/^([\`]{3})/', $lineContent);
    }

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockInlineCode($lineNumber)
    {
        $lineContent = $this->dataObject->getLine($lineNumber);
        if (preg_match('/([\s]{4,}|[\t]{1,})/', $lineContent)) {
            return true;
        }

        return false;
    }

    /**
     * @param int $lineNumber
     *
     * @return bool
     */
    public function isBlockQuote($lineNumber)
    {
        return (bool) preg_match('/^\>\s/', $this->dataObject->getLine($lineNumber));
    }
}
