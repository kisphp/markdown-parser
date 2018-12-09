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
     *
     * @throws \Kisphp\Exceptions\BlockNotFoundException
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
                BlockTypes::BLOCK_LIST,
                BlockTypes::BLOCK_SPACES_CODE,
                BlockTypes::BLOCK_CONTINUE,
                BlockTypes::BLOCK_EMPTY,
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
     *
     * @throws \Kisphp\Exceptions\BlockNotFoundException
     */
    protected function convertLines()
    {
        $max = $this->dataObject->count();

        for ($lineNumber = 0; $lineNumber < $max; $lineNumber++) {
            $this->dataObject->updateLine(
                $lineNumber,
                $this->createRowObjectByLineNumber($lineNumber)
            );
        }

        return $this;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    protected function setupDependencies($text)
    {
        $this->dataObject = $this->factory->createDataObject($text);

        $this->injectDataObjectIntoFactory();

        return $this;
    }

    /**
     * @return $this
     */
    protected function injectDataObjectIntoFactory()
    {
        $this->factory->setDataObject($this->dataObject);

        return $this;
    }

    /**
     * @param int $lineNumber
     *
     * @throws Exceptions\BlockNotFoundException
     *
     * @return BlockInterface
     */
    protected function createRowObjectByLineNumber($lineNumber)
    {
        $content = $this->grabReferences($lineNumber);
        $objectType = $this->getObjectTypeByContent($content, $lineNumber);

        return $this->factory->create($objectType)
            ->setContent($content)
            ->setLineNumber($lineNumber)
        ;
    }

    /**
     * @param int $lineNumber
     *
     * @return null|BlockInterface|mixed|string
     */
    protected function grabReferences($lineNumber)
    {
        $content = $this->dataObject->getLine($lineNumber);

        // grab urls with title
        $content = preg_replace_callback('/\[(.*)\]:\s?(.*)\s?"(.*)"/U', function ($found) {
            $key = trim($found[1]);
            $url = trim($found[2]);
            $title = trim($found[3]);

            $this->dataObject->addReference($key, [
                'url' => $url,
                'title' => $title,
                'type' => 'url',
            ]);

            return '';
        }, $content);

        // grab urls without title
        $content = preg_replace_callback('/\[(.*)\]:\s?(.*)/', function ($found) {
            $key = trim($found[1]);
            $url = trim($found[2]);

            $this->dataObject->addReference($key, [
                'url' => $url,
                'title' => '',
                'type' => 'url',
            ]);

            return '';
        }, $content);

        return $content;
    }

    /**
     * @param string $lineContent
     * @param int $lineNumber
     *
     * @return string
     *
     * @throws \Kisphp\Exceptions\BlockNotFoundException
     */
    protected function getObjectTypeByContent($lineContent, $lineNumber)
    {
        if (empty($lineContent) || trim($lineContent) === '') {
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
     * @return bool|int|string
     */
    protected function getAvailableTypesByContent($lineContent)
    {
        return array_search($lineContent[0], array_keys($this->factory->getBlockPlugins()), false);
    }
}
