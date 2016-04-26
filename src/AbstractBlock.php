<?php

namespace Kisphp;

abstract class AbstractBlock implements BlockInterface
{
    /**
     * @var MarkdownFactoryInterface
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
     * @param MarkdownFactoryInterface $factory
     */
    public function __construct(MarkdownFactoryInterface $factory)
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
     * do not validate type by default, inline block don't need this method
     *
     * @param int $lineNumber
     *
     * @return bool
     */
    public function validateLineType($lineNumber)
    {
        return false;
    }

    /**
     * @param DataObjectInterface $dataObject
     * @param int $lineNumber
     */
    protected function createSkipLine(DataObjectInterface $dataObject, $lineNumber)
    {
        $lineContent = $dataObject->getLine($lineNumber);

        $changedContent = $this->factory->create(BlockTypes::BLOCK_SKIP);
        $changedContent->setContent($lineContent->getContent());

        $dataObject->updateLine($lineNumber, $changedContent);
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function parseInlineMarkup($lineContent)
    {
        $lineContent = $this->factory
            ->parseCustomInlineMarkup($lineContent)
        ;
        $lineContent = $this->parseInlineStrongItalic($lineContent);
        $lineContent = $this->parseInlineCode($lineContent);
        $lineContent = $this->parseInlineStrong($lineContent);
        $lineContent = $this->parseInlineEmphasis($lineContent);
        $lineContent = $this->parseInlineStrikethrough($lineContent);
        $lineContent = $this->parseInlineImages($lineContent);
        $lineContent = $this->parseInlineUrls($lineContent);
        $lineContent = $this->parseInlineBlockTemplates($lineContent);

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
     * @param string $lineContent
     *
     * @return string
     */
    protected function parseInlineImages($lineContent)
    {
        return $this->factory->create(BlockTypes::BLOCK_IMAGE)
            ->setContent($lineContent)
            ->parse()
        ;
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function parseInlineUrls($lineContent)
    {
        return $this->factory->create(BlockTypes::BLOCK_URLS)
            ->setContent($lineContent)
            ->parse()
        ;
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

        $requiredTypeNamespace = $this->getFullClassNamespace($objectType);
        $blockContinueType = $this->getFullClassNamespace(BlockTypes::BLOCK_CONTINUE);

        return (bool) (
            ($block instanceof $requiredTypeNamespace) || ($block instanceof $blockContinueType)
        );
    }

    /**
     * @param string $className
     *
     * @return string
     */
    protected function getFullClassNamespace($className)
    {
        if (strpos($className, '\\') !== false) {
            return $className;
        }

        return $this->factory->getClassNamespace($className);
    }

    /**
     * @param $lineContent
     *
     * @return string
     */
    protected function parseInlineStrongItalic($lineContent)
    {
        return $this->factory->create(BlockTypes::BLOCK_STRONG_ITALIC)
            ->setContent($lineContent)
            ->parse()
        ;
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function parseInlineBlockTemplates($lineContent)
    {
        return $this->factory->create(BlockTypes::BLOCK_CODE_INJECTOR)
            ->setContent($lineContent)
            ->parse()
        ;
    }

    /**
     * @param array $updatedLines
     *
     * @return string
     */
    protected function getSubBlockParsedContent(array $updatedLines)
    {
        $markdown = $this->factory->createMarkdown();
        $markdownContent = implode("\n", $updatedLines);

        return $markdown->parse($markdownContent);
    }
}
