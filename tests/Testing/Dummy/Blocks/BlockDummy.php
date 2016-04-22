<?php

namespace Kisphp\Testing\Dummy\Blocks;

use Kisphp\AbstractBlock;

class BlockDummy extends AbstractBlock
{
    /**
     * @return string
     */
    public function parse()
    {
        $html = $this->getStartTag() . $this->cleanupMarkup($this->content) . $this->getEndTag();

        return $this->parseInlineMarkup($html);
    }

    /**
     * @return string
     */
    public function getStartTag()
    {
        return '<span>';
    }

    /**
     * @return string
     */
    public function getEndTag()
    {
        return '</span>';
    }

    protected function cleanupMarkup($text)
    {
        $text = preg_replace('/^\^/', '', $text);

        return trim($text);
    }

    /**
     * @param int $lineNumber
     * 
     * @return bool
     */
    public function validateLineType($lineNumber)
    {
        $content = $this->factory->getDataObject()->getLine($lineNumber);

        return (bool) (strpos($content, '^') === 0);
    }
}