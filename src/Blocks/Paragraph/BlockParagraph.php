<?php

namespace Kisphp\Blocks\Paragraph;

use Kisphp\AbstractBlock;
use Kisphp\BlockInterface;
use Kisphp\DataObjectInterface;

/**
 * handle paragraphs and merge connected paragraphs
 */
class BlockParagraph extends AbstractBlock
{
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

    /**
     * @param DataObjectInterface $dataObject
     *
     * @return BlockInterface
     */
    public function changeLineType(DataObjectInterface $dataObject)
    {
        $nextLineNumber = $this->lineNumber + 1;
        if (!$dataObject->hasLine($nextLineNumber)) {
            return $this;
        }

        $nextLineObject = $dataObject->getLine($nextLineNumber);
        if (!is_a($nextLineObject, static::class)) {
            return $this;
        }

        $start = false;
        $changeNextLine = true;
        $max = $dataObject->count();

        for ($i = $this->lineNumber; $i < $max; $i++) {
            $currentLineObject = $dataObject->getLine($i);
            $newLineObject = $this->factory->create('BlockParagraphContinue');
            if ($start === false) {
                $start = true;
                $newLineObject = $this->factory->create('BlockParagraphStart');
            }

            $nextLineObject = $dataObject->getLine($i + 1);
            if (!is_a($nextLineObject, static::class)) {
                $newLineObject = $this->factory->create('BlockParagraphEnd');
                $changeNextLine = false;
            }

            $newLineObject
                ->setContent($currentLineObject->getContent())
                ->setLineNumber($currentLineObject->getLineNumber())
            ;
            $dataObject->updateLine($i, $newLineObject);

            if ($changeNextLine === false) {
                break;
            }
        }

        return $this;
    }
}
