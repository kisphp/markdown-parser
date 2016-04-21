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
     * @var RowTypeGuesser
     */
    protected $rowTypeGuesser;

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

    protected function createRules()
    {
        $this->factory
            ->addBlockPlugin('|', BlockTypes::BLOCK_TABLE)
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

    /**
     * @param string $text
     */
    protected function setupDependencies($text)
    {
        $this->dataObject = $this->factory->createDataObject($text);
        $this->rowTypeGuesser = $this->factory->createRowTypeGuesser($this->dataObject);

        $this->factory->setDataObject($this->dataObject);
    }
}
