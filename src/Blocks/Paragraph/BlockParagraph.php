<?php

namespace Kisphp\Blocks\Paragraph;

use Kisphp\AbstractBlock;
use Kisphp\BlockInterface;
use Kisphp\BlockTypes;
use Kisphp\DataObjectInterface;

/**
 * handle paragraphs and merge connected paragraphs
 */
class BlockParagraph extends AbstractBlock
{
    /**
     * @var bool
     */
    protected $parsed = false;

    /**
     * @return string
     */
    public function parse()
    {
        $html = $this->getStartTag() . $this->content . $this->getEndTag();

        return $this->parseInlineMarkup($html);
    }

    /**
     * @return string
     */
    public function getStartTag()
    {
        return '<p>';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</p>';
    }

    public function changeLineType(DataObjectInterface $dataObject)
    {
        $nextLineNumber = $this->lineNumber + 1;
        if (!$dataObject->hasLine($nextLineNumber)) {
            return $this;
        }

        $max = $dataObject->count();
        $changeNextLine = true;

        $htmlCotent = [];
        for ($i = $this->lineNumber; $i < $max; $i++) {
            $currentLineObject = $dataObject->getLine($i);
            $htmlCotent[] = $currentLineObject->getContent();

            $nextLineObject = $dataObject->getLine($i + 1);
            $paragraphType = $this->factory->getClassNamespace(BlockTypes::BLOCK_PARAGRAPH);
            if (!$this->lineIsObjectOf($nextLineObject, $paragraphType)) {
                $changeNextLine = false;
            }

            $this->createSkipLine($dataObject, $i);

            if ($changeNextLine === false) {
                break;
            }

        }

        $newLineObject = $this->factory->create(BlockTypes::BLOCK_PARAGRAPH);
        $newLineObject
            ->setContent(implode(' ', $htmlCotent))
            ->setLineNumber($this->lineNumber)
        ;
        $dataObject->updateLine($this->lineNumber, $newLineObject);

        return $this;
    }
}
