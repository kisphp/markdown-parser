<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlock;
use Kisphp\BlockFactory;
use Kisphp\BlockTypes;
use Kisphp\DataObject;

class BlockQuote extends AbstractBlock
{
    /**
     * @return string
     */
    public function parse()
    {
        $html = $this->getStartTag() . $this->getContent() . $this->getEndTag();

        return $this->parseInlineMarkup($html);
    }

    /**
     * @return string
     */
    public function getStartTag()
    {
        return '<blockquote>';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</blockquote>';
    }

    /**
     * @param DataObject $dataObject
     *
     * @return $this
     */
    public function changeLineType(DataObject $dataObject)
    {
        $nextLineNumber = $this->lineNumber + 1;
        if (!$dataObject->hasLine($nextLineNumber)) {
            return $this;
        }

        $nextLineObject = $dataObject->getLine($nextLineNumber);
        if (!is_a($nextLineObject, static::class)) {
            $this->setContent(
                $this->clearBlockQuoteMarkup(
                    $this->getContent()
                )
            );

            return $this;
        }

        $changeNextLine = true;
        $max = $dataObject->count();

        $updatedLines = [];
        for ($i = $this->lineNumber; $i < $max; $i++) {
            $currentLineObject = $dataObject->getLine($i);

            $updatedLines[] = $this->clearBlockQuoteMarkup($currentLineObject->getContent());

            $nextLineObject = $dataObject->getLine($i + 1);
            $nextSecondLineObject = $dataObject->getLine($i + 2);
            if (!is_a($nextLineObject, static::class) && !is_a($nextSecondLineObject, static::class)) {
                $changeNextLine = false;
            }

            $changedContent = BlockFactory::create(BlockTypes::BLOCK_SKIP);
            $dataObject->updateLine($i, $changedContent);

            if ($changeNextLine === false) {
                break;
            }
        }

        $this->parseSubBlock($dataObject, $updatedLines);
    }

    /**
     * @param string $lineContent
     *
     * @return string
     */
    protected function clearBlockQuoteMarkup($lineContent)
    {
        if (strpos($lineContent, '> ') === 0) {
            $lineContent = substr($lineContent, 2);
        }

        return $lineContent;
    }
}
