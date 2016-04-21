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

        $paragraphNamespace = $this->factory->getClassNamespace(BlockTypes::BLOCK_PARAGRAPH);

        $nextLineObject = $dataObject->getLine($nextLineNumber);
        if ($this->lineIsObjectOf($nextLineObject, $paragraphNamespace) === false) {
            return $this;
        }

        $changeNextLine = true;
        $max = $dataObject->count();

        $paragraphContent = [];
        for ($i = $this->lineNumber; $i < $max; $i++) {
            $currentLineObject = $dataObject->getLine($i);
            dump($currentLineObject->getContent());
            $paragraphContent[] = trim($currentLineObject->getContent());

            $nextLineObject = $dataObject->getLine($i + 1);
            if ($this->lineIsObjectOf($nextLineObject, $paragraphNamespace) === false) {
                $changeNextLine = false;
            }

//            $this->createSkipLine($dataObject, $currentLineObject->getLineNumber());

            if ($changeNextLine === false) {
                break;
            }
        }

        $newLineObject = $this->factory->create(BlockTypes::BLOCK_PARAGRAPH);
        $newLineObject
            ->setContent(implode(' ', $paragraphContent))
            ->setLineNumber($this->lineNumber)
        ;
        $dataObject->updateLine($i, $newLineObject);

        return $this;
    }
}
