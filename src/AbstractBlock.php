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
     * @return string
     */
    protected function parseInlineMarkup($lineContent)
    {
        // inline code
        if (strpos($lineContent, '`') !== false) {
            $lineContent = BlockFactory::create(BlockTypes::BLOCK_INLINE_CODE)
                ->setContent($lineContent)
                ->parse()
            ;
        }

        // strong
        $lineContent = BlockFactory::create(BlockTypes::BLOCK_STRONG)
            ->setContent($lineContent)
            ->parse()
        ;

        // inline emphasis
        $lineContent = BlockFactory::create(BlockTypes::BLOCK_EMPHASIS)
            ->setContent($lineContent)
            ->parse()
        ;

        // Strikethrough
        $lineContent = BlockFactory::create(BlockTypes::BLOCK_STRIKETHROUGH)
            ->setContent($lineContent)
            ->parse()
        ;

        return $lineContent;
    }
}
