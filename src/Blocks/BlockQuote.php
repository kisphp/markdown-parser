<?php

namespace Kisphp\Blocks;

use Kisphp\AbstractBlock;
use Kisphp\BlockTypes;
use Kisphp\DataObjectInterface;

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
     * @param DataObjectInterface $dataObject
     *
     * @return $this
     */
    public function changeLineType(DataObjectInterface $dataObject)
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

            $changedContent = $this->factory->create(BlockTypes::BLOCK_SKIP);
            $dataObject->updateLine($i, $changedContent);

            if ($changeNextLine === false) {
                break;
            }
        }

        $this->parseSubBlock($dataObject, $updatedLines);
    }

    /**
     * @param DataObjectInterface $dataObject
     * @param array $updatedLines
     */
    protected function parseSubBlock(DataObjectInterface $dataObject, array $updatedLines)
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

    /**
     * @param int $lineNumber
     * @param DataObjectInterface $dataObject
     *
     * @return bool
     */
    public static function validateLineType($lineNumber, DataObjectInterface $dataObject)
    {
        return (bool) preg_match('/^\>\s/', $dataObject->getLine($lineNumber));
    }
}
