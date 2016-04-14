<?php

namespace Kisphp;

abstract class AbstractBlock implements BlockInterface
{
    /**
     * @var BlockFactory
     */
    protected $factory;

    /**
     * @var int
     */
    protected $lineNumber = 0;

    /**
     * @var string
     */
    protected $content;

    /**
     * @param BlockFactory $factory
     */
    public function __construct(BlockFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return string
     */
    abstract public function parse();

    /**
     * @return int
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
     * @param int $lineNumber
     *
     * @return BlockInterface
     */
    public function setLineNumber($lineNumber)
    {
        $this->lineNumber = $lineNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return BlockInterface
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @param string $newType
     *
     * @return BlockInterface
     */
    public function changeObjectType($newType)
    {
        $object = $this->factory->create($newType)
            ->setContent($this->getContent())
            ->setLineNumber($this->getLineNumber())
        ;

        return $object;
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function parseInlineMarkup($lineContent)
    {
        $lineContent = $this->parseInlineCode($lineContent);
        $lineContent = $this->parseInlineStrong($lineContent);
        $lineContent = $this->parseInlineEmphasis($lineContent);
        $lineContent = $this->parseInlineStrikethrough($lineContent);

        return $lineContent;
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function parseInlineCode($lineContent)
    {
        if (strpos($lineContent, '`') === false) {
            return $lineContent;
        }

        return $this->factory->create(BlockTypes::BLOCK_INLINE_CODE)
            ->setContent($lineContent)
            ->parse()
        ;
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function parseInlineStrong($lineContent)
    {
        return $this->factory->create(BlockTypes::BLOCK_STRONG)
            ->setContent($lineContent)
            ->parse()
        ;
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function parseInlineEmphasis($lineContent)
    {
        return $this->factory->create(BlockTypes::BLOCK_EMPHASIS)
            ->setContent($lineContent)
            ->parse()
        ;
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function parseInlineStrikethrough($lineContent)
    {
        return $this->factory->create(BlockTypes::BLOCK_STRIKETHROUGH)
            ->setContent($lineContent)
            ->parse()
        ;
    }

    /**
     * @param DataObject $dataObject
     * @param array $updatedLines
     */
    protected function parseSubBlock(DataObject $dataObject, array $updatedLines)
    {
        $markdown = $this->factory->createMarkdown();
        $md = implode("\n", $updatedLines);

        $newCodeParsed = $markdown->parse($md);
        $this->setContent($newCodeParsed);

        $newContent = $this->factory->create(BlockTypes::BLOCK_UNCHANGE)
            ->setContent($this->parse())
        ;

        $dataObject->updateLine($this->getLineNumber(), $newContent);
    }

    /**
     * @param BlockInterface $block
     * @param string $objectType
     *
     * @return bool
     */
    protected function lineIsObjectOf(BlockInterface $block = null, $objectType)
    {
        if ($block === null) {
            return false;
        }

        return (bool) (
            is_a($block, $objectType) || is_a($block, $this->factory->getClassNamespace(BlockTypes::BLOCK_CONTINUE))
        );
    }
}
