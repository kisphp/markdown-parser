<?php

namespace Kisphp;

use Kisphp\Interfaces\BlockInterface;

abstract class AbstractBlock implements BlockInterface
{
    /**
     * @var int
     */
    protected $lineNumber = 0;

    /**
     * @var string
     */
    protected $content;

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
        $object = BlockFactory::create($newType)
            ->setContent($this->getContent())
            ->setLineNumber($this->getLineNumber())
        ;

        return $object;
    }

    /**
     * @param string $lineContent
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
     * @return string
     */
    protected function parseInlineCode($lineContent)
    {
        if (strpos($lineContent, '`') === false) {
            return $lineContent;
        }

        return BlockFactory::create(BlockTypes::BLOCK_INLINE_CODE)
            ->setContent($lineContent)
            ->parse()
        ;
    }

    /**
     * @param string $lineContent
     * @return string
     */
    protected function parseInlineStrong($lineContent)
    {
        return BlockFactory::create(BlockTypes::BLOCK_STRONG)
            ->setContent($lineContent)
            ->parse()
        ;
    }

    /**
     * @param string $lineContent
     * @return string
     */
    protected function parseInlineEmphasis($lineContent)
    {
        return BlockFactory::create(BlockTypes::BLOCK_EMPHASIS)
            ->setContent($lineContent)
            ->parse()
        ;
    }

    /**
     * @param string $lineContent
     * @return string
     */
    protected function parseInlineStrikethrough($lineContent)
    {
        return BlockFactory::create(BlockTypes::BLOCK_STRIKETHROUGH)
            ->setContent($lineContent)
            ->parse()
        ;
    }
}
