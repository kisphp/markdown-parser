<?php

namespace Kisphp\Blocks\Lists;

use Kisphp\AbstractBlock;
use Kisphp\BlockFactory;
use Kisphp\BlockTypes;
use Kisphp\DataObject;

abstract class BlockList extends AbstractBlock
{
    const LIST_MARKUP_PATTERNS = '/^([\*\s]{1}[\s?]|[\-\s]{1}[\s?]|[\+\s]{1}[\s?]|[\d]{1}\.[\s?])/';

    /**
     * @return string
     */
    public function parse()
    {
        $html = $this->getStartTag() . $this->clearListMarkup($this->content) . $this->getEndTag();

        return $this->parseInlineMarkup($html);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    protected function clearListMarkup($content)
    {
        return preg_replace(self::LIST_MARKUP_PATTERNS, '', $content);
    }

    public function changeLineType(DataObject $dataObject)
    {
        $max = $dataObject->count();
        $changeNextLine = true;

        $updatedLines = [];
        for ($i = $this->lineNumber; $i < $max; $i++) {
            $currentLineObject = $dataObject->getLine($i);

            $updatedLines[] = $this->createLineContent($currentLineObject->getContent());

            //dump($currentLineObject->getContent());

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

    protected function getItemStartTag()
    {
        return '<li>';
    }

    protected function getItemEndTag()
    {
        return '</li>';
    }

    protected function createLineContent($lineContent)
    {
        $content = $this->clearListMarkup($lineContent);

        return $this->getItemStartTag() . $content . $this->getItemEndTag();
    }
}
