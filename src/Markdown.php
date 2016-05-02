<?php

namespace Kisphp;

class Markdown implements MarkdownInterface
{
    /**
     * @var DataObjectInterface
     */
    protected $dataObject;

    /**
     * @var MarkdownFactoryInterface
     */
    protected $factory;

    /**
     * @param MarkdownFactoryInterface $factory
     */
    public function __construct(MarkdownFactoryInterface $factory)
    {
        $this->factory = $factory;

        $this->createRules();
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function parse($text)
    {
        $this->setupDependencies($text);
        $this->convertLines();
        $this->validateLinesType();

        return $this->dataObject->parseEachLine();
    }

    /**
     * @param string $ruleKey
     * @param array $blockNameCollection
     *
     * @return $this
     */
    public function addRules($ruleKey, array $blockNameCollection)
    {
        $this->factory->addBlockPlugins($ruleKey, $blockNameCollection);

        return $this;
    }

    /**
     * @return $this
     */
    protected function createRules()
    {
        $this->factory
            ->addBlockPlugin('|', BlockTypes::BLOCK_TABLE)
            ->addBlockPlugins(':', [
                BlockTypes::BLOCK_CONTENT_BLOCKS,
                BlockTypes::BLOCK_TABLE,
            ])
            ->addBlockPlugin('=', BlockTypes::BLOCK_HEADER_ONE)
            ->addBlockPlugins('-', [
                BlockTypes::BLOCK_TABLE,
                BlockTypes::BLOCK_HEADER_TWO,
                BlockTypes::BLOCK_HORIZONTAL_RULE,
                BlockTypes::BLOCK_LIST,
            ])
            ->addBlockPlugin('#', BlockTypes::BLOCK_HEADER)
            ->addBlockPlugins('*', [
                BlockTypes::BLOCK_HORIZONTAL_RULE,
                BlockTypes::BLOCK_LIST,
            ])
            ->addBlockPlugin('_', BlockTypes::BLOCK_HORIZONTAL_RULE)
            ->addBlockPlugin('>', BlockTypes::BLOCK_QUOTE)
            ->addBlockPlugin('`', BlockTypes::BLOCK_CODE)
            ->addBlockPlugins(' ', [
                BlockTypes::BLOCK_CONTINUE,
                BlockTypes::BLOCK_INLINE_CODE,
            ])
            ->addBlockPlugin('+', BlockTypes::BLOCK_LIST)
            ->addBlockPlugin('1', BlockTypes::BLOCK_LIST)
            ->addBlockPlugin('2', BlockTypes::BLOCK_LIST)
            ->addBlockPlugin('3', BlockTypes::BLOCK_LIST)
            ->addBlockPlugin('4', BlockTypes::BLOCK_LIST)
            ->addBlockPlugin('5', BlockTypes::BLOCK_LIST)
            ->addBlockPlugin('6', BlockTypes::BLOCK_LIST)
            ->addBlockPlugin('7', BlockTypes::BLOCK_LIST)
            ->addBlockPlugin('8', BlockTypes::BLOCK_LIST)
            ->addBlockPlugin('9', BlockTypes::BLOCK_LIST)
        ;

        return $this;
    }

    /**
     * @return $this
     */
    protected function validateLinesType()
    {
        $max = $this->dataObject->count();

        for ($i = 0; $i < $max; $i++) {
            $lineObject = $this->dataObject->getLine($i);
            if (method_exists($lineObject, 'changeLineType')) {
                $lineObject->changeLineType($this->dataObject);
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function convertLines()
    {
        $max = $this->dataObject->count();

        for ($i = 0; $i < $max; $i++) {
            $this->dataObject->updateLine($i, $this->createLineObject($i));
        }

        return $this;
    }

    /**
     * @param int $lineNumber
     *
     * @return BlockInterface
     */
    protected function createLineObject($lineNumber)
    {
        return $this->createRowObjectByLineContent($lineNumber);
    }

    /**
     * @param string $text
     */
    protected function setupDependencies($text)
    {
        $this->dataObject = $this->factory->createDataObject($text);

        $this->factory->setDataObject($this->dataObject);
    }

    /**
     * @param int $lineNumber
     *
     * @throws Exceptions\BlockNotFoundException
     *
     * @return BlockInterface
     */
    public function createRowObjectByLineContent($lineNumber)
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
        return array_search($lineContent[0], array_keys($this->factory->getBlockPlugins()));
    }
}
